<?php

namespace Megagroup\SmartReplace;
use Megagroup\SmartReplace\Container;


/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 07.11.17
 * Time: 17:14
 */

class Application
{
    private static $_instance;
    private $container;

    public function __construct()
    {
        $this->container = new Container\Containers();
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    public function getBdConnect () {
        return $this->container->container['db'];
    }
    public  function getFenom () {
        return $this->container->container['fenom'];
    }

    public function getLogger () {
        return $this->container->container['logger'];
    }
}