<?php

/**
 * Classe para gerenciar as sessões personalizadas.
 * Contém métodos para iniciar a sessão, definir, obter, remover e destruir dados da sessão.
 *
 * @author Estevão carlosestevao@moobitech.com.br
 */
class CustomSessionHandler {
    /**
     * Inicia a sessão se ela ainda não estiver iniciada.
     * Verifica o status da sessão e chama session_start() caso necessário.
     * @return void
     */
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Define um valor em uma chave específica na sessão.
     *
     * @param string $key   A chave onde o valor será armazenado na sessão.
     * @param mixed $value  O valor a ser armazenado na sessão.
     * @return void
     */
    public static function set($key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Obtém um valor armazenado na sessão por sua chave.
     *
     * @param string $key   A chave do valor a ser recuperado.
     * @return mixed|null   O valor armazenado, ou null caso a chave não exista.
     */
    public static function get($key) {
        self::start();
        return $_SESSION[$key] ?? null;
    }

    /**
     * Remove um valor da sessão pelo seu identificador de chave.
     *
     * @param string $key   A chave do valor a ser removido.
     * @return void
     */
    public static function remove($key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Destrói a sessão e limpa todos os dados armazenados.
     *
     * @return void
     */
    public static function destroy(): void {
        self::start();
        session_destroy();
    }
}
