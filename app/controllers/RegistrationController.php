<?php

namespace Megagroup\SmartReplace\Controllers;

use Megagroup\SmartReplace\User;
use Megagroup\Smart_Replace;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.09.17
 * Time: 19:52
 */

class RegistrationController {

    private $nickName;
    private $email ;
    private $name ;
    private $password;
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


        $this->method = $_SERVER['REQUEST_METHOD'];

        $this->fenom = Smart_Replace::getInstance()->getFenom();

        $this->user = new User($this->fenom);
    }

    public function init () {
        if ( $this->method == 'GET' ) {
            $this->user->getHtml($this->method, 0);
        }
        else {
            $result = $this->user->init($this->email,$this->name, $this->password);
            if ( $result == 'to_login' ) {
                $this->method = 'GET';
                header('location: /registration/login');
            }
        }
    }

    public function login () {
        if ( $this->method == 'GET' ) {
            $this->user->getHtml($this->method, 1);
        }
        else{
            $result = $this->user->login($this->email, $this->password);

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