<?php

namespace easyPM\Domain;

/**
 * 定义用户的基本属性和方法
 */
class User
{
    protected $id;          // 唯一标识
    protected $name;        // 用户登录名或者标识名，可以重名
    protected $password;    // 登录的密码
    protected $role;        // 角色，譬如普通用户，管理员等
    protected $nickName;    // 昵称，用来网络浏览/显示
    protected $email;       // 邮件地址，必须唯一，也可以用作登录名
    protected $iconFile;    // 存放icon的文件（全路径）

    function __construct()
    {
        //# code...
    }   
}

?>
