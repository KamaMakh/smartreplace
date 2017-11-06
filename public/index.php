<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */

use Megagroup\SmartReplace\Renders;

require_once('../configs/config.php');
require_once ('../vendor/autoload.php');
require_once('../app/controllers/Router.php');
require_once('../app/renders/Render.php');

/* ------------ Include plugin Venom ------------ */

$options = [
    "auto_reload" => "true"
];
$fenom = new Renders\Render(new \Fenom\Provider('../app/views'));
$fenom->setCompileDir(__DIR__.'/../compile_views');
$fenom->setOptions($options);





$router = new \Megagroup\SmartReplace\Router();
session_start();
$router->run();
