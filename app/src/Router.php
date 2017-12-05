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

    public function run() {

       // $url =    ( isset( $_GET['url'] )  &&  $_GET['url'] != '/' )    ?    trim($_GET['url'], '/')    :    '/';

        if ( isset( $_GET['url'] )  &&  $_GET['url'] != '/' ) {
            $url = trim($_GET['url'], '/');
        } else {
            $url = '/';
        }

        if ($url != '/') {
            $url = explode('/', $url);

            $controller = ucfirst($url[0]) . 'Controller';
            $arr = scandir(__DIR__.'/Controllers');
            $logger = Application::getInstance()->getLogger();

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