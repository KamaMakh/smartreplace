<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */

use Megagroup\SmartReplace;
use Megagroup\SmartReplace\Controllers;

require_once(__DIR__.'/../configs/config.php');
require_once (__DIR__.'/../vendor/autoload.php');
require_once(__DIR__.'/../app/src/Router.php');
require_once(__DIR__.'/../app/src/Render.php');
require_once(__DIR__.'/../app/src/Application.php');


/* ------------ Include plugin Venom ------------ */


$fenom = SmartReplace\Application::getInstance()->getFenom();
$logger = SmartReplace\Application::getInstance()->getLogger();

$router = new SmartReplace\Router();
session_start();
$router->run();
