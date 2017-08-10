<?php

namespace easyPM\Core;
use PDO;
use Exception;

class Db {
    private $instance;
    private function connect($dbSettings): PDO {
        $dbConfig = Config::getInstance()->get('db');
        try {
            return new PDO(
                'postgresql:host=127.0.0.1;dbname=easypm',
                $dbConfig['user'],
                $dbConfig['password']
            );
        } catch (Exception $e) {
            echo ("Database error: " . $e->getMessage());
        }
    }

    public static function getInstance(){
        if (self::$instance == null) {
            self::$instance = self::connect();
        }
        return self::$instance;
    }
}
