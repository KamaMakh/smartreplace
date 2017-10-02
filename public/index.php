<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 18:57
 */
require_once('../configs/config.php');

/* ------------ Include plugin Smarty ------------ */

//require_once('../smarty_old/Smarty.class.php');
//require_once('../vendor/smarty/smarty/libs/Smarty.class.php');
//
//$smarty = new Smarty;
//$smarty->template_dir = '../app/views';
//$smarty->compile_dir = '../compile_views';
//$smarty->assign('my_dir', DIR_PATH);

$fenom = \Fenom::factory('../app/views', '../compile_views', $options);

$options = [
    "auto_reload" => "true"
];

echo'2222';
//$fenom->display('test fenom');

/* ---------------- Include End ------------------ */


/* ----------------- Auto Load End --------------- */
//$smarty->display('main.tpl');

$router = new \Megagroup\DynamicContent\Router();
session_start();
$router->run();
