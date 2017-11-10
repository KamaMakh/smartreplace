<?php
namespace Megagroup\SmartReplace\Controllers;


use Megagroup\SmartReplace\Application;
use Megagroup\SmartReplace;
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:39
 */
class PageController
{
    private $fenom;
    private $url;

    public function __construct($url)
    {
        $this->fenom = Application::getInstance()->getFenom();
        $this->url = $url;
    }

    public function init() {
        $page = new SmartReplace\Pages($this->url,$this->fenom);
        $page->init();
    }
}