<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:06
 */

namespace Megagroup\SmartReplace;

use Megagroup\SmartReplace\Controllers\MainController;
use Megagroup\SmartReplace\Controllers\PageController;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

require_once('Db.php');

class Router
{
    protected $controller = 'Main';
    protected $action     = 'init';
    protected $params;
    public $logger;

    public function __construct()
    {
        $this->logger = Application::getInstance()->getLogger();
    }

    public function run() {
        if ( isset( $_SERVER['REQUEST_URI'] )  &&  $_SERVER['REQUEST_URI'] != '/' ) {
            $pre_url = explode('?', $_SERVER['REQUEST_URI']);
            if (!empty($pre_url[1])) {
                $url = trim($pre_url[0], '/');
            } else {
                $url = trim($_SERVER['REQUEST_URI'], '/');
            }
        } else {
            $url = '/';
        }

        if ($url != '/') {
            $url = explode('/', $url);
            $controller = ucfirst($url[0]) . 'Controller';
            $arr = scandir(__DIR__.'/Controllers');

            if (in_array(  $controller . '.php', $arr )) {

               $controller = 'Megagroup\SmartReplace\Controllers\\' . $controller;


                $controller = new $controller;

                if ( !empty($url[1]) ) {
                    $action = $url[1];
                    $controller->$action();
                } else {
                    $controller->init();
                }
            } else {
                $controller = new PageController($url[0]);
                $controller->init();
            }

        } else {
            $controller = new MainController();
            $controller->init();
        }
    }
}