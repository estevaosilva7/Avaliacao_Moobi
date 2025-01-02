<?php
require_once __DIR__ . '/../Database/MoobiDatabaseHandler.php';
require_once __DIR__ . '/../Models/DependenteModel.php';
require_once __DIR__ . '/../Session/CustomSessionHandler.php';
require_once __DIR__ . '/../Configuracoes/Url_Local.php';
require_once __DIR__ . '/../Utils/Valicacoes.php';


/**
 * Controlador responsável pelas operações relacionadas aos dependentes de um filiado.
 * Permite cadastrar, listar, editar e deletar dependentes.
 * Somente administradores têm permissão para realizar essas operações.
 *
 * @author Estevão carlosestevao@moobitech.com.br
 */

class DependenteController {
    private $dependenteModel;
    /**
     * Construtor da classe DependenteController.
     * Inicializa o modelo DependenteModel com a conexão PDO.
     *
     * @param $dbHandler Instância da classe MoobiDatabaseHandler.
     */
    public function __construct() {
        $dbHandler = new MoobiDatabaseHandler();
        $this->dependenteModel = new DependenteModel($dbHandler);
    }


    /**
     * Cadastra um novo dependente associado a um filiado.
     * Realiza validação de permissões e exibe a view de cadastro.
     *
     * @param array|null $aDados Dados do dependente a serem cadastrados.
     * @return void
     */
    public function cadastrar(?array $aDados = null): void {
        $mUsuarioLogado = CustomSessionHandler::get('usuario');
        if (!$mUsuarioLogado || $mUsuarioLogado['usu_Tipo'] != 'admin') {
            echo "<p>Acesso restrito. Somente administradores podem cadastrar dependentes.</p>";
            header("Location: /index.php/filiado/listar");
        }

        if (isset($aDados['cadastro'])) {
            $mNome = $aDados['nome'];
            $mDataNascimento = $aDados['dataNascimento'];
            $mGrauParentesco = $aDados['grauParentesco'];
            $mFiliadoId = $aDados['filiadoId'];

            try {
                Valicacoes::validarNome($mNome);
            } catch (Exception $e) {
                echo "<p>" . $e->getMessage() . "</p>";
                require_once __DIR__ . '/../Views/Dependente/CadastrarDependenteView.php';
                exit();
            }

            try {
                Valicacoes::validarNome($mGrauParentesco);
            } catch (Exception $e) {
                echo "<p>" . $e->getMessage() . "</p>";
                require_once __DIR__ . '/../Views/Dependente/CadastrarDependenteView.php';
                exit();
            }

            $this->dependenteModel->cadastrarDependente($mNome, $mDataNascimento, $mGrauParentesco, $mFiliadoId);

            header('Location: ' . Config::pegarUrl() . 'dependente/listar&id=' . $mFiliadoId);
            exit();
        }

        $mFiliadoId = $aDados['filiadoId'] ?? null;

        require_once __DIR__ . '/../Views/Dependente/CadastrarDependenteView.php';
    }


    /**
     * Lista os dependentes de um filiado.
     * Exibe todos os dependentes associados ao filiado com o ID fornecido.
     *
     * @param array|null $aDados Dados contendo o ID do filiado.
     * @return void
     */
    public function listar(?array $aDados = null): void {
	    $mFiliadoId = $aDados['id'];
        $mUsuarioLogado = CustomSessionHandler::get('usuario');
        $isAdmin = ($mUsuarioLogado && $mUsuarioLogado['usu_Tipo'] == 'admin');

        $aDependentes = $this->dependenteModel->listarPorFiliado($mFiliadoId);

        require_once __DIR__ . '/../Views/Dependente/ListarDependenteView.php';
    }

    /**
     * Edita os dados de um dependente.
     * Atualiza as informações do dependente com os dados fornecidos no formulário.
     *
     * @param array|null $aDados Dados do dependente a serem atualizados.
     * @return void
     */
    public function editar(?array $aDados = null): void {
        $mId = $aDados['id'];
        $usuarioLogado = CustomSessionHandler::get('usuario');
        if (!$usuarioLogado || $usuarioLogado['usu_Tipo'] != 'admin') {
            echo "<p>Acesso restrito. Somente administradores podem editar dependentes.</p>";
            header("Location: /index.php?path=filiado/listar");
            exit();
        }

        if (isset($aDados['atualizar'])) {
            $mNome = $aDados['nome'];
            $mDataNascimento = $aDados['dataNascimento'];
            $mGrauParentesco = $aDados['grauParentesco'];
            $mFiliadoId = $aDados['filiadoId'];

            try {
                Valicacoes::validarNome($mNome);

                Valicacoes::validarNome($mGrauParentesco);
            } catch (Exception $e) {
                echo "<p>" . $e->getMessage() . "</p>";
                header('Location: ' . Config::pegarUrl() . "dependente/editar?id={$mId}&filiadoId={$mFiliadoId}");
                exit();
            }

            $this->dependenteModel->editarDependente($mId, $mNome, $mDataNascimento, $mGrauParentesco);

            header('Location: ' . Config::pegarUrl() . "dependente/listar&id={$mFiliadoId}");
            exit();
        }

        $aDependente = $this->dependenteModel->buscarPorId($mId);

        if (!$aDependente) {
            die("Erro: Dependente não encontrado.");
        }
        require_once __DIR__ . '/../Views/Dependente/EditarDependenteView.php';
    }


    /**
     * Exclui um dependente do sistema.
     * Remove o dependente do banco de dados e redireciona para a lista de dependentes.
     *
     * @param array|null $aDados Dados contendo o ID do dependente e do filiado.
     * @return void
     */
    public function deletar(?array $aDados = null): void {
	    $mId = $aDados['id'];

	    $usuarioLogado = CustomSessionHandler::get('usuario');
        if (!$usuarioLogado || $usuarioLogado['usu_Tipo'] != 'admin') {
            echo "<p>Acesso restrito. Somente administradores podem excluir dependentes.</p>";
			header("Location: /index.php?path=filiado/listar");
        }
        $filiadoId = $aDados['filiadoId'];

        $this->dependenteModel->deletarDependente($mId);

        header('Location: ' . Config::pegarUrl() . 'dependente/listar&id='. $filiadoId);
    }
}
