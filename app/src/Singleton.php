<?php

namespace Megagroup\Singleton;

use Megagroup\SmartReplace\Renders;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 07.11.17
 * Time: 17:14
 */

class Application
{
    private static $_instance;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function getBdConnect () {
        return new \PDO("mysql:host=" . DB_CONF['host'] . ";dbname=" . DB_CONF['database'] . ";charset=" . DB_CONF['charset'], DB_CONF['user'], DB_CONF['password']);
    }
    public  function getFenom () {
        return new Renders\Render(new \Fenom\Provider(__DIR__ . '/../views'));
    }

    public function getLogger () {
        return new Logger('my_logger');
    }
}