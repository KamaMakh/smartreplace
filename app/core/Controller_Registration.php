<?php

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.09.17
 * Time: 19:52
 */
class Controller_Registration {
    private $nickName;
    private $email;
    private $name;
    private $password;
    private $confirm_password;
    private $hash_password;

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

    }

    protected function dataFilter($data){
        return strip_tags(trim($data));
    }

    public function init() {
        global $smarty;
        $errors = [];

        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            return $smarty->display('registration.tpl');
        }

        if ( !empty($this->email) ) {
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

        if ( !empty($errors) ) {
            $smarty->assign('errors', $errors);
            return $smarty->display('registration.tpl');
        } else {
            if ( $this->check_email($this->email) ) {
                $errors[] = 'Пользователь с данным адресом почты уже существует!';
                $smarty->assign('errors', $errors);
                return $smarty->display('registration.tpl');
            };
        }

        $this->hash_password = $this->hashing_password($this->password);

        $data_fields = [
            'email' => [$this->email, 's'],
            'nickname' =>  [$this->name, 's'],
            'password' => [$this->hash_password, 's']
        ];

        $result = Db::insert($data_fields, 'users');


        if ( $result ) {
            $this->login();
        }
    }

    protected function check_email  ($email) {
        $result = Db::select( "SELECT email FROM users WHERE email = '$email' " );
        if ( empty($result) ) {
            return false;
        }
        return true;
    }

    protected function hashing_password ($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    protected function verify_password ($password, $hash) {
        return password_verify($password, $hash);
    }

    public function login() {

        global $smarty;
        $errors = [];

        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $smarty->assign('login', 1);
            return $smarty->display('registration.tpl');
        }

        if ( !empty($this->email) ) {
            $user = Db::select("SELECT email, password, nickname, status FROM users WHERE email = '$this->email'");

            $user = $user[0];

            if ( empty($user) ) {
                $errors[] = "Логин или пароль введен не правильно!";
            } else {

                if ( !empty($this->password) ) {
                    if ( $this->verify_password($this->password, $user['password']) ) {

                        $_SESSION['check_user'] = 1;

                        $_SESSION['user'] = [
                            'status' => $user['status'],
                            'email' => $user['email'],
                            'nickname' => $user['nickname']
                        ];

                        header('Location: /');
                        exit();
                    }

                } else {
                    $errors[] = "Логин или пароль введен не правильно!";
                }

            }

        } else {
            $errors[] = "Заполните поле E-MAIL!";
        }

        if ( !empty($errors) ) {
            $smarty->assign('login', 1);
            $smarty->assign('errors', $errors);
            return $smarty->display('registration.tpl');
        }
    }

    public function logout () {
        unset($_SESSION['check_user']);
        unset($_SESSION['user']);
        header('Location: /');
    }
}