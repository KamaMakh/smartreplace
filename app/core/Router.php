<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:06
 */
namespace Megagroup\DynamicContent;

class Router
{
    protected $controller = 'Main';
    protected $action     = 'init';
    protected $params;

    public function run() {

        $url = (isset($_GET['url']) && $_GET['url'] != '/') ? trim($_GET['url'], '/') : '/';

        if ($url != '/') {
            $url = explode('/', $url);

            $controller =  ucfirst($url[0]) . 'Controller';

            if (file_exists( DIR_PATH . '/app/core/' . $controller . '.php' )) {
                $controller = new $controller;

                if ( !empty($url[1]) ) {
                    $action = $url[1];

                    $controller->$action();
                } else {
                    $controller->init();
                }
            } else {
                $controller = new Controller_Page($url[0]);
                $controller->init();
            }

        } else {

            $controller = $this->controller . 'Controller';
            $controller = new $controller;

            $controller->init();
        }
    }
}