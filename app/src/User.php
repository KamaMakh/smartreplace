<?php

namespace Megagroup\SmartReplace;

use Megagroup\SmartReplace\Controllers;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 30.10.17
 * Time: 14:12
 */
class User
{
    private $nickName;
    private $email;
    private $name;
    private $password;
    private $confirm_password;
    private $hash_password;
    private $method;
    private $fenom;

    public function __construct($nickName,$email,$name,$password,$confirm_password, $method, $fenom){


        $this->nickName = $nickName;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
        $this->method = $method;
        $this->fenom = $fenom;
        $this->fenom->assign('login', 0);

    }


    public function init() {

        $errors = [];


        if ( $this->method == 'GET' ) {
            $this->fenom->display('registration.tpl');
            return;
        }

        if ( !empty($this->email) && $this->email ) {
            if ( !filter_var($this->email, FILTER_VALIDATE_EMAIL) ) {
                $errors[] = "Email введен некорректно!";
            };
        } else {
            $errors[] = "Заполните поле E-MAIL!";
        }

        if ( empty($this->name) ) {
            $errors[] = "Заполните поле ИМЯ!";
        }

        if ( !empty($this->password) ) {
            if ( mb_strlen($this->password) < 8 ) {
                $errors[] = "Пароль должен быть не меньше 8 символов!";
            } else {
                if ( !empty($this->confirm_password) ) {
                    if ( $this->password != $this->confirm_password ) {
                        $errors[] = 'Пароли не совпадают!';
                    }
                } else {
                    $errors[] = 'Повторно введите пароль';
                }
            }
        } else {
            $errors[] = "Заполните поле Пароль!";
        }

        if ( !empty($errors)  ) {
            $this->fenom->assign('errors', $errors);
            print_r($errors);
            return $this->fenom->display('registration.tpl');
        } else {
            if ( $this->checkEmail($this->email) ) {
                $errors[] = 'Пользователь с данным адресом почты уже существует!';
                $this->fenom->assign('errors', $errors);
                return $this->fenom->display('registration.tpl');
            };
        }

        $this->hash_password = $this->hashingPassword($this->password);

        $data_fields = [
            'email' => [$this->email, 's'],
            'nickname' =>  [$this->name, 's'],
            'password' => [$this->hash_password, 's']
        ];

        $result = Controllers\Db::insert($data_fields, 'sr_users');


        if ( $result ) {

            $this->login();
        }
    }

    protected function checkEmail  ($email) {
        $result = Controllers\Db::select( "SELECT email FROM sr_users WHERE email = '$email' " );
        if ( empty($result) ) {
            return false;
        }
        return true;
    }

    protected function hashingPassword ($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    protected function verifyPassword ($password, $hash) {
        return password_verify($password, $hash);
    }

    public function login() {


        $errors = [];

        if ( $this->method == 'GET' ) {
            $this->fenom->assign('login', 1);
            return $this->fenom->display('registration.tpl');
        }

        if ( !empty($this->email) ) {
            $user = Controllers\Db::select("SELECT email, password, nickname, status FROM sr_users WHERE email = '$this->email'");

            $user = $user[0];

            if ( empty($user) ) {
                $errors[] = "Логин или пароль введен не правильно!";
            } else {

                if ( !empty($this->password) ) {
                    if ( $this->verifyPassword($this->password, $user['password']) ) {

                        return $user;
                    }

                } else {
                    $errors[] = "Логин или пароль введен не правильно!";
                }

            }

        } else {
            $errors[] = "Заполните поле E-MAIL!";
        }

        if ( !empty($errors) ) {
            $this->fenom->assign('login', 1);
            $this->fenom->assign('errors', $errors);
            return $this->fenom->display('registration.tpl');
        }
    }

    public function logout () {
        return 'logOut';
    }
}