<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 30.10.17
 * Time: 18:19
 */

namespace Megagroup\SmartReplace;

use Megagroup\SmartReplace\Controllers;
use GuzzleHttp;

class AddElements
{
    public $site_url;
    private $fenom;
    private $logger;
    private $user_id;
    private $project_id;
    private $template_id;
    private $email;
    private $project_name;

    public  function __construct(\Fenom $fenom, $logger)
    {
        $this->fenom = $fenom;
        $this->logger = $logger;
    }


    function init(string $site_url) {
        $this->site_url = $site_url;
        $this->sendToClient(null, $this->site_url);
        $this->fenom->display('addelements.tpl');
    }

    public function getcontent(string $site_url) {
        $this->site_url = $site_url;

        $url = explode('/', $this->site_url);

        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $this->site_url);

        $page = $res->getBody();


        $new_url = $url[0] . '//' . $url[2];
        $this->project_name = $new_url;
        $_SESSION['user']['project_name'] = $this->project_name;

        $page = str_replace('href="', 'href="'.$new_url.'/', $page);
        $page = str_replace('href=\'', 'href=\''.$new_url.'/', $page);
        $page = str_replace('href="'.$new_url.'/'.$new_url, 'href="'.$new_url, $page);

        $page = str_replace('src="', 'src="'.$new_url.'/', $page);
        $page = str_replace('src=\'', 'src=\''.$new_url.'/', $page);
        $page = str_replace('src="'.$new_url.'/'.$new_url, 'src="'.$new_url, $page);



        $page = $page . '<script src="/js/site.js"></script>'
            .'<link rel="stylesheet" href="/css/site.css">'
            .'<div class="preload"></div>';

        echo $page;
    }

    public function insertToDb(string $method,string $mode) {
        if ( $mode == 'add') {
            if ( $_SESSION['user'] ) {
                //$this->logger->addWarning('session222', $_SESSION);
                $this->email = $_SESSION['user']['email'];
                $this->user_id = Db::select("SELECT id FROM sr_users WHERE email='$this->email'");
                $this->project_name = $_SESSION['user']['project_name'];
                $this->fenom->assign('project_name', $this->project_name);
                $check_project = Db::select("SELECT * FROM sr_projects WHERE project_name='$this->project_name' ");

                if ( !$check_project ) {
                    $data_fields = [
                        'user_id'=> [$this->user_id[0]['id'], 'i'],
                        'project_name'=> [$this->project_name, 's']
                    ];

                    Db::insert($data_fields, 'sr_projects');
                    $check_project = Db::select("SELECT * FROM sr_projects WHERE project_name='$this->project_name' ");
                }

                $check_template = Db::select("SELECT * FROM sr_templates WHERE project_id=".$check_project[0]['project_id']." and param ="."'".$_POST['wayToElement']."'");

                if ( !$check_template ) {
                    $data_fields = [
                        'project_id'=> [$check_project[0]['project_id'], 'i'],
                        'param'=> [$_POST['wayToElement'], 's'],
                        'type'=> [$_POST['type'], 's'],
                        'data'=> [$_POST['inner'], 's'],
                        'name'=> [$_POST['name'], 's']
                    ];
                    $this->logger->addWarning('data-fields', $data_fields);
                    Db::insert($data_fields, 'sr_templates');
                }
                return $check_project[0]['project_id'];
                $this->sendToClient($check_project[0]['project_id']);

            }

        }
    }
    public function sendToClient ($project_id, $project_name = null) {
        if ( !$project_id ) {

            $project_id = Db::select("SELECT project_id FROM sr_projects WHERE project_name ="."'".$project_name."'");
            $project_id = $project_id[0]['project_id'];
            $this->logger->info("SELECT project_id FROM sr_projects WHERE project_name ="."'".$project_name."'");

            $elements = Db::select("SELECT * FROM sr_templates WHERE project_id =".$project_id);

            $this->fenom->assign('dataFields', json_encode($elements));
            $this->fenom->assign('firstCheck', true);

        } else {

            $elements = Db::select("SELECT * FROM sr_templates WHERE project_id =".$project_id);

            $this->fenom->assign('firstCheck', false);
            if ( $elements ) {
                echo json_encode($elements);
            }
        }
    }

    public function complete() {
        $project_id = Db::select("SELECT project_id FROM sr_projects WHERE project_name=". "'" .$_SESSION['user']['project_name']."'");
        $project_id = $project_id[0]['project_id'];
        $list = Db::select("SELECT * FROM sr_templates WHERE project_id = $project_id");
        $eq_goup = Db::select("SELECT * FROM sr_groups WHERE project_id=$project_id");

        $this->fenom->assign('elements',$eq_goup[0]['elements']);

        if ( !$eq_goup ) {
            Db::insert([
                'project_id' => [$project_id, 'i'],
                'elements' => [ json_encode($list), 's']
            ], 'sr_groups');
        } else if ( (count(json_decode($eq_goup[0]['elements'])) != count($list)) ) {
            Db::update(['elements'=>json_encode($list)], 'sr_groups', "project_id= $project_id");
        }

        $eq_goup = Db::select("SELECT * FROM sr_groups WHERE project_id=$project_id");

        $this->fenom->assign('groups', $eq_goup);
        $this->fenom->assign('project_name', $_SESSION['user']['project_name']);
        $this->fenom->assign('list', $list);
        $this->fenom->assign('project_id', $project_id);
        $this->fenom->display('complete.tpl');
    }

    public function reset ($project_name) {

        $project_id = Db::select("SELECT project_id FROM sr_projects WHERE project_name ="."'".$project_name."'");

        $project_id = $project_id[0]['project_id'];

        $result = Db::delete('sr_templates', 'project_id='.'"'.$project_id.'"');

        if ( $result ) {
            return $project_id;
        }
    }
    public function insertGroup($groups) {

        foreach ( $groups as $key=>$group ) {
           if ( is_array($group)  ) {

               $get_param = Db::select("SELECT data FROM sr_replacements WHERE project_id=".$groups['project_id'] . " AND get_param='".$group['keyword']."'");

                if (  !$get_param ) {

                    $groups_params = [
                        'group_id'=>[$group['group_id'], 'i'],
                        'get_param'=>[$group['keyword'], 's'],
                        'channel_name'=>[$group['name'], 's'],
                        'data'=>[json_encode($group['replacements']), 's'],
                        'project_id'=>[$groups['project_id'], 'i']
                    ];
                    Db::insert($groups_params,'sr_replacements');

                } else if ( $get_param != json_encode($group['replacements']) ) {

                    Db::update(['data'=>json_encode($group['replacements']),'channel_name'=>$group['name']], 'sr_replacements', "project_id=".$groups['project_id']. " AND group_id=". $group['group_id']);

                }
            }
        }
    }

    public function getScript() {
        $this->fenom->display('getscript.tpl');
    }

    public function addNewGroup ($new_group) {
        $new_group_params = [
            'project_id'=>[$new_group['project_id'], 'i'],
            'elements'=>[json_encode($new_group['elements']),'s']
        ];

        Db::insert($new_group_params, 'sr_groups');

        $backToClient = Db::select('SELECT * FROM sr_groups WHERE group_id>'.$new_group['last_id'].' LIMIT 1');
        $this->logger->addWarning('new_group', $backToClient);
        return json_encode($backToClient[0]);
    }

    public function removeGroup ($group_id) {
        Db::delete('sr_groups', 'group_id='.$group_id);
    }
}