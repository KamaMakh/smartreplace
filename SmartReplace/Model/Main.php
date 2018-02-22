<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.02.18
 * Time: 16:27
 */

namespace SmartReplace\Model;

use GuzzleHttp;
use MF\DB\MySQL as DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Main
{
    public $fenom;
    public $logger;

    public function __construct()
    {
        $logger = new Logger('my_logger');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/my_app.log', $logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());

        $this->logger = $logger;
    }

    public static function init($user_id) {


        $projects = Db::fetchAll("SELECT project_id,user_id,project_name,code_status,project_alias FROM sr_projects WHERE user_id=".$user_id);

        foreach($projects as $key=>$project){
            $groups_count =  count(Db::fetchAll("SELECT group_id FROM sr_groups WHERE project_id=".$project['project_id']));
            $templates_count = count(Db::fetchAll("SELECT template_id FROM sr_templates WHERE project_id=".$project['project_id']));
            $projects[$key]['groups_count'] = $groups_count;
            $projects[$key]['templates_count'] = $templates_count;
        }

        return $projects;
    }

    public function checkScript ($site_url, $project_id) {

        $this->logger->info($site_url);

        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $site_url);

        $page = $res->getBody();

        $check_script = strstr($page, 'sr.service.js');
        if($check_script){
            Db::update('sr_projects', ['code_status'=>true],'project_id='.$project_id);
        }else{
            Db::update('sr_projects', ['code_status'=>false],'project_id='.$project_id);
        }

        header ('location: /');
        exit;
    }

    public function addNewProject ($post, $user_id) {

        //$user_id = Db::fetchAll("SELECT id FROM sr_users WHERE email='test@gmail.com'")[0]['id'];


        $check_name = $post['site_url'];
        $check_project = Db::fetchAll("SELECT project_id FROM sr_projects WHERE project_name="."'$check_name'");

        if ( !$check_project && strlen(strval($check_project))>0) {
            $projects_count = count(Db::fetchAll("SELECT project_id FROM sr_projects WHERE user_id =".$user_id));

            $data_fields = [
                'user_id'=> $user_id,
                'project_name'=> $check_name,
                'project_alias'=> 'Проект №'.($projects_count+1)
            ];
            Db::insert('sr_projects', $data_fields);
        }


        header ('location: /');
        exit;
    }

    public function editProjectName($post){
        Db::update('sr_projects', ['project_alias'=>$post['project_new_name']], 'project_id='.$post['project_id']);
        header ('location: /');
        exit;
    }

    public function removeProject($project_id) {
        Db::delete('sr_projects', 'project_id='.$project_id);
    }
}