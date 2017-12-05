<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 05.12.17
 * Time: 15:27
 */

use Pimple\Container;
use Megagroup\SmartReplace;

$container = new Container();

$container['fenom'] = function ($c) {
    return SmartReplace\Application::getInstance()->getFenom();
};

$container['logger'] = function ($c) {
  return SmartReplace\Application::getInstance()->getLogger();
};
