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
    private $hash_password;
    private $fenom;

    public function __construct(\Fenom $fenom){

        $this->fenom = $fenom;
        $this->fenom->assign('login', 0);

    }


    public function init(string $email, string $name, string $password, string $confirm_password, string $method) {

        $errors = [];


        if ( $method == 'GET' ) {
            $this->fenom->display('registration.tpl');
            return;
        }

        if ( !empty($email) && $email ) {
            if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                $errors[] = "Email введен некорректно!";
            };
        } else {
            $errors[] = "Заполните поле E-MAIL!";
        }

        if ( empty($name) ) {
            $errors[] = "Заполните поле ИМЯ!";
        }

        if ( !empty($password) ) {
            if ( mb_strlen($password) < 8 ) {
                $errors[] = "Пароль должен быть не меньше 8 символов!";
            } else {
                if ( !empty($confirm_password) ) {
                    if ( $password != $confirm_password ) {
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
            if ( $this->checkEmail($email) ) {
                $errors[] = 'Пользователь с данным адресом почты уже существует!';
                $this->fenom->assign('errors', $errors);
                return $this->fenom->display('registration.tpl');
            };
        }

        $this->hash_password = $this->hashingPassword($password);

        $data_fields = [
            'email' => [$email, 's'],
            'nickname' =>  [$name, 's'],
            'password' => [$this->hash_password, 's']
        ];

        $result = Controllers\Db::insert($data_fields, 'sr_users');


        if ( $result ) {
            $this->login($email,$password, 'GET');
        }
    }

    protected function checkEmail  (string $email) {
        $result = Controllers\Db::select( "SELECT email FROM sr_users WHERE email = '$email' " );
        if ( empty($result) ) {
            return false;
        }
        return true;
    }

    protected function hashingPassword (string $password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    protected function verifyPassword (string $password, string $hash) {
        return password_verify($password, $hash);
    }

    public function login(string $email,string $password,string $method) {

        $errors = [];

        if ( $method == 'GET' ) {
            $this->fenom->assign('login', 1);
            return $this->fenom->display('registration.tpl');
        }

        if ( !empty($email) ) {
            $user = Controllers\Db::select("SELECT email, password, nickname, status FROM sr_users WHERE email = '$email'");

            $user = $user[0];

            if ( empty($user) ) {
                $errors[] = "Логин или пароль введен не правильно!";
            } else {

                if ( !empty($password) ) {
                    if ( $this->verifyPassword($password, $user['password']) ) {
                        $user['login'] = 'true';
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