<?php
namespace Megagroup\DynamicContent;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:42
 */
class MainController
{

    public function init() {
        global $smarty;

        $smarty->assign('check_user', $_SESSION['check_user']);
        $smarty->assign('user', $_SESSION['user']);
        $smarty->assign('check_user', $_SESSION['check_user']);
        $smarty->display('main.tpl');

    }

}
