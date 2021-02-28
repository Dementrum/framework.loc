<?php

namespace ishop;

class ErrorHandler {

    //чтобы узнать состояние константы debug 
    public function __construct() {
        if (DEBUG) {
            error_reporting(-1);
        } else {
            error_reporting(0);
        }
        set_exception_handler([$this, 'exceptionHandler']);
    }

    public function exceptionHandler($e) {
        $this->logErrors($e->getMessage(), $e->getFile(), $e->getLine());
        $this->displayError('Исключение', $e->getMessage(), $e->getFile(), $e->getLine(), $e->getCode());
    }

    //метод для логирования ошибок
    protected function logErrors($message = '', $file = '', $line = '') {
        error_log("[" . date('Y-m-d H:i:s') . "] Текст ошибки: {$message} | Файл: {$file} | Строка: {$line}\n============================================\n",
            3, ROOT . '/tmp/errors.log');
    }

    //метод для вывода ошибок
    protected function displayError($errno, $errstr, $errfile, $errline, $responce = 404) {
        //отправить заголовок
        http_response_code($responce);
        if ($responce == 404 && !DEBUG) {
            require WWW . '/errors/404.php';
            die();
        }
        if (DEBUG) {
            require WWW . '/errors/dev.php';
        } else {
            require WWW . '/errors/prod.php';
        }
        die();
    }

}
