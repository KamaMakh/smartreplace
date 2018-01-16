<?php
namespace Megagroup\SmartReplace\Controllers;


/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 18.09.17
 * Time: 18:24
 */

use Megagroup\SmartReplace\AddElements;
use Megagroup\SmartReplace\Application;



class AddelementsController
{
    private $site_url;
    private $fenom;
    private $logger;
    private $method;
    private $mode;
    private $addElements;

    public function __construct()
    {
        $this->site_url = $_GET['site_url'];
        $this->fenom = Application::getInstance()->getFenom();
        $this->logger = Application::getInstance()->getLogger();
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->addElements = new AddElements($this->fenom, $this->logger);

    }

    public function init () {
        $this->addElements->init($this->site_url);
    }

    public function getcontent () {
        $this->addElements->getcontent($this->site_url);
    }

    public function insertToDb () {
        //$this->mode = $_GET['mode'];
        $this->logger->addWarning('post',$_POST);
//        $this->logger->addWarning('request',$_REQUEST);
//        $this->logger->addWarning('get', $_GET);


        $result = $this->addElements->insertToDb( $this->method, 'add' );
        if ($result) {
            $this->sendToClient($result);
        }
    }

    public function sendToClient ($data) {
        $this->addElements->sendToClient($data);
    }

    public function complete () {
        $this->addElements->complete();
    }

    public function reset () {
        //$this->logger->addWarning('5555', $_GET);
        $result = $this->addElements->reset($_GET['project_name']);

        if ( $result ) {
            $this->sendToClient($result);
        }
    }
}