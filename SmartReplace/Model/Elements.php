<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 19.02.18
 * Time: 16:22
 */

namespace SmartReplace\Model;

use MF\DB\MySQL as DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use MF\Request;

class Elements
{
    public $site_url;
    private $fenom;
    private $logger;

    public  function __construct()
    {
        $logger = new Logger('my_logger');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/my_app.log', $logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());

        $this->logger = $logger;
    }


    function init(int $project_id=null, int $page_id=null) {
        if ( $project_id && $page_id) {

            $site_url = Db::fetchAll('SELECT project_name FROM sr_projects WHERE project_id=' . $project_id)[0]['project_name'];
            $page_url = Db::fetchAll("SELECT page_name FROM sr_pages WHERE page_id=$page_id")[0]['page_name'];

            if ($site_url && strlen($site_url) > 0) {
                $result = $this->sendToClient($page_id, $site_url, true);
                $result['site_url'] = $site_url;
                $result['page_url'] = $page_url;
                return $result;
            }
        }
    }

    public function insertToDb($post) {

        $project_id = $post['project_id'];
        $page_id = $post['page_id'];
        $check_template = Db::fetchAll("SELECT name FROM sr_templates WHERE page_id=".$page_id." and param ="."'".$post['wayToElement']."'");

        if ( !$check_template ) {
            $data_fields = [
                'page_id'=> $page_id,
                'param'=> $post['wayToElement'],
                'type'=> $post['type'],
                'data'=> $post['inner'],
                'name'=> $post['name']
            ];
            Db::insert('sr_templates', $data_fields);
        }
        else if ( $check_template != $post['name'] ) {
            Db::update('sr_templates', ['name'=>$post["name"]], 'param='."'".$post['wayToElement']."'");
        }

    }

    public function sendToClient ($page_id, $project_name = null, $first_check = null) {
//        if ( !$page_id ) {
//
//            $query = "SELECT * FROM sr_pages p
//                        JOIN sr_projects pr
//                        ON p.project_id = pr.project_id
//                        WHERE project_name='$project_name'";
//
//            $page_id = Db::fetchAll($query)[0]['page_id'];
//
//            $query2 = "SELECT t.template_id, t.page_id, t.param, t.type, t.data, t.name, p.project_id
//                        FROM sr_templates t
//                        JOIN sr_pages p
//                        ON t.page_id=p.page_id
//                        WHERE t.page_id =".$page_id;
//
//            $elements = Db::fetchAll($query2);
//
//            return [
//                'elements'=>json_encode($elements),
//                'firstCheck'=>1
//            ];
//
//        } else {
        if ($page_id) {

            $elements = Db::fetchAll("SELECT * FROM sr_templates WHERE page_id =".$page_id);

            if ( !$elements ) {
                $elements = null; //json_encode(['clear'=>true]);
            }

            return [
                'elements'=>json_encode($elements),
                'firstCheck'=> $first_check ? true : false
            ];
        }
    }

    public function removeElement ($get, $user_id) {
        $project_id = $get['project_id'];
        $page_id = $get['page_id'];
        $template_id = $get['template_id'];
        $equal_user_id = Db::fetchAll("SELECT user_id FROM sr_projects WHERE project_id=".$project_id)[0]['user_id'];

        if ( $user_id == $equal_user_id ) {
            Db::delete('sr_templates', 'template_id='.$template_id);
            Db::delete('sr_replacements', 'template_id='.$template_id);

            $this->sendToClient($page_id);
            return true;
        } else {
            return false;
        }
    }
}