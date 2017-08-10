<?php

/**
// <@category:>Inde</@category>
// <@package:></@package>
// <@author:>Luke</@author:>
// <@license:>Free</@license>
// <@link:></@link>
*/

use easyPM\Core\Router;
use easyPM\Core\Request;
use easyPM\Core\EasyPmApp;

require_once __DIR__ . '/vendor/autoload.php';
// require_once __DIR__ . '/src/Core/EasyPmApp.php';

$APP_GLOBALS = new EasyPmApp();
$router = new Router($APP_GLOBALS);

// $request = new Request();
// var_dump($request);

$response = $router->route(new Request());
echo $response;

/*
$loader = new Twig_Loader_Filesystem(__DIR__ . '/Views');
$twig = new Twig_Environment($loader);

$propertyModel = new PropertyModel(Db:getInstance());
$property = $PropertyModel->get(1);

$params = ['property' => $property];
echo $twig->loadTemplate('property.twig')->render($params);
*/