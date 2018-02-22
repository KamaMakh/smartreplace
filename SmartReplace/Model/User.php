<?php

namespace SmartReplace\Model;

use MF\DB\MySQL as DB;
use Oauth\Session;

class User {


    public $id;
    public $last_activity;
    public $user_type;
    public $outer_user_id;
    public $first_name;
    public $last_name;
    public $patronymic;
    public $role;

    private $default_role = 1; // Репортер
    private $moderator_role = 2; // Модератор
    private $name_expire_time_hours = 2;

    public function __construct($outer_id,$type_id,$api,$debug) {

        //1) Получим общую информацию о пользователе

        $user = $this->_get($outer_id,$type_id);

        if (!$user) {
            $this->_create($outer_id,$type_id);
            $user = $this->_get($outer_id,$type_id);
        }

        $this->id = $user['user_id'];
        $this->last_activity = $user['last_activity'];
        $this->user_type_id = $user['user_type_id'];
        $this->outer_user_id = $user['outer_user_id'];
        $this->first_name = $user['first_name'];
        $this->patronymic = $user['patronymic'];
        $this->created = $user['created'];




        if (true) {

            $name_update = [
                'name_expires' => DB::expr('NOW() + INTERVAL ?d HOUR',[$this->name_expire_time_hours])
            ];
            //обновить фио
            if ($type_id == 1) { // клиент
                $api_data = Session::api($api['client_info_api']);

                if (isset($api_data['success']) && $api_data['success']) {

                    $name_update['first_name'] = $api_data['data']['first_name'];
                    $name_update['last_name'] = $api_data['data']['last_name'];
                    $name_update['email'] = $api_data['data']['email'];
                }

            } elseif ($type_id == 2) { // сотрудник

                if ($debug) {
                    $api_data['first_name'] = "Камрон";
                    $api_data['last_name'] = "Махмудов";
                } else {
                    $api_data = Session::api($api['staff_agent_info_api']);
                }

                if (isset($api_data['first_name']) && $api_data['first_name']) {

                    $name_update['first_name'] = $api_data['first_name'];
                    $name_update['last_name'] = $api_data['last_name'];
                }

            }
            $this->_update($name_update);

            if (isset($name_update['first_name'])) {
                $this->first_name = $name_update['first_name'];
            }

            if (isset($name_update['last_name'])) {
                $this->last_name = $name_update['last_name'];
            }
        }

        //3) Информация о роли пользователя

        $this->role = $this->_getRole($user['role_id']);

        // 4)Инфо о типе пользователя

        $this->type = $this->_getType($user['user_type_id']);

    }

    private function _create($outer_id,$type_id) {

        if ($type_id == 2) {
            return DB::insertIgnore(
                'user',
                [
                    'outer_user_id' => $outer_id,
                    'user_type_id' => $type_id,
                    'role_id' => $this->moderator_role
                ]
            );
        } else {
            return DB::insertIgnore(
                'user',
                [
                    'outer_user_id' => $outer_id,
                    'user_type_id' => $type_id,
                    'role_id' => $this->default_role
                ]
            );
        }
    }

    private function _get($outer_id,$type_id) {
        return DB::fetchRow(
            'SELECT
                `user_id`,
                `last_activity`,
                `user_type_id`,
                `outer_user_id`,
                `first_name`,
                `last_name`,
                `patronymic`,
                `created`,
                `role_id`,
                IF (`name_expires` < NOW(),1,0) AS `name_expired`
            FROM
                `user`
            WHERE
                `outer_user_id` = ?d
                AND `user_type_id` = ?d',[$outer_id,$type_id]
        );
    }

    private function _update ($data) {

        if ($this->id) {
            $cnt = DB::update('user',$data,['user_id' => $this->id]);

            if ($cnt || $cnt === 0) {
                return true;
            }
        }

        return false;

    }

    private function _getRole ($role_id) {
        return DB::fetchRow('
        SELECT
            `role_id`,
            `name`,
            `is_superuser`,
            `is_moderator`,
            `is_reporter`
        FROM
            `user_role`
        WHERE
            `role_id` = ?d',[$role_id]);
    }

    private function _getType ($user_type_id) {
        return DB::fetchRow('
        SELECT
            `user_type_id`,
            `name`
        FROM
            `user_type`
        WHERE
            `user_type_id` = ?d',[$user_type_id]);
    }

    public static function getUsersNames($user_ids) {
        if ($user_ids) {
            return DB::fetchHashAll('SELECT user_id, user_type_id, first_name, last_name, outer_user_id, patronymic, email FROM user WHERE user_id IN (?d)','user_id',[$user_ids]);
        } else {
            return [];
        }
    }

    public static function getUserName(int $user_id) {
        if ($user_id) {
            return DB::fetchRow("SELECT user_id, first_name, last_name, outer_user_id, patronymic FROM user WHERE user_id = $user_id");
        } else {
            return [];
        }
    }

    public static function getUserEmail(int $user_id) {
        if ($user_id) {
            $db = DB::fetchRow("SELECT email FROM user WHERE user_id = $user_id");
            return $db['email'];
        } else {
            return ['email' => ''];
        }
    }

    public static function updateUserEmail(int $user_id, string $user_email) {
        if ($user_id) {
            return DB::update('user',['email' => $user_email],['user_id' => $user_id]);
        } else {
            return false;
        }
    }

}
