<?php

namespace Megagroup\SmartReplace\Controllers;

use Megagroup\SmartReplace\User;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.09.17
 * Time: 19:52
 */

class RegistrationController {
    public function init () {
        $user = new User();
        $user->init();
    }

    public function login () {
        $user = new User();
        $user->login();
    }

    public function logout () {
        $user = new User();
        $user->logout();
    }
}