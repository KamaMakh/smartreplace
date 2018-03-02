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


    function init(int $project_id=null) {
        if ( $project_id ) {

            $site_url = Db::fetchAll('SELECT project_name FROM sr_projects WHERE project_id=' . $project_id)[0]['project_name'];

            if ($site_url && strlen($site_url) > 0) {
                $result = $this->sendToClient(null, $site_url);
                $result['site_url'] = $site_url;
                return $result;
            }
        }
    }

    public function insertToDb($post) {

        $project_id = $post['project_id'];
        $check_template = Db::fetchAll("SELECT name FROM sr_templates WHERE project_id=".$project_id." and param ="."'".$post['wayToElement']."'");

        if ( !$check_template ) {
            $data_fields = [
                'project_id'=> $project_id,
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

    public function sendToClient ($project_id, $project_name = null) {
        if ( !$project_id ) {

            $project_id = Db::fetchAll("SELECT project_id FROM sr_projects WHERE project_name ="."'".$project_name."'");

            $project_id = $project_id[0]['project_id'];

            $elements = Db::fetchAll("SELECT * FROM sr_templates WHERE project_id =".$project_id);

            return [
                'elements'=>json_encode($elements),
                'firstCheck'=>1
            ];

        } else {

            $elements = Db::fetchAll("SELECT * FROM sr_templates WHERE project_id =".$project_id);

            if ( !$elements ) {
                $elements = null; //json_encode(['clear'=>true]);
            }

            return [
                'elements'=>json_encode($elements),
                'firstCheck'=>false
            ];
        }
    }

    public function removeElement ($get, $user_id) {
        $project_id = $get['project_id'];
        $template_id = $get['template_id'];
        $equal_user_id = Db::fetchAll("SELECT user_id FROM sr_projects WHERE project_id=".$project_id)[0]['user_id'];

        if ( $user_id == $equal_user_id ) {
            Db::delete('sr_templates', 'template_id='.$template_id);
            Db::delete('sr_replacements', 'template_id='.$template_id);

            $this->sendToClient($project_id);
            return true;
        } else {
            return false;
        }
    }
}