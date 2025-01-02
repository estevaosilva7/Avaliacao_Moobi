<?php
/**
 * Classe Config.
 * Responsável por carregar e fornecer a URL de configuração do arquivo JSON.
 * A classe carrega o arquivo JSON contendo a URL de configuração e a disponibiliza através do método estático.
 *
 * @author Estevão carlosestevao@moobitech.com.br
 */

class Config {
    private static $mFile_local;

    /**
     * Carrega o arquivo de configurações JSON, se ainda não foi carregado.
     * Verifica se o arquivo de configurações existe, faz a leitura e decodifica seu conteúdo.
     * Caso o arquivo não seja encontrado ou haja erro na decodificação, uma exceção será lançada.
     *
     * @throws Exception Se o arquivo JSON não for encontrado ou não puder ser decodificado.
     */
    private static function carregarFile() {
        if (self::$mFile_local === null) {
            $sFilePath = 'app/Configuracoes/local_url.json';

            if (file_exists($sFilePath)) {
                $mJson = file_get_contents($sFilePath);
                self::$mFile_local = json_decode($mJson, true);
                if (self::$mFile_local === null) {
                    throw new Exception("Erro ao decodificar o arquivo JSON.");
                }
            } else {
                throw new Exception("Arquivo JSON não encontrado: {$sFilePath}");
            }
        }
    }

    /**
     * Retorna a URL local configurada no arquivo JSON.
     * Chama o método carregarFile para garantir que as configurações foram carregadas antes de retornar o valor.
     *
     * @return string URL local configurada no arquivo JSON.
     *
     * @throws Exception Se o arquivo JSON não puder ser carregado.
     */
    public static function pegarUrl() {
        self::carregarFile();
        return self::$mFile_local['local_url'];
    }
}
