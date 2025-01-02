<?php

class DependenteModel {
	private $pdo;
	/**
	 * Construtor da classe DependenteModel.
	 * Inicializa a conexão com o banco de dados utilizando o manipulador de banco.
	 *
	 * @param $oDbHandler Objeto responsável pela conexão com o banco de dados.
	 */
	public function __construct($oDbHandler) {
		$this->pdo = $oDbHandler->getConnection();
	}

	/**
	 * Cadastra um novo dependente.
	 *
	 * @param string $sNome Nome do dependente.
	 * @param string $sDataNascimento Data de nascimento do dependente.
	 * @param string $sGrauParentesco Grau de parentesco do dependente.
	 * @param int $iFiliadoId ID do filiado ao qual o dependente pertence.
	 * @return void
	 */
	public function cadastrarDependente($sNome, $sDataNascimento, $sGrauParentesco, $iFiliadoId): void {
		$sql = "INSERT INTO dependentes (dpe_Nome, dpe_Data_De_Nascimento, dpe_Grau_De_Parentesco, flo_Id)
                VALUES (:nome, :dataNascimento, :grauParentesco, :filiadoId)";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([
			':nome' => $sNome,
			':dataNascimento' => $sDataNascimento,
			':grauParentesco' => $sGrauParentesco,
			':filiadoId' => $iFiliadoId
		]);
	}

	/**
	 * Lista todos os dependentes de um filiado específico.
	 *
	 * @param int $iFiliadoId ID do filiado.
	 * @return array Lista de dependentes.
	 */
	public function listarPorFiliado($iFiliadoId) {
		$sSql = "SELECT * FROM dependentes WHERE flo_Id = :filiadoId";
		$stmt = $this->pdo->prepare($sSql);
		$stmt->execute([':filiadoId' => $iFiliadoId]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Edita as informações de um dependente.
	 *
	 * @param int $iId ID do dependente.
	 * @param string $sNome Nome do dependente.
	 * @param string $sDataNascimento Data de nascimento.
	 * @param string $sGrauParentesco Grau de parentesco.
	 * @return void
	 */
	public function editarDependente($iId, $sNome, $sDataNascimento, $sGrauParentesco): void {
		$sSql = "UPDATE dependentes 
				SET dpe_Nome = :nome, dpe_Data_De_Nascimento = :dataNascimento, dpe_Grau_De_Parentesco = :grauParentesco
				WHERE dpe_Id = :id";
		$stmt = $this->pdo->prepare($sSql);
		$stmt->execute([
			':nome' => $sNome,
			':dataNascimento' => $sDataNascimento,
			':grauParentesco' => $sGrauParentesco,
			':id' => $iId
		]);
	}

	/**
	 * Exclui um dependente.
	 *
	 * @param int $iId ID do dependente.
	 * @return void
	 */
	public function deletarDependente($iId): void {
		$sSql = "DELETE FROM dependentes 
       			WHERE dpe_Id = :id";
		$stmt = $this->pdo->prepare($sSql);
		$stmt->execute([':id' => $iId]);
	}

	/**
	 * Busca um dependente por ID.
	 *
	 * @param int $iId ID do dependente.
	 * @return array|null Dados do dependente ou null caso não encontrado.
	 */
	public function buscarPorId($iId) {
		$sSql = "SELECT * FROM dependentes WHERE dpe_Id = :id";
		$stmt = $this->pdo->prepare($sSql);
		$stmt->bindParam(':id', $iId);
		$stmt->execute();

		$dependente = $stmt->fetch(PDO::FETCH_ASSOC);
		return $dependente;
	}
}
?>
