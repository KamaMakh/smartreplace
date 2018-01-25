<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 23.01.18
 * Time: 15:21
 */

namespace Megagroup\SmartReplace\Controllers;


use Megagroup\SmartReplace\Srapi;
use Megagroup\SmartReplace\Application;

class SrApiController
{
    private $srApi;
    public $logger;

    public function __construct()
    {
        $this->logger = Application::getInstance()->getLogger();
        $this->srApi = new Srapi($this->logger);
    }

    public function getGroup () {

        $this->logger->addWarning('from_client', $_GET);
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
        echo json_encode($this->srApi->getGroup($_GET));
    }


}