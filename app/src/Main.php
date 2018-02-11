<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 11.02.18
 * Time: 18:24
 */

namespace Megagroup\SmartReplace;


class Main
{
    public $fenom;
    public $logger;

    public function __construct(\Fenom $fenom, $logger)
    {
        $this->fenom = $fenom;
        $this->logger = $logger;
    }

    public function init() {
        $projects = Db::select("SELECT project_id,user_id,project_name FROM sr_projects JOIN sr_users WHERE email="."'".$_SESSION['user']['email']."'");
        //$this->logger->info("SELECT project_id,user_id,project_name FROM sr_projects JOIN sr_users WHERE email=".$_SESSION['user']['email']);
        //$this->logger->addWarning('$projects', $projects);

        $this->fenom->assign('projects', $projects);
        $this->fenom->display('main.tpl');
    }

}