<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */

use Megagroup\SmartReplace;

require_once(__DIR__.'/../configs/config.php');
require_once (__DIR__.'/../vendor/autoload.php');
require_once(__DIR__.'/../app/src/Router.php');
require_once(__DIR__.'/../app/src/Render.php');
require_once(__DIR__.'/../app/src/Application.php');


/* ------------ Include plugin Venom ------------ */

//$options = [
//    "auto_reload" => "true"
//];
$fenom = SmartReplace\Application::getInstance()->getFenom();
//$fenom->setCompileDir(__DIR__.'/../compile_views');
//$fenom->setOptions($options);

$logger = SmartReplace\Application::getInstance()->getLogger();


$router = new SmartReplace\Router();
session_start();
$router->run();
