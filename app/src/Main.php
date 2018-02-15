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
        $projects = Db::select("SELECT project_id,user_id,project_name,code_status,project_alias FROM sr_projects JOIN sr_users WHERE email="."'".$_SESSION['user']['email']."'");
        //$this->logger->info("SELECT project_id,user_id,project_name FROM sr_projects JOIN sr_users WHERE email=".$_SESSION['user']['email']);


        foreach($projects as $key=>$project){
           $groups_count =  count(Db::select("SELECT group_id FROM sr_groups WHERE project_id=".$project['project_id']));
           $templates_count = count(Db::select("SELECT template_id FROM sr_templates WHERE project_id=".$project['project_id']));
           $projects[$key]['groups_count'] = $groups_count;
           $projects[$key]['templates_count'] = $templates_count;

            //$this->logger->addWarning('$project', $project);
        }

        //$this->logger->addWarning('$projects', $projects);

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

    public function addNewProject (int $project_id=null) {
        $email = $_SESSION['user']['email'];
        $user_id = Db::select("SELECT id FROM sr_users WHERE email='$email'")[0]['id'];

        //$this->logger->addWarning('post',$_POST);

        if(!$project_id){

            $check_name = $_POST['site_url'];
            $check_project = Db::select("SELECT project_id FROM sr_projects WHERE project_name="."'$check_name'");

            if ( !$check_project && strlen(strval($check_project))>0) {
                $projects_count = count(Db::select("SELECT project_id FROM sr_projects WHERE user_id =".$user_id));

                $data_fields = [
                    'user_id'=> [$user_id, 'i'],
                    'project_name'=> [$check_name, 's'],
                    'project_alias'=> ['Проект №'.($projects_count+1), 's']
                ];
                $project_id = Db::insert($data_fields, 'sr_projects', null, true);
            } else {
                $project_id = $check_project[0]['project_id'];
               // $this->logger->addWarning([0]['project_id']);
            }
        }

        header ('location: /');
        exit;
    }

    public function editProjectName(){
        //$this->logger->addWarning('post',$_POST);
        Db::update(['project_alias'=>$_POST['project_new_name']], 'sr_projects', 'project_id='.$_POST['project_id']);
        header ('location: /');
        exit;
    }

}