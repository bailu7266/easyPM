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

// __sleep不能支持无名函数的补救措施：定义一个有名的function

function bootstrapPath($asset): string
{
    return sprintf('vendor/twbs/bootstrap/dist/%s', ltrim($asset, '/'));
}

class EasyPmApp
{
    private $config;
    protected $pdo;
    protected $dbSettings;
    protected $log;
    protected $view;
    protected $dbready = false;

    public function __construct()
    {
//        require_once 'Config.php';
        $this->config = new Config();

        // 连接RDMS，返回一个PDO对象
        try {
            $this->dbSettings = $this->config->get('database');
        } catch (NotFoundException $e) {
            echo 'DbConfig Error:' . $e->getMessage();
            exit();
        }

        if (($this->pdo = $this->connectDb()) == null) {
            exit(-1);
        }

        try {
            $loader = new Twig_Loader_Filesystem(__DIR__ . '/../Views');
            $this->view = new Twig_Environment($loader);
            // 尝试用无名函数（closur）
            /*$this->view->addFunction(new \Twig_SimpleFunction('asset', function ($asset) {
                // asset可以用来灵活布放资源的路径，目前什么也没有作。
                return sprintf('%s', ltrim($asset, '/'));
            }));*/
            // 测试 getFunctions(), 试验不成功，getFunction获得的是副本
            /*$functions = $this->view->getFunctions();
            if (array_key_exists('asset', $functions)) {
                unset($functions['asset']);
            }*/
            // 因为无名函数（closur）无法serialization, 改用有名函数
            $this->view->addFunction(new \Twig_SimpleFunction('assetBootstrap', 'easyPM\Core\bootstrapPath'));

            $this->log = new Logger('easypm');
            $logFile = $this->config->get('log');
            $this->log->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
        } catch (NotFoundException $e) {
            echo "Error:" . $e->getMessage();
        }
    }

    public function __sleep(): array
    {
        // $pdo = null equals to close database connection
        unset($this->pdo);

        return array('dbSettings', 'dbready', 'config', 'log', 'view');
    }

    public function __wakeup()
    {
        // re_connect to database after unserialization
        $this->pdo = $this->connectDb();
    }

    protected function connectDb(): PDO
    {
        // PDO __construct($dsn, $user, $password, $options)
        // $dsn的格式：postgresql:host=127.0.0.1;dbname=database_name
        try {
            $pdo = new PDO(
                $this->dbSettings['driver'] .
                ':host=' . $this->dbSettings['host'] .
                ';dbname=' . $this->dbSettings['dbname'],
                $this->dbSettings['user'],
                $this->dbSettings['password']
            );
        } catch (DbException $e) {
            echo 'Database Error:' . $e->getMessage();
        }
        return $pdo;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getLog()
    {
        return $this->log;
    }

    public function getPDO()
    {
        return $this->pdo;
    }

    public function isDbInitialized() : bool
    {
        return $this->dbready === true;
    }

    public function setDbReady()
    {
        $this->dbready = true;
    }
}
