<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 23.01.18
 * Time: 15:22
 */

namespace Megagroup\SmartReplace;


class Srapi
{
    public $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }


    public function getGroup($get_param) {

        $elemetns = Db::select("SELECT data FROM sr_replacements WHERE group_id=".$get_param['group_id']. " AND project_id=".$get_param['project_id']);
        //$this->logger->info("SELECT elements FROM sr_groups WHERE group_id=".$get_param['group_id']. " AND project_id=".$get_param['project_id']);
        $this->logger->addWarning('elements', $elemetns);
        //setcookie('elements', json_encode($elemetns), + 60*60*24*30, '/', $get_param['project_name']);

        return $elemetns;
    }

}