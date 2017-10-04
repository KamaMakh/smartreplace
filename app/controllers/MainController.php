<?php
namespace Megagroup\SmartReplace\Controllers;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:42
 */
class MainController
{

    public function init() {
        global $fenom;

//        $fenom->assign('check_user', $_SESSION['check_user']);
//        $fenom->assign('user', $_SESSION['user']);
//        $fenom->assign('check_user', $_SESSION['check_user']);
        $fenom->display('main.tpl');

    }

}

