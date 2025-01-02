<?php
require_once __DIR__ . '/../Database/MoobiDatabaseHandler.php';
require_once __DIR__ . '/../Models/FiliadoModel.php';
require_once __DIR__ . '/../Session/CustomSessionHandler.php';
require_once __DIR__ . '/../Configuracoes/Url_Local.php';
require_once __DIR__ . '/../Utils/Valicacoes.php';

/**
 * Controlador para gerenciar as ações relacionadas aos filiados.
 * Contém métodos para  cadastro, listagem, exclusão, edição, atualização de filiados e verificação da existência de dependentes por filiado.
 *
 * @author Estevão carlosestevao@moobitech.com.br
 */

class FiliadoController {
	private $filiadoModel;

	/**
	 * Construtor da classe FiliadoController.
	 * Inicializa a instância do modelo de filiado com a conexão do banco de dados.
	 *
	 * @param $dbHandler Instância da classe MoobiDatabaseHandler.
	 */
	public function __construct()
	{
		$dbHandler = new MoobiDatabaseHandler();
		$this->filiadoModel = new FiliadoModel($dbHandler);
	}

	/**
	 * Exibe a tela de cadastro de filiado, caso o usuário tenha permissão.
	 * Usuários não administradores são redirecionados ao dashboard.
	 *
	 * @return void
	 */
	public function cadastrarFiliado(): void {
		$mUsuarioLogado = CustomSessionHandler::get('usuario');

		if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
			echo "<p>Acesso restrito. Somente administradores podem cadastrar filiados.</p>";
			require __DIR__ . '/../Views/Home/DashboardView.php';
		} else {
			require __DIR__ . '/../Views/Filiado/CadastrarFiliadoView.php';
		}
	}

	/**
	 * Realiza o cadastro de um novo filiado no sistema.
	 * Valida os dados informados e salva o registro no banco de dados.
	 *
	 * @param array|null $aDados Dados do filiado a ser cadastrado.
	 * @return void
	 */
	public function cadastrar(?array $aDados = null): void {
		try {
			$mNome = $aDados['nome'] ?? null;
			$mCpf = $aDados['cpf'] ?? null;
			$mRg = $aDados['rg'] ?? null;
			$mDataNascimento = $aDados['dataNascimento'] ?? null;
			$mIdade = $aDados['idade'] ?? null;
			$mEmpresa = $aDados['empresa'] ?? null;
			$mCargo = $aDados['cargo'] ?? null;
			$mSituacao = $aDados['situacao'] ?? null;
			$mTelefoneResidencial = $aDados['telefoneResidencial'] ?? null;
			$mCelular = $aDados['celular'] ?? null;

			Valicacoes::validarNome($mNome);
			Valicacoes::validarCPF($mCpf);
			Valicacoes::validarRG($mRg);
			Valicacoes::validarDataNascimento($mDataNascimento);
			Valicacoes::validarIdade((int)$mIdade);
			Valicacoes::validarCargoEmpresa($mEmpresa);
			Valicacoes::validarCargoEmpresa($mCargo);
			if (!empty($mTelefoneResidencial)) {
				Valicacoes::validarTelefoneFixo($mTelefoneResidencial);
			}
			if (!empty($mCelular)) {
				Valicacoes::validarTelefoneCelular($mCelular);
			}
			if ($this->filiadoModel->cadastrarFiliado($mNome, $mCpf, $mRg, $mDataNascimento, $mIdade, $mEmpresa, $mCargo, $mSituacao, $mTelefoneResidencial, $mCelular)) {
				$_SESSION['mensagem_sucesso'] = 'Filiado cadastrado com sucesso!';
				header('Location: ' . Config::pegarUrl() . 'filiado/listar');
				exit();
			} else {
				throw new Exception('Erro ao cadastrar filiado.');
			}
		} catch (Exception $e) {
			$_SESSION['mensagem_erro'] = $e->getMessage();
			require __DIR__ . '/../Views/Filiado/CadastrarFiliadoView.php';
		}
	}


	/**
	 * Lista todos os filiados cadastrados no sistema com páginação.
	 * Exibe as informações dos filiados na view de listagem.
	 *
	 * @param array|null $aDados Filtros para a listagem (nome, mês de nascimento).
	 * @return void
	 */
	public function listar(?array $aDados = null): void {
		$mUsuarioLogado = CustomSessionHandler::get('usuario');
		$isAdmin = ($mUsuarioLogado && $mUsuarioLogado['usu_Tipo'] == 'admin');

		$mNome = $aDados['nome'] ?? null;
		$mMesNascimento = $aDados['mes_nascimento'] ?? null;
        $mPaginaAtual = $aDados['pagina'] ?? 1;

        $iLimite = 10; // Número de registros por página
        $mOffset = ($mPaginaAtual - 1) * $iLimite;

        $filiados = $this->filiadoModel->listarFiliadosPaginados($mOffset, $iLimite, $mNome, $mMesNascimento);
        $iTotalFiliados = $this->filiadoModel->contarFiliados($mNome, $mMesNascimento);

		$mTotalPaginas = ceil($iTotalFiliados / $iLimite);

        require __DIR__ . '/../Views/Filiado/ListarFiliadoView.php';
	}


	/**
	 * Exibe a tela de edição de filiado, carregando os dados do filiado pelo ID.
	 * Caso o filiado não seja encontrado, exibe mensagem de erro e redireciona para a listagem.
	 *
	 * @param array|null $aDados Dados contendo o ID do filiado a ser editado.
	 * @return void
	 */
	public function editar(?array $aDados = null): void {
		$mId = $aDados['id'];
		$mFiliado = $this->filiadoModel->buscarFiliadoPorId($mId);
		if ($mFiliado) {
			require __DIR__ . '/../Views/Filiado/EditarFiliadoView.php';
			exit();
		} else {
			$_SESSION['mensagem_erro'] = 'Filiado não encontrado';
			header("Location: /index.php?path=filiado/listar");
			exit();
		}
	}

	/**
	 * Atualiza as informações de um filiado no sistema.
	 * Realiza a verificação de dados e atualiza as informações do filiado, redirecionando para a edição ou exibindo erro.
	 *
	 * @param array|null $aDados Dados atualizados do filiado (empresa, cargo, situação).
	 * @return void
	 */
	public function atualizar(?array $aDados = null): void {
		$mId = $aDados['id'];
		$mUsuarioLogado = CustomSessionHandler::get('usuario');
		if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
			echo "<p>Acesso restrito. Somente administradores podem editar filiados.</p>";
			header('Location: /index.php?path=filiado/listar');
			exit();
		}

		if ($aDados) {
			$mEmpresa = $aDados['empresa'] ?? null;
			$mCargo = $aDados['cargo'] ?? null;
			$mSituacao = $aDados['situacao'] ?? null;

			try {
				Valicacoes::validarCargoEmpresa($mEmpresa);
				Valicacoes::validarCargoEmpresa($mCargo);

				if ($this->filiadoModel->atualizarFiliado($mId, $mEmpresa, $mCargo, $mSituacao)) {
					$_SESSION['mensagem_sucesso'] = 'Filiado atualizado com sucesso!';
					header('Location: ' . Config::pegarUrl() . "filiado/editar&id=$mId");
					exit();
				} else {
					throw new Exception('Erro ao atualizar filiado.');
				}
			} catch (Exception $e) {
				$_SESSION['mensagem_erro'] = $e->getMessage();
				header('Location: ' . Config::pegarUrl() . "filiado/editar&id=$mId");
				exit();
			}
		} else {
			$_SESSION['mensagem_erro'] = 'Dados inválidos para atualização.';
			header('Location: /index.php?path=filiado/listar');
			exit();
		}
	}

	/**
	 * Realiza a exclusão de um filiado do sistema, dado o ID.
	 * Exibe mensagem de sucesso ou erro conforme o resultado da exclusão.
	 *
	 * @param array|null $aDados Dados contendo o ID do filiado a ser deletado.
	 * @return void
	 */
	public function deletar(?array $aDados = null): void {
		$mId = $aDados['id'];
		$mUsuarioLogado = CustomSessionHandler::get('usuario');
		if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
			echo "<p>Acesso restrito. Somente administradores podem excluir filiados.</p>";
			header('Location: /index.php?path=filiado/listar');
			exit();
		}
		if (!isset($mId) || !is_numeric($mId)) {
			$_SESSION['mensagem_erro'] = 'ID inválido';
			header("Location: /index.php?path=filiado/listar");
			exit();
		}
		if ($this->filiadoModel->deletarFiliado($mId)) {
			header('Location: ' . Config::pegarUrl() . 'filiado/listar');
		} else {
			$_SESSION['mensagem_erro'] = 'Erro ao deletar filiado';
			header("Location: /index.php?path=filiado/listar");
			exit();
		}
	}

	/**
	 * Exibe a lista de dependentes de um filiado.
	 * Caso não existam dependentes, exibe mensagem informando a ausência de dependentes para o filiado.
	 *
	 * @param array|null $aDados Dados contendo o ID do filiado cujos dependentes serão listados.
	 * @return void
	 */
	public function dependentes(?array $aDados = null): void {
		$mFiliadoId = $aDados['id'];

		$dependentes = $this->dependenteModel->listarPorFiliado($mFiliadoId);

		if (empty($dependentes)) {
			$sMensagem = "Não existem dependentes para este filiado.";
		} else {
			$sMensagem = "";
		}
		include 'Views/Dependente/DependenteListarView.php';
	}
}
?>
