<?php

// 系统初始化，主要初始化系统中需要的数据库表（migrate）
use easyPM\Core\Config;
use easyPM\Exceptions\NotFoundException;

// use PDO;
// use Exception;

require_once __DIR__ . '/vendor/autoload.php';

$config = new Config();
// 连接RDMS，返回一个PDO对象
try {
	$dbSettings = $config->get('database');

} catch (NotFoundException $e) {
	echo "没有数据配置：" . $e->getMessage();
	exit(-1);
}
// PDO __construct($dsn, $user, $password, $options)
// $dsn的格式：postgresql:host=127.0.0.1;dbname=database_name

$dsn = $dbSettings['driver'] . ':host=' . $dbSettings['host'] . ';dbname=' . $dbSettings['dbname'];

$pdo = new PDO($dsn, $dbSettings['user'], $dbSettings['password']);
if ($pdo == NULL)
{
    echo ("Failed to connect to database: " . $dsn);
    exit (-1);
}

// 初始化数据库表，共四张表：
// user：所以用户表
// equipment：所有设备的表单
// inventory：库存设备表单(目前不需要)
// in_use：已申领在用的设备

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS app_user (
id serial PRIMARY KEY,
name VARCHAR(64) NOT NULL,
password VARCHAR(16) NOT NULL,
nickname VARCHAR(64),
email VARCHAR (64) NOT NULL UNIQUE,
iconfile VARCHAR(128)
);
SQL;

$pdo->exec($sql);
if (($err = $pdo->errorInfo())[0] != 0)
{
    echo ("Failed to create table app_user [$err[1]]:" . $err[2]);
    exit(-1);
}

/* try {
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit(-1);
} */

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS equipment (
id serial PRIMARY KEY,
u_code VARCHAR(64) NOT NULL UNIQUE,
type VARCHAR(32),
catalog VARCHAR(32),
description VARCHAR(256) NOT NULL,
status BOOL DEFAULT false,
imgfile VARCHAR(128)
);
SQL;

$pdo->exec($sql);
if (($err = $pdo->errorInfo())[0] != 0)
{
    echo ("Failed to create table equipment [$err[1]]:" . $err[2]);
    exit(-1);
}

/*try {
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit(-1);
}*/

$sql = <<<SQL
CREATE TABLE IF NOT EXISTS in_use (
equipment_id INT NOT NULL,
user_id  INT,
PRIMARY KEY (equipment_id, user_id),
FOREIGN KEY (equipment_id) REFERENCES equipment(id) ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (user_id) REFERENCES app_user(id) ON DELETE SET NULL ON UPDATE CASCADE,
date DATE NOT NULL
);
SQL;

$pdo->exec($sql);
if (($err = $pdo->errorInfo())[0] != 0)
{
    echo ("Failed to create table in_use [$err[1]]:" . $err[2]);
    exit(-1);
}

/*try {
    $pdo->exec($sql);
} catch (PDOException $e) {
    echo $e->getMessage();
    exit(-1);
}*/


echo "数据库初始化大功告成";

?>
