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


    public function init(string $email, string $name, string $password, string $confirm_password) {

        $errors = [];

        if (  $this->checkEmail($email, 0) === 'empty' ) {
            $errors[] = 'Пользователь с данным адресом почты уже существует!';
        }
        else if ( !empty($email) && $email != 'empty' ) {
            if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
                $errors[] = "Email введен некорректно!";
            }
        }
        else {
            $errors[] = "Заполните поле E-MAIL!";
        }

        if ( empty($name) || $name == 'empty' ) {
            $errors[] = "Заполните поле ИМЯ!";
        }

        if ( !empty($password) && $password != 'empty' ) {
            if ( mb_strlen($password) < 8 ) {
                $errors['ch'] = "Пароль должен быть не меньше 8 символов!";
            }
            else {
                if ( !empty($confirm_password) ) {
                    if ( $password != $confirm_password ) {
                        $errors[] = 'Пароли не совпадают!';
                    }
                } else {
                    $errors[] = 'Повторно введите пароль';
                }
            }
        }
        else {
            $errors[] = "Заполните поле Пароль!";
        }

        if ( !empty($errors)  ) {
            $this->showErrors($errors, 0);
            return;
        }

        $this->hash_password = $this->hashingPassword($password);

        $data_fields = [
            'email' => [$email, 's'],
            'nickname' =>  [$name, 's'],
            'password' => [$this->hash_password, 's']
        ];

        $result = Controllers\Db::insert($data_fields, 'sr_users');


        if ( $result ) {
           // $this->login($email, $password);
            return 'to_login';
        }
    }

    public function login(string $email,string $password) {

        $errors = [];

        if ( !empty($email) && $email != 'empty' ) {
            $user = $this->checkEmail($email, 1);

            if ( $user ) {
                $user = $user[0];
            }

            if ( empty($user) ) {
                $errors[] = "Логин или пароль введен не правильно!";
            } else {

                if ( !empty($password) && $password != 'empty' && $this->verifyPassword($password, $user['password'])) {
                        $user['login'] = 'true';
                        return $user;
                } else {
                    $errors[] = "Логин или пароль введен не правильно!";
                }

            }

        } else {
            $errors[] = "Заполните поле E-MAIL!";
        }

        if ( !empty($errors) ) {
            $this->showErrors($errors,1);
            return;
        }
    }

    public function getHtml( string $method, int $login ){
        if ( $method == 'GET' ) {
            $this->fenom->assign('login', $login);
            return $this->fenom->display('registration.tpl');
        }
    }

    protected function checkEmail  ( string $email, int $login  ) {
        $result = Controllers\Db::select( "SELECT email, password, nickname, status FROM sr_users WHERE email = '$email' " );
        if ( $login == 1 ) {
            if ( !empty($result) ) {
                return $result;
            } else{
                return false;
            }
        }
        else {
            if ( !empty($result) ) {
                return 'empty';
            } else{
                return $result;
            }
        }

    }

    protected function hashingPassword (string $password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    protected function verifyPassword (string $password, string $hash) {
        return password_verify($password, $hash);
    }

    public function showErrors (array $errors, int $login) {
        if ( $login == 1 ) {
            $this->fenom->assign('login', 1);
        }
        else{
            $this->fenom->assign('login', 0);
        }
        $this->fenom->assign('errors', $errors);
        print_r($errors['ch']);
        return $this->fenom->display('registration.tpl');
    }

    public function logout () {
        return 'logOut';
    }
}