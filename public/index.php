<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */
require_once('../configs/config.php');
require_once ('../vendor/autoload.php');
require_once ('../app/core/Router.php');

/* ------------ Include plugin Venom ------------ */

$options = [
    "auto_reload" => "true"
];

$fenom = new \Fenom(new Fenom\Provider('../app/views'));
$fenom->setCompileDir('../compile_views');
$fenom->setOptions($options);

$fenom->display('main.tpl', $_SERVER);


/* ----------------- Auto Load End --------------- */

$router = new \Megagroup\DynamicContent\Router();
session_start();
$router->run();
