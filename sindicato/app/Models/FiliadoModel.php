<?php
class FiliadoModel {
	private $pdo;
	/**
	 * Construtor da classe FiliadoModel.
	 * Inicializa a conexão com o banco de dados utilizando o manipulador de banco.
	 *
	 * @param $oBbHandler Objeto responsável pela conexão com o banco de dados.
	 */
	public function __construct($oBbHandler) {
		$this->pdo = $oBbHandler->getConnection();
	}

	/**
	 * Realiza o cadastro de um novo filiado no banco de dados.
	 *
	 * @param mixed $mNome Nome do filiado a ser cadastrado.
	 * @param mixed $mCpf CPF do filiado.
	 * @param mixed $mRg RG do filiado.
	 * @param mixed $mDataNascimento Data de nascimento do filiado.
	 * @param mixed $mIdade Idade do filiado.
	 * @param mixed $mEmpresa Empresa onde o filiado trabalha.
	 * @param mixed $mCargo Cargo do filiado.
	 * @param mixed $mSituacao Situação do filiado (ativo, aposentado, etc).
	 * @param mixed $mTelefoneResidencial Telefone residencial.
	 * @param mixed $mCelular Telefone celular.
	 * @return bool Retorna verdadeiro se o cadastro for bem-sucedido, falso caso contrário.
	 */
	public function cadastrarFiliado($mNome, $mCpf, $mRg, $mDataNascimento, $mIdade, $mEmpresa, $mCargo, $mSituacao, $mTelefoneResidencial, $mCelular) {
		$stmt = $this->pdo->prepare("INSERT INTO filiados (flo_Nome, flo_CPF, flo_RG, flo_Data_De_Nascimento, flo_Idade, flo_Empresa, flo_Cargo, flo_Situacao, flo_Telefone_Residencial, flo_Celular, flo_Data_Ultima_Atualizacao)
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

		return $stmt->execute([$mNome, $mCpf, $mRg, $mDataNascimento, $mIdade, $mEmpresa, $mCargo, $mSituacao, $mTelefoneResidencial, $mCelular]);
	}

	/**
	 * Lista todos os filiados cadastrados no banco de dados.
	 *
	 * @return array Retorna um array com os dados dos filiados.
	 */
	public function listarFiliados() {
		$stmt = $this->pdo->query("SELECT * FROM filiados");

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Deleta um filiado do banco de dados com base no ID fornecido.
	 *
	 * @param mixed $mId ID do filiado a ser deletado.
	 * @return bool Retorna verdadeiro se a exclusão for bem-sucedida, falso caso contrário.
	 */
	public function deletarFiliado($mId) {
		if (!is_numeric($mId)) {
			return false;
		}

		$stmt = $this->pdo->prepare("DELETE FROM filiados WHERE flo_Id = ?");

		return $stmt->execute([$mId]);
	}

	/**
	 * Busca um filiado no banco de dados pelo seu ID.
	 *
	 * @param mixed $mId ID do filiado a ser buscado.
	 * @return array|null Retorna os dados do filiado se encontrado, ou null se não encontrado.
	 */
	public function buscarFiliadoPorId($mId) {
		$stmt = $this->pdo->prepare("SELECT * FROM filiados WHERE flo_Id = ?");
		$stmt->execute([$mId]);

		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Atualiza os dados de um filiado no banco de dados.
	 *
	 * @param mixed $mId ID do filiado a ser atualizado.
	 * @param mixed $mEmpresa Nova empresa do filiado.
	 * @param mixed $mCargo Novo cargo do filiado.
	 * @param mixed $mSituacao Nova situação do filiado.
	 * @return bool Retorna verdadeiro se a atualização for bem-sucedida, falso caso contrário.
	 */
	public function atualizarFiliado($mId, $mEmpresa, $mCargo, $mSituacao) {
		$stmt = $this->pdo->prepare("UPDATE filiados 
                                 SET flo_Empresa = ?, flo_Cargo = ?, flo_Situacao = ?, flo_Data_Ultima_Atualizacao = NOW()
                                 WHERE flo_Id = ?");
		return $stmt->execute([$mEmpresa, $mCargo, $mSituacao, $mId]);
	}

	/**
	 * Lista os filiados com base em filtros opcionais (nome e mês de nascimento).
	 *
	 * @param string|null $sNome Nome do filiado (opcional).
	 * @param string|null $sMesNascimento Mês de nascimento do filiado (opcional).
	 * @return array Retorna um array com os filiados filtrados.
	 */
	public function listarFiliadosComFiltro(?string $sNome = null, ?string $sMesNascimento = null) {
		$sQuery = "SELECT * FROM filiados WHERE 1=1";
		$aParams = [];

		if ($sNome) {
			$sQuery .= " AND flo_Nome LIKE ?";
			$aParams[] = '%' . $sNome . '%';
		}

		if ($sMesNascimento) {
			$sQuery .= " AND MONTH(flo_Data_De_Nascimento) = ?";
			$aParams[] = $sMesNascimento;
		}

		$stmt = $this->pdo->prepare($sQuery);
		$stmt->execute($aParams);

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Lista os filiados paginados com base em filtros opcionais (nome e mês de nascimento).
	 *
	 * @param int $iOffset Posição inicial dos registros.
	 * @param int $iLimite Número máximo de registros a serem retornados.
	 * @param string|null $nParams Nome do filiado (opcional).
	 * @param string|null $nMesNascimento Mês de nascimento do filiado (opcional).
	 * @return array Retorna um array com os filiados paginados.
	 */
	public function listarFiliadosPaginados(int $iOffset, int $iLimite, ?string $nParams = null, ?string
	$nMesNascimento =  null) {
		$sSql = "SELECT * FROM filiados WHERE 1=1";

		if ($nParams) {
			$sSql .= " AND flo_Nome LIKE :nome";
		}

		if ($nMesNascimento) {
			$sSql .= " AND MONTH(flo_Data_De_Nascimento) = :mesNascimento";
		}

		$sSql .= " LIMIT :offset, :limite";

		$stmt = $this->pdo->prepare($sSql);

		if ($nParams) {
			$stmt->bindValue(':nome', '%' . $nParams . '%', PDO::PARAM_STR);
		}

		if ($nMesNascimento) {
			$stmt->bindValue(':mesNascimento', $nMesNascimento, PDO::PARAM_INT);
		}

		$stmt->bindValue(':offset', $iOffset, PDO::PARAM_INT);
		$stmt->bindValue(':limite', $iLimite, PDO::PARAM_INT);

		$stmt->execute();

		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	/**
	 * Conta o número total de filiados com base em filtros opcionais (nome e mês de nascimento).
	 *
	 * @param string|null $nNome Nome do filiado (opcional).
	 * @param string|null $sMesNascimento Mês de nascimento do filiado (opcional).
	 * @return int Retorna o número total de filiados.
	 */
	public function contarFiliados(?string $nNome = null, ?string $sMesNascimento = null): int {
		$sSql = "SELECT COUNT(*) AS total FROM filiados WHERE 1=1";

		if ($nNome) {
			$sSql .= " AND flo_Nome LIKE :nome";
		}

		if ($sMesNascimento) {
			$sSql .= " AND MONTH(flo_Data_De_Nascimento) = :mesNascimento";
		}

		$stmt = $this->pdo->prepare($sSql);

		if ($nNome) {
			$stmt->bindValue(':nome', '%' . $nNome . '%', PDO::PARAM_STR);
		}

		if ($sMesNascimento) {
			$stmt->bindValue(':mesNascimento', $sMesNascimento, PDO::PARAM_INT);
		}

		$stmt->execute();
		$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

		return (int)$resultado['total'];
	}
}
?>
