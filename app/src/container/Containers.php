<?php
namespace Megagroup\SmartReplace\container;
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 05.12.17
 * Time: 15:27
 */

use Pimple\Container;
use Megagroup\SmartReplace;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Containers
{
    public $container;

    public function __construct()
    {
        $this->container = new Container();

        $this->container['fenom'] = function ($c) {
            $fenom =  new SmartReplace\Render(new \Fenom\Provider(__DIR__ . '/../../views'));
            $fenom->setCompileDir(__DIR__.'/../../../compile_views');
            $options = [
                "auto_reload" => "true"
            ];
            $fenom->setOptions($options);
            return $fenom;
        };

        $this->container['logger'] = function ($c) {
            $logger = new Logger('my_logger');
            $logger->pushHandler(new StreamHandler(__DIR__.'/../../../logs/my_app.log', $logger::DEBUG));
            $logger->pushHandler(new FirePHPHandler());
            return $logger;
        };

        $this->container['db'] = function ($c) {
            return new \PDO("mysql:host=" . DB_CONF['host'] . ";dbname=" . DB_CONF['database'] . ";charset=" . DB_CONF['charset'], DB_CONF['user'], DB_CONF['password']);
        };

    }

}


//$container['fenom'] = function ($c) {
//    $fenom =  new SmartReplace\Render(new \Fenom\Provider(__DIR__ . '/../views'));
//    $fenom->setCompileDir(__DIR__.'/../../../compile_views');
//    $options = [
//        "auto_reload" => "true"
//    ];
//    $fenom->setOptions($options);
//    return $fenom;
//};
//
//$container['logger'] = function ($c) {
//    $logger = new Logger('my_logger');
//    $logger->pushHandler(new StreamHandler(__DIR__.'/../../../logs/my_app.log', $logger::DEBUG));
//    $logger->pushHandler(new FirePHPHandler());
//    return $logger;
//};
//
//$container['db'] = function ($c) {
//    return new \PDO("mysql:host=" . DB_CONF['host'] . ";dbname=" . DB_CONF['database'] . ";charset=" . DB_CONF['charset'], DB_CONF['user'], DB_CONF['password']);
//};

