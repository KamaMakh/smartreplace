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
        $res = $client->request('GET', $get['site_url'].'sr=001');
        $page = $res->getBody();
        $check_script = strstr($page, 'sr.service.js');

        if($check_script){
            Db::update('sr_pages', ['code_status'=>true],'page_id='.$get['page_id']);
            $result = true;
        }else{
            Db::update('sr_pages', ['code_status'=>false],'page_id='.$get['page_id']);
            $result = false;
        }
        return $result;
    }
}