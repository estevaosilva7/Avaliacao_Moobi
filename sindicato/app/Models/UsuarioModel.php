<?php
/**
 * Modelo de usuário que interage com o banco de dados para realizar operações
 * relacionadas ao gerenciamento de usuários (login, cadastro, listagem, edição, atualização e exclusão).
 *
 * @author Estevão carlosestevao@moobitech.com.br
 */
class UsuarioModel {
    private $pdo;

    /**
     * Construtor da classe UsuarioModel.
     * Inicializa a conexão com o banco de dados utilizando o manipulador de banco.
     *
     * @param $oDbHandler Objeto responsável pela conexão com o banco de dados.
     */
    public function __construct($oDbHandler) {
        $this->pdo = $oDbHandler->getConnection();
    }

    /**
     * Verifica se um usuário existe no banco de dados, comparando o nome e a senha.
     *
     * @param mixed $mNome Nome do usuário a ser verificado.
     * @param mixed $mSenha Senha do usuário a ser verificada.
     * @return array|null Retorna os dados do usuário se encontrado e a senha for válida, ou null se não encontrado.
     */
    public function verificarUsuario($mNome, $mSenha) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE usu_Nome = ?");

        $stmt->execute([$mNome]);

        $mUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($mUsuario && password_verify($mSenha, $mUsuario['usu_Senha'])) {
            return $mUsuario;
        }

        return null;
    }

    /**
     * Realiza o cadastro de um novo usuário no banco de dados.
     *
     * @param mixed $mNome Nome do usuário a ser cadastrado.
     * @param mixed $mSenha Senha do usuário a ser cadastrada.
     * @param mixed $mTipo Tipo do usuário (por exemplo, "admin", "comum").
     * @return bool Retorna verdadeiro se o cadastro for bem-sucedido, falso caso contrário.
     */
    public function cadastrarUsuario($mNome, $mSenha, $mTipo) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usu_Nome = ?");

        $stmt->execute([$mNome]);

        if ($stmt->fetchColumn() > 0) {
            return false;
        }

        $sSenhaHash = password_hash($mSenha, PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare("INSERT INTO usuarios (usu_Nome, usu_Senha, usu_Tipo) VALUES (?, ?, ?)");

        return $stmt->execute([$mNome, $sSenhaHash, $mTipo]);
    }

    /**
     * Lista todos os usuários cadastrados no banco de dados.
     *
     * @return array Retorna um array com os dados dos usuários (ID, nome e tipo).
     */
    public function listarUsuarios() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Deleta um usuário do banco de dados com base no ID fornecido.
     *
     * @param mixed $mId ID do usuário a ser deletado.
     * @return bool Retorna verdadeiro se a exclusão for bem-sucedida, falso caso contrário.
     */
    public function deletarUsuario($mId) {
        if (!is_numeric($mId)) {
            return false;
        }

        $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE usu_Id = ?");

        return $stmt->execute([$mId]);
    }

    /**
     * Busca um usuário no banco de dados pelo seu ID.
     *
     * @param mixed $mId ID do usuário a ser buscado.
     * @return array|null Retorna os dados do usuário se encontrado, ou null se não encontrado.
     */
    public function buscarUsuarioPorId($mId) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE usu_Id = ?");
        $stmt->execute([$mId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Atualiza os dados de um usuário no banco de dados.
     *
     * @param mixed $mId ID do usuário a ser atualizado.
     * @param mixed $mNome Novo nome do usuário.
     * @param mixed $mTipo Novo tipo do usuário.
     * @return bool Retorna verdadeiro se a atualização for bem-sucedida, falso caso contrário.
     */
    public function atualizarUsuario($mId, $mNome, $mTipo) {
        $stmt = $this->pdo->prepare("UPDATE usuarios SET usu_Nome = ?, usu_Tipo = ? WHERE usu_Id = ?");
        return $stmt->execute([$mNome, $mTipo, $mId]);
    }

    /**
     * Verifica se o nome de usuário já existe no banco de dados,
     * ignorando o ID do usuário atual.
     *
     * @param mixed $mNome Nome do usuário a ser verificado.
     * @param mixed $mId ID do usuário a ser ignorado na verificação.
     * @return bool Retorna verdadeiro se o nome de usuário já existir, falso caso contrário.
     */
    public function usuarioNomeExistente($mNome, $mId) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE usu_Nome = ? AND usu_Id != ?");

        $stmt->execute([$mNome, $mId]);

        return $stmt->fetchColumn() > 0;
    }
}
?>