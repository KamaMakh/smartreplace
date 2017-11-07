<?php

namespace Megagroup;

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

class Smart_Replace
{
    private static $_instance;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public  function getFenom () {
        return new Renders\Render(new \Fenom\Provider(__DIR__.'/../views'));
    }

    public function getLogger () {
        return new Logger('my_logger');
    }
}