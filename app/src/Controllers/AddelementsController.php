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
        $this->logger->addWarning('post',$_POST);

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
        $result = $this->addElements->reset($_GET['project_name']);

        if ( $result ) {
            $this->sendToClient($result);
        }
    }
    public function insertGroup() {
        $groups = $_POST;
        $this->addElements->insertGroup($groups);
    }
    public function addNewGroup () {
        $new_group = $_POST;
        echo $this->addElements->addNewGroup($new_group);
    }
    public function removeGroup () {
        $group_id = $_POST['group_id'];
        $this->addElements->removeGroup($group_id);
    }
}