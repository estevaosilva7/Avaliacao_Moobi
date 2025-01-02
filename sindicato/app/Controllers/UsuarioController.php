<?php
require_once __DIR__ . '/../Database/MoobiDatabaseHandler.php';
require_once __DIR__ . '/../Models/UsuarioModel.php';
require_once __DIR__ . '/../Session/CustomSessionHandler.php';
require_once __DIR__ . '/../Configuracoes/Url_Local.php';
require_once __DIR__ . '/../Utils/Valicacoes.php';

/**
 * Controlador para gerenciar as ações relacionadas aos usuários.
 * Contém métodos para login, cadastro, listagem, exclusão, edição, atualização e logout de usuários.
 *
 * @author Estevão carlosestevao@moobitech.com
 */
class UsuarioController {
	private $usuarioModel;

	/**
	 * Construtor da classe UsuarioController.
	 * Inicializa a instância do modelo de usuário com a conexão do banco de dados.
	 *
	 * @param $dbHandler Instância da classe MoobiDatabaseHandler.
	 */
	public function __construct() {
		$dbHandler = new MoobiDatabaseHandler();
		$this->usuarioModel = new UsuarioModel($dbHandler);
	}

	/**
	 * Exibe a tela inicial de login.
	 *
	 * @return void
	 */
	public function index(): void {
		require __DIR__ . '/../Views/Home/LoginView.php';
	}

	/**
	 * Exibe a tela do dashboard para o usuário autenticado.
	 *
	 * @return void
	 */
	public function dashboard(): void {
		require __DIR__ . '/../Views/Home/DashboardView.php';
	}

	/**
	 * Realiza o login do usuário, validando o nome de usuário e senha.
	 * Caso o login seja bem-sucedido, inicia uma sessão e redireciona para o dashboard.
	 *
	 * @param array|null $aDados Dados de login do usuário (nome e senha).
	 * @return void
	 */
	public function login(?array $aDados = null): void {
		$mNome = $aDados['nome'] ?? null;
		$sSenha = $aDados['senha'] ?? null;

		if ($mNome && $sSenha) {
			$nUsuario = $this->usuarioModel->verificarUsuario($mNome, $sSenha);

			if ($nUsuario) {
				CustomSessionHandler::set('usuario', $nUsuario);
				require __DIR__ . '/../Views/Home/DashboardView.php';
				exit();
			} else {
				echo "<p>Nome ou senha incorretos!</p>";
			}
		}
		$this->index();
	}

	/**
	 * Realiza o cadastro de um novo usuário no sistema.
	 * Verifica se os dados são válidos e tenta salvar o novo usuário no banco.
	 *
	 * @param array|null $aDados Dados do novo usuário a ser cadastrado (nome, senha e tipo).
	 * @return void
	 */
	public function cadastrar(?array $aDados = null): void {
		$mUsuarioLogado = CustomSessionHandler::get('usuario');
		if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
			echo "<p>Acesso restrito. Somente administradores podem cadastrar usuários.</p>";
			require __DIR__ . '/../Views/Home/DashboardView.php';
			exit();
		}

		if (isset($aDados['cadastro'])) {
			$mNome = $aDados['nome'] ?? null;
			$sSenha = $aDados['senha'] ?? null;
			$mTipo = $aDados['tipo'] ?? null;

			try {
				Valicacoes::validarUsuario($mNome);
			} catch (Exception $e) {
				echo "<p>" . $e->getMessage() . "</p>";
				require __DIR__ . '/../Views/Usuario/CadastroUsuarioView.php';
				exit();
			}

			if ($this->usuarioModel->cadastrarUsuario($mNome, $sSenha, $mTipo)) {
				echo "<p>Usuário cadastrado com sucesso!</p>";
			} else {
				echo "<p>ALERTA: Nome de usuário já em uso!</p>";
			}
		}

		require __DIR__ . '/../Views/Usuario/CadastroUsuarioView.php';
		exit();
	}


	/**
	 * Lista todos os usuários cadastrados no sistema.
	 * Exibe as informações dos usuários na view de listagem.
	 *
	 * @return void
	 */
	public function listar(): void {
		$aUsuarios = $this->usuarioModel->listarUsuarios();
		$mUsuarioLogado = CustomSessionHandler::get('usuario');
		$isAdmin = $mUsuarioLogado && $mUsuarioLogado['usu_Tipo'] == 'admin';

		require __DIR__ . '/../Views/Usuario/ListarUsuarioView.php';
		exit();
	}

	/**
	 * Realiza a exclusão de um usuário do sistema, dado o ID.
	 * Exibe mensagem de sucesso ou erro conforme o resultado da exclusão.
	 *
	 * @param array|null $aDados Dados com o ID do usuário a ser deletado.
	 * @return void
	 */
	public function deletar(?array $aDados = null): void {
		$mUsuarioLogado = CustomSessionHandler::get('usuario');
		if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
			echo "<p>Acesso restrito. Somente administradores podem excluir usuários.</p>";
			header('Location: /index.php/usuario/listar');
			exit();
		}
		if ($aDados['id'] && is_numeric($aDados['id'])) {
			$bResultado = $this->usuarioModel->deletarUsuario($aDados['id']);
			if ($bResultado) {
				echo "<p>Usuário deletado com sucesso!</p>";
			} else {
				echo "<p>Erro ao deletar o usuário!</p>";
			}
		} else {
			echo "<p>ID inválido!</p>";
		}
		header('Location: ' . Config::pegarUrl() . 'usuario/listar');
		exit();
	}

	/**
	 * Exibe o formulário de edição de usuário.
	 * Realiza a verificação do ID e exibe as informações do usuário para edição.
	 *
	 * @param array|null $aDados Dados fornecidos para edição.
	 * @return void
	 */
	public function editar(?array $aDados = null): void {
		$mUsuarioLogado = CustomSessionHandler::get('usuario');
		if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
			echo "<p>Acesso restrito. Somente administradores podem editar usuários.</p>";
			header('Location: /index.php?path=usuario/listar');
			exit();
		}
		if ($aDados['id'] && is_numeric($aDados['id'])) {
			$aUsuario = $this->usuarioModel->buscarUsuarioPorId($aDados['id']);
			if (!$aUsuario) {
				echo "<p>Usuário não encontrado!</p>";
				header('Location: /index.php?path=usuario/listar');
				exit();
			}
			require __DIR__ . '/../Views/Usuario/EditarUsuarioView.php';

		} else {
			echo "<p>ID inválido!</p>";
			header('Location: /index.php?path=usuario/listar');
			exit();
		}
	}

	/**
	 * Atualiza os dados de um usuário no sistema.
	 * Verifica se o novo nome de usuário já existe e valida os dados antes de realizar a atualização.
	 *
	 * @param array|null $aDados Dados atualizados do usuário (nome, tipo).
	 * @return void
	 */
	public function atualizar(?array $aDados = null): void {
		$mId = $aDados['id'] ?? null;
		$mUsuarioLogado = CustomSessionHandler::get('usuario');

		if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
			echo "<p>Acesso restrito. Somente administradores podem editar usuários.</p>";
			header('Location: /index.php/usuario/listar');
			exit();
		}

		if (!$mId || !is_numeric($mId)) {
			CustomSessionHandler::set('mensagem_erro', "ID inválido!");
			header('Location: /index.php/usuario/listar');
			exit();
		}

		$mNome = $aDados['nome'] ?? null;
		$mTipo = $aDados['tipo'] ?? null;

		try {
			Valicacoes::validarUsuario($mNome);
		} catch (Exception $e) {
			CustomSessionHandler::set('mensagem_erro', $e->getMessage());
			header('Location: ' . Config::pegarUrl() . "usuario/editar&id=$mId");
			exit();
		}

		if ($this->usuarioModel->usuarioNomeExistente($mNome, $mId)) {
			CustomSessionHandler::set('mensagem_erro', "ALERTA! O nome de usuário já está em uso.");
			header('Location: ' . Config::pegarUrl() . "usuario/editar&id=$mId");
			exit();
		}

		if ($this->usuarioModel->atualizarUsuario($mId, $mNome, $mTipo)) {
			CustomSessionHandler::set('mensagem_sucesso', "Usuário atualizado com sucesso!");
			header('Location: ' . Config::pegarUrl() . "usuario/editar&id=$mId");
			exit();
		} else {
			CustomSessionHandler::set('mensagem_erro', "Erro ao atualizar o usuário!");
			header("Location: /index.php/usuario/editar&id=$mId");
			exit();
		}
	}

	/**
	 * Realiza o logout do usuário, destruindo a sessão.
	 * Redireciona o usuário para a página inicial.
	 *
	 * @return void
	 */
	public function logout(): void {
		CustomSessionHandler::destroy();
		header('Location: ' . Config::pegarUrl() . 'usuario/login');
		exit();
	}
}
?>
