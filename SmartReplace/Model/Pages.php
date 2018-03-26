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

class Pages
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

    public function init($project_id) {

        $pages = Db::fetchAll("SELECT page_id,project_id,page_name, code_status FROM sr_pages WHERE project_id=".$project_id);

        foreach($pages as $key=>$page){
            $groups_count =  count(Db::fetchAll("SELECT group_id FROM sr_groups WHERE page_id=".$page['page_id']));
            $templates_count = count(Db::fetchAll("SELECT template_id FROM sr_templates WHERE page_id=".$page['page_id']));
            $pages_count = count(Db::fetchAll("SELECT page_id FROM sr_pages WHERE page_id=".$page['page_id']));
            $pages[$key]['groups_count'] = $groups_count;
            $pages[$key]['templates_count'] = $templates_count;
            $pages[$key]['pages_count'] = $pages_count;

            if ( strstr($page['page_name'], '--') ) {
                $url_arr = explode('//', $page['page_name']);
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

                $pages[$key]['real_page_name'] = $url_arr[0] . '//' . idn_to_utf8($url_arr[1]);
            }
        }

        return $pages;
    }

    public function checkScript ($page_url, $site_url, $page_id) {
        $client  =  new GuzzleHttp\Client();
        error_log(var_export($site_url.$page_url, 1));
        $res = $client->request('GET', $site_url.$page_url.'?sr=001');
        $page = $res->getBody();

        $check_script = strstr($page, 'sr.service.js');
        if($check_script){
            Db::update('sr_pages', ['code_status'=>true],'page_id='.$page_id);
        }else{
            Db::update('sr_pages', ['code_status'=>false],'page_id='.$page_id);
        }

    }

    public function addNewPage ($post, $project_id) {

        $check_name = $post['site_url'];
        $check_page_name = $post['page_url'];

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

        if (strrpos($check_page_name, '/') !== 0) {
            $check_page_name = '/'.$check_page_name;
        }

        $check_project = Db::fetchAll("SELECT page_id FROM sr_pages WHERE page_name="."'$check_page_name'");

        if ( !$check_project && strlen(strval($check_project))>0 && $check_page_name) {
            $pages_count = count(Db::fetchAll("SELECT page_id FROM sr_pages WHERE project_id =".$project_id));

            $data_fields = [
                'project_id'=> $project_id,
                'page_name'=> $check_page_name,
            ];
            $page_id = Db::insert('sr_pages', $data_fields);
            $this->checkScript($check_page_name, $check_name, $page_id);
        }


        header ("location: /pages?project_id=".$project_id."&site_url=$check_name");
        exit;
    }

    public function editPageName($post){
        Db::update('sr_pages', ['project_alias'=>$post['project_new_name']], 'page_id='.$post['page_id']);
        header ('location: /');
        exit;
    }

    public function removePage($page_id, $project_id) {
        Db::delete('sr_pages', 'page_id='.$page_id. ' AND project_id='.$project_id);
    }
}