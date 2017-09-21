<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */
require_once ('../config.php');

/* ------------ Include plugin Smarty ------------ */

require_once ('../smarty/Smarty.class.php');

$smarty = new Smarty;
$smarty->template_dir = '../app/views';
$smarty->compile_dir = '../compile_views';
$smarty->assign('my_dir', DIR_PATH);

/* ---------------- Include End ------------------ */

/* ---------------- Auto Load Classes ------------ */

require_once ('../vendor/autoload.php');

/* ----------------- Auto Load End --------------- */


$router = new Router;
session_start();
$router->run();
