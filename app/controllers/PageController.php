<?php
namespace Megagroup\SmartReplace\Controllers;


use Megagroup\SmartReplace;
use Megagroup\SmartReplace\Renders;
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
        $this->fenom = new Renders\Render(new \Fenom\Provider('../app/views'));
        $this->url = $url;
    }

    public function init() {
        $page = new SmartReplace\Pages($this->url,$this->fenom);
        $page->init();
    }
}