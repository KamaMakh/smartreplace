<?php
namespace Megagroup\SmartReplace\Controllers;

use Megagroup\SmartReplace\Application;
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:42
 */
class MainController
{
    public $fenom;

    public function __construct()
    {
        $this->fenom = Application::getInstance()->getFenom();
    }

    public function init() {
        $this->fenom->display('main.tpl');
    }

}

