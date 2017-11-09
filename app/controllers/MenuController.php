<?php
namespace Megagroup\SmartReplace\Controllers;


use Megagroup\SmartReplace\Menu;
use Megagroup\Singleton\Application;
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.09.17
 * Time: 20:06
 */
class MenuController
{

    private $fenom;
    private $menu;

    public function __construct()
    {
        $this->fenom = Application::getInstance()->getFenom();
        $this->menu = new Menu($this->fenom);
    }
}