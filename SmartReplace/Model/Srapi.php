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

        $data = [];
        $page_data = Db::fetchAll("SELECT page_id FROM sr_groups WHERE group_id =".$get_param['group_id']);

        if ($page_data) {
            $page_id = $page_data[0]['page_id'];
            $elements = Db::fetchAll("SELECT selector,type,new_text FROM sr_replacements WHERE group_id=".$get_param['group_id']. " AND page_id=".$page_id);
            $page_data = Db::fetchAll("SELECT * FROM sr_pages WHERE page_id=".$page_id);

            $data = [
                'elements' => $elements,
                'page_url' => $page_data[0]['page_name']
            ];
        }

        return $data;

    }
}