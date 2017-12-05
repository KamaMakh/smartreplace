<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */

use Megagroup\SmartReplace;

require_once('../configs/config.php');
require_once ('../vendor/autoload.php');
require_once('../app/src/Router.php');
require_once('../app/src/Render.php');
require_once('../app/src/Application.php');
require_once('../app/src/container/Containers.php');

/* ------------ Include plugin Venom ------------ */

$options = [
    "auto_reload" => "true"
];
$fenom = $container['fenom'];
$fenom->setCompileDir(__DIR__.'/../compile_views');
$fenom->setOptions($options);

$logger = $container['logger'];


$router = new SmartReplace\Router();
session_start();
$router->run();
