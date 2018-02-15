<?php
namespace Megagroup\SmartReplace\Controllers;

use Megagroup\SmartReplace\Application;
use Megagroup\SmartReplace\Db;
use Megagroup\SmartReplace\Main;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:42
 */
class MainController
{
    public $fenom;
    private $logger;
    private $main;

    public function __construct()
    {
        $this->fenom = Application::getInstance()->getFenom();
        $this->logger = Application::getInstance()->getLogger();
        $this->main = new Main($this->fenom, $this->logger);
    }

    public function init() {
        $this->main->init();
    }

    public function checkScript() {
        $this->main->checkScript($_GET['site_url']);
        //$this->main->init();
        header('Location: /');
        exit;
    }
    public function addNewProject (){
        $this->main->addNewProject($_GET['project_id']);
    }

    public function editProjectName(){
        $this->main->editProjectName();
    }

}