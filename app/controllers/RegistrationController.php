<?php

namespace Megagroup\SmartReplace\Controllers;

use Megagroup\SmartReplace\User;
use Megagroup\SmartReplace\Renders;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.09.17
 * Time: 19:52
 */

class RegistrationController {

    private $nickName;
    private $email;
    private $name;
    private $password;
    private $confirm_password;
    private $method;
    private $fenom;
    private $user;

    public function __construct(){
        if (isset($_POST['nickName']))  {
            $this->nickName = $this->dataFilter($_POST['nickName']);
        }

        if (isset($_POST['email']))  {
            $this->email = $this->dataFilter($_POST['email']);
        }

        if (isset($_POST['name'])) {
            $this->name = $this->dataFilter($_POST['name']);
        }

        if (isset($_POST['password'])) {
            $this->password = $this->dataFilter($_POST['password']);
        }

        if (isset($_POST['confirm_password'])) {
            $this->confirm_password = $this->dataFilter($_POST['confirm_password']);
        }

        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->fenom = new Renders\Render(new \Fenom\Provider('../app/views'));

        $this->user = new User($this->fenom);
    }

    public function init () {
        $this->user->init($this->email,$this->name, $this->password, $this->confirm_password, $this->method);
    }

    public function login () {

        $result = $this->user->login($this->email, $this->password,  $this->method);

        if( $result['login'] == 'true' ) {
            $_SESSION['check_user'] = 1;

            $_SESSION['user'] = [
                'status' => $result['status'],
                'email' => $result['email'],
                'nickname' => $result['nickname']
            ];

            header('Location: /');
            exit();
        }
    }

    public function logout () {
        $result = $this->user->logout();

        if ( $result == 'logOut' ) {
            unset($_SESSION['check_user']);
            unset($_SESSION['user']);
            header('Location: /');
        }
    }

    protected function dataFilter($data){
        return strip_tags(trim($data));
    }
}