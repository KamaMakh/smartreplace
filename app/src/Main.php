<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 11.02.18
 * Time: 18:24
 */

namespace Megagroup\SmartReplace;

use GuzzleHttp;


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
        $projects = Db::select("SELECT project_id,user_id,project_name,code_status FROM sr_projects JOIN sr_users WHERE email="."'".$_SESSION['user']['email']."'");
        //$this->logger->info("SELECT project_id,user_id,project_name FROM sr_projects JOIN sr_users WHERE email=".$_SESSION['user']['email']);


        foreach($projects as $key=>$project){
           $groups_count =  count(Db::select("SELECT group_id FROM sr_groups WHERE project_id=".$project['project_id']));
           $templates_count = count(Db::select("SELECT template_id FROM sr_templates WHERE project_id=".$project['project_id']));
           $projects[$key]['groups_count'] = $groups_count;
           $projects[$key]['templates_count'] = $templates_count;

            //$this->logger->addWarning('$project', $project);
        }

        $this->logger->addWarning('$projects', $projects);

        $this->fenom->assign('projects', $projects);
        $this->fenom->display('main.tpl');
    }

    public function checkScript ($site_url) {

        //$this->logger->info($this->site_url);

        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $site_url);

        $page = $res->getBody();

        $check_script = strstr($page, 'sr.service.js');
        if($check_script){
            $this->logger->info(explode('//',$site_url)[1]);
            Db::update(['code_status'=>true],'sr_projects','project_name='."'".explode('//',$site_url)[1]."'");
        }else{
            Db::update(['code_status'=>false],'sr_projects','project_name='."'".explode('//',$site_url)[1]."'");
        }
    }

}