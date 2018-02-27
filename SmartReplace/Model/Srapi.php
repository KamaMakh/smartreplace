<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 20.02.18
 * Time: 18:57
 */

namespace SmartReplace\Model;

use MF\DB\MySQL as DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Srapi
{
    public $logger;
    public function getGroup($get_param) {

        $logger = new Logger('my_logger');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/my_app.log', $logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        $this->logger = $logger;

        $project_id = Db::fetchAll("SELECT project_id FROM sr_groups WHERE group_id =".$get_param['group_id'])[0]['project_id'];
        $elements = Db::fetchAll("SELECT selector,type,new_text FROM sr_replacements WHERE group_id=".$get_param['group_id']. " AND project_id=".$project_id);
        return $elements;

    }
}