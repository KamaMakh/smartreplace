<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 15.09.17
 * Time: 20:06
 */

spl_autoload_register(function($class) {
    $path = explode('_', $class);

    if (strtolower($path[0]) == 'controller' || strtolower($path[0]) == 'db') {
        require_once DIR_PATH . '/app/core/' . $class . '.php';
    } else {
        require_once DIR_PATH . '/vendor/' . $class . '.php';
    }
});