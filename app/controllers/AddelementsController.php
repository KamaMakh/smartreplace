<?php
namespace Megagroup\SmartReplace\Controllers;


/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 18.09.17
 * Time: 18:24
 */

use Megagroup\SmartReplace\AddElements;
use Megagroup\Singleton\Application;



class AddelementsController
{
    private $site_url;
    private $fenom;
    private $method;
    private $mode;
    private $addElements;

    public function __construct()
    {
        $this->site_url = $_GET['site_url'];
        $this->fenom = Application::getInstance()->getFenom();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->addElements = new AddElements($this->fenom);

    }

    public function init () {
        $this->addElements->init($this->site_url);
    }

    public function getcontent () {
        $this->addElements->getcontent($this->site_url);
    }

    public function insertToDb () {
        $this->addElements->insertToDb( $this->method, $this->mode );
    }
}