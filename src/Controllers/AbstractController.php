<?php

namespace easyPM\Controllers;

use easyPM\Core\EasyPmApp;
use easyPM\Core\Config;
use easyPM\Core\Db;
use easyPM\Core\Request;
use Monolog\Logger;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Monolog\Handler\StreamHandler;

abstract class AbstractController {
    protected $request;
    // protected $app;
    protected $db;
    // protected $config;
    protected $view;
    protected $log;

    public function __construct(EasyPmApp $app, Request $request) {
        $this->request = $request;
        // $this->app = $app;
        $this->db = $app->getPDO();
        $this->view = $app->getView();
        $this->log = $app->getLog();

        // $this->config = Config::getInstance();
        // $loader = new Twig_Loader_Filesystem(
        //    __DIR__ . '/../../views'
        // );
        // $this->view = new Twig_Environment($loader);
        /* $this->log = new Logger('easyPM');
        $logFile = $this->config->get('log');
        $this->log->pushHandler(
            new StreamHandler($logFile, Logger::DEBUG)
        );*/
    }

    public function setUserId(int $userId) {
        $this->userId = $userId;
    }

    protected function render(string $template, array $params): string {
        return $this->view->loadTemplate($template)->render($params);
    }
}

?>
