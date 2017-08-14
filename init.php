<?php

// 系统初始化，主要初始化系统中需要的数据库表（migrate）
// use easyPM\Exceptions\DbException;

// use PDO;
// use Exception;

// require_once __DIR__ . '/vendor/autoload.php';

/*--
// 初始化数据库表，共四张表：
// user：所以用户表
// equipment：所有设备的表单
// inventory：库存设备表单(目前不需要)
// in_use：已申领在用的设备
--*/

function initDatabase($app)
{
    $pdo = $app->getPDO();

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
    if (($err = $pdo->errorInfo())[0] != 0) {
        throw new DbException("Failed to create table app_user [$err[1]]:" . $err[2]);
    }

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
    if (($err = $pdo->errorInfo())[0] != 0) {
        throw new DbException("Failed to create table app_user [$err[1]]:" . $err[2]);
    }

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
    if (($err = $pdo->errorInfo())[0] != 0) {
        throw new DbException("Failed to create table app_user [$err[1]]:" . $err[2]);
    }

    echo "数据库初始化大功告成";

    $app->setDbReady();
}
