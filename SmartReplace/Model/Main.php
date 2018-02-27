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

    public function init($user_id) {

        $projects = Db::fetchAll("SELECT project_id,user_id,project_name,code_status,project_alias FROM sr_projects WHERE user_id=".$user_id);

        foreach($projects as $key=>$project){
            $groups_count =  count(Db::fetchAll("SELECT group_id FROM sr_groups WHERE project_id=".$project['project_id']));
            $templates_count = count(Db::fetchAll("SELECT template_id FROM sr_templates WHERE project_id=".$project['project_id']));
            $projects[$key]['groups_count'] = $groups_count;
            $projects[$key]['templates_count'] = $templates_count;

            if ( strstr($project['project_name'], '--') ) {
                $url_arr = explode('//', $project['project_name']);
                $url_arr[1] = explode('/',$url_arr[1]);

                if ( count($url_arr[1]) > 1 && strlen($url_arr[1]) ) {
                    foreach ( $url_arr[1] as $key2=>$item ) {
                        if ( strstr($item, '--') ) {
                            $url_arr[1][$key2] = idn_to_utf8($item);
                        }
                    }
                    $url_arr[1] = implode('/', $url_arr[1]);
                }
                else {
                    $url_arr[1] = idn_to_utf8($url_arr[1][0]);
                }

                $projects[$key]['real_project_name'] = $url_arr[0] . '//' . idn_to_utf8($url_arr[1]);
            }
        }

        return $projects;
    }

    public function checkScript ($site_url, $project_id) {

        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $site_url);

        $page = $res->getBody();

        $check_script = strstr($page, 'sr.service.js');
        if($check_script){
            Db::update('sr_projects', ['code_status'=>true],'project_id='.$project_id);
        }else{
            Db::update('sr_projects', ['code_status'=>false],'project_id='.$project_id);
        }

    }

    public function addNewProject ($post, $user_id) {

        $check_name = $post['site_url'];

        if ( count(explode('//', $check_name)) < 2 ) {
            $check_name = 'http://'.$check_name;
        }

        if ( !strstr($check_name, '--') ) {
            $arr = explode('//', $check_name);

            $arr[1] = explode('/',$arr[1]);

            if ( count($arr[1]) > 1 && strlen($arr[1][1]) ) {
                foreach ( $arr[1] as $key2=>$item ) {
                    if ( strstr($item, '--') ) {
                        $arr[1][$key2] = idn_to_utf8($item);
                    }
                }
                $arr[1] = array_filter($arr[1], function($item){
                    if ( strlen($item) ) {
                        return $item;
                    }
                });
                $arr[1] = implode('/', $arr[1]);
            }
            else {
                $arr[1] = idn_to_utf8($arr[1][0]);
            }
            $check_name = $arr[0] . '//' . idn_to_ascii($arr[1]);
        }

        if (substr($check_name, -1) == '/') {
            $check_name = substr($check_name, 0, -1);
        }

        $check_project = Db::fetchAll("SELECT project_id FROM sr_projects WHERE project_name="."'$check_name'");

        if ( !$check_project && strlen(strval($check_project))>0) {
            $projects_count = count(Db::fetchAll("SELECT project_id FROM sr_projects WHERE user_id =".$user_id));

            $data_fields = [
                'user_id'=> $user_id,
                'project_name'=> $check_name,
                'project_alias'=> 'Проект №'.($projects_count+1)
            ];
            $project_id = Db::insert('sr_projects', $data_fields);
            $this->checkScript($check_name, $project_id);
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