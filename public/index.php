<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */

use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Megagroup\SmartReplace;

require_once('../configs/config.php');
require_once ('../vendor/autoload.php');
require_once('../app/src/Router.php');
require_once('../app/src/Render.php');
require_once('../app/src/Application.php');

/* ------------ Include plugin Venom ------------ */

$options = [
    "auto_reload" => "true"
];
$fenom = SmartReplace\Application::getInstance()->getFenom();
$fenom->setCompileDir(__DIR__.'/../compile_views');
$fenom->setOptions($options);


$logger = SmartReplace\Application::getInstance()->getLogger();

$logger->pushHandler(new StreamHandler(__DIR__.'/../logs/my_app.log', $logger::DEBUG));
$logger->pushHandler(new FirePHPHandler());


$router = new SmartReplace\Router();
session_start();
$router->run();
