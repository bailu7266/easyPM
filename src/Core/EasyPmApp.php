<?php

// 这个类的目的就是要解决配置文件，数据库，日志，web模板等依赖关系，是一种依赖注入
// （Dependency Injection)

namespace EasyPM\Core;

use PDO;
use Exception;
use esayPM\Exceptions\NotFoundException;
use esayPM\Exceptions\DbException;
use easyPM\Core\Config;
use Monolog\Logger;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Monolog\Handler\StreamHandler;

require_once 'Config.php';

class EasyPmApp
{
    private $config;
    protected $pdo;
    protected $log;
    protected $view;
    protected $dbready = false;

    public function __construct() {
        $this->config = new Config();

        // 连接RDMS，返回一个PDO对象
        try {
            $dbSettings = $this->config->get('database');
        } catch (NotFoundException $e) {
            echo 'DbConfig Error:' . $e->getMessage();
            exit();
        }

        // PDO __construct($dsn, $user, $password, $options)
        // $dsn的格式：postgresql:host=127.0.0.1;dbname=database_name
        try {
            $this->pdo = new PDO(
                $dbSettings['driver'] .
                ':host=' . $dbSettings['host'] .
                ';dbname=' . $dbSettings['dbname'],
                $dbSettings['user'],
                $dbSettings['password']
            );
        } catch (DbException $e) {
            echo 'Database Error:' . $e->getMessage();
            exit();
        }

        try {
            $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views');
            $this->view = new Twig_Environment($loader);
            $this->log = new Logger('easypm');
            $logFile = $this->config->get('log');
            $this->log->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
        } catch (NotFoundException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function getView() {
        return $this->view;
    }

    public function getLog() {
        return $this->log;
    }

    public function getPDO() { return $this->pdo; }

    public function isDbInitialized() : bool { return $this->dbready === true; }

    public function setDbReady() { $this->dbready = true; }
}
