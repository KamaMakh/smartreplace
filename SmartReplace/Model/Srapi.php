<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 20.02.18
 * Time: 18:57
 */

namespace SmartReplace\Model;

use MF\DB\MySQL as DB;

class Srapi
{
    public function getGroup($get_param) {

        //if($get_param['check_script']){
         //   Db::update('sr_projects', ['code_status'=>true],'project_id='.$get_param['project_id']);
        //}

        $elements = Db::fetchAll("SELECT selector,type,new_text FROM sr_replacements WHERE group_id=".$get_param['group_id']. " AND project_id=".$get_param['project_id']);


        return $elements;
    }
}