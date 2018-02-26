<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 22.02.18
 * Time: 16:35
 */

namespace SmartReplace\Model;

use GuzzleHttp;
use MF\DB\MySQL as DB;

class Code
{
    public function init($get) {

    }

    public function checkScript ($get) {
        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $get['site_url']);

        $page = $res->getBody();

        $check_script = strstr($page, 'sr.service.js');
        $check_link   = strstr($page, 'site.css');
        if($check_script && $check_link){
            Db::update('sr_projects', ['code_status'=>true],'project_id='.$get['project_id']);
            $result = true;
        }else{
            Db::update('sr_projects', ['code_status'=>false],'project_id='.$get['project_id']);
            $result = false;
        }

        return $result;

    }
}