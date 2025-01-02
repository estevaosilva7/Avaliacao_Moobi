<?php

/**
 * Classe para gerenciar a conexão com o banco de dados utilizando PDO.
 * Contém métodos para estabelecer a conexão e retornar a instância do PDO.
 *
 * @author Estevão carlosestevano@moobitech.com.br
 */
class MoobiDatabaseHandler {
	private $mHost;
	private $mDb;
	private $mUser;
	private $mPassword;
	private $mPort;
	private $pdo;

	/**
	 * Construtor da classe MoobiDatabaseHandler.
	 * Inicializa os parâmetros de conexão e estabelece a conexão com o banco de dados.
	 * A configuração da conexão é carregada a partir de um arquivo JSON e a conexão é estabelecida com o banco de dados.
	 *
	 * @throws Exception Se o arquivo de configuração não for encontrado ou não puder ser lido.
	 */
	public function __construct() {
		$this->carregarJson();
		$this->connect();
	}

	/**
	 * Carrega as configurações de conexão com o banco de dados a partir de um arquivo JSON.
	 * O arquivo JSON contém as informações de host, banco de dados, usuário, senha e porta.
	 *
	 * @throws Exception Se o arquivo JSON não for encontrado ou se houver um erro ao decodificar o conteúdo.
	 */
	private function carregarJson() {
		$json = file_get_contents('app/Database/db_config.json');
		$config = json_decode($json, true);

		$this->mHost = $config['host'];
		$this->mDb = $config['db'];
		$this->mUser = $config['user'];
		$this->mPassword = $config['password'];
		$this->mPort = $config['port'];
	}

	/**
	 * Estabelece a conexão com o banco de dados utilizando PDO.
	 * Configura a instância PDO para lançar exceções em caso de erro.
	 *
	 * @throws PDOException Se houver erro na conexão com o banco de dados.
	 */
	private function connect() {
		try {
			$this->pdo = new PDO("mysql:host={$this->mHost};dbname={$this->mDb};port={$this->mPort}", $this->mUser, $this->mPassword);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			die("Erro de conexão: " . $e->getMessage());
		}
	}

	/**
	 * Retorna a instância do PDO para realizar consultas no banco de dados.
	 * Este método deve ser chamado para obter a conexão com o banco de dados e executar operações SQL.
	 *
	 * @return PDO A instância do PDO conectada ao banco de dados.
	 */
	public function getConnection() {
		return $this->pdo;
	}
}

// Teste da classe (comentado para evitar execução desnecessária)
// try {
//     $db = new MoobiDatabaseHandler();
//     $pdo = $db->getConnection();
//     echo "Conexão estabelecida com sucesso!";
// } catch (Exception $e) {
//     echo $e->getMessage();
// }

?>
