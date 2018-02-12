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
        $this->site_url = explode('//',$site_url)[1];
        $this->sendToClient(null, $this->site_url);
        $this->fenom->display('addelements.tpl');
    }

    public function getcontent(string $site_url) {
        $this->site_url = $site_url;
        //$this->logger->info($this->site_url);
        $url = explode('/', $this->site_url);

        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $this->site_url);

        $page = $res->getBody();


        $new_url = $url[0] . '//' . $url[2];
        $this->project_name = $new_url;
        //$this->logger->info($new_url.'55');
   //     $_SESSION['user']['project_name'] = $this->project_name;

//        $page = str_replace('href="', 'href="'.$new_url.'/', $page);
//        $page = str_replace('href=\'', 'href=\''.$new_url.'/', $page);
//        $page = str_replace('href="'.$new_url.'/'.$new_url, 'href="'.$new_url, $page);

        $page = str_replace('url(', 'url('.$new_url.'/', $page);

        $page = str_replace('src="', 'src="'.$new_url.'/', $page);
        $page = str_replace('src=\'', 'src=\''.$new_url.'/', $page);
        $page = str_replace('src="'.$new_url.'/'.$new_url, 'src="'.$new_url, $page);

        $check_script = strstr($page, 'sr.service.js');
        if($check_script){
            $this->logger->info(explode('//',$this->site_url)[1]);
            Db::update(['code_status'=>true],'sr_projects','project_name='."'".explode('//',$this->site_url)[1]."'");
        }else{
            //$this->logger->info(9999);
        }

        $page = $page .'<link rel="stylesheet" href="/css/site.css">'
            .'<div class="preload"></div>'
            .'<script src="/js/site.js"></script>';

        echo $page;
    }

    public function insertToDb(string $mode) {
        if ( $mode == 'add') {
            if ( $_SESSION['user'] ) {
               // $this->logger->addWarning('post222', $_POST);
                $this->email = $_SESSION['user']['email'];
                $this->user_id = Db::select("SELECT id FROM sr_users WHERE email='$this->email'");
                $this->project_name = $_POST['project_name'];
                //$this->fenom->assign('project_name', $this->project_name);
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
                    //$this->logger->addWarning('data-fields', $data_fields);
                    Db::insert($data_fields, 'sr_templates');
                }
                return $check_project[0]['project_id'];
                //$this->sendToClient($check_project[0]['project_id']);

            }

        }
    }
    public function sendToClient ($project_id, $project_name = null) {
        if ( !$project_id ) {

            $project_id = Db::select("SELECT project_id FROM sr_projects WHERE project_name ="."'".$project_name."'");
            $project_id = $project_id[0]['project_id'];
           // $this->logger->info("SELECT project_id FROM sr_projects WHERE project_name ="."'".$project_name."'");

            $elements = Db::select("SELECT * FROM sr_templates WHERE project_id =".$project_id);

            $this->fenom->assign('dataFields', json_encode($elements));
            $this->fenom->assign('firstCheck', true);

        } else {

            $elements = Db::select("SELECT * FROM sr_templates WHERE project_id =".$project_id);

            $this->logger->addWarning('sendto', $elements);

            $this->fenom->assign('firstCheck', false);
            if ( $elements ) {
                echo json_encode($elements);
            }
        }
    }

    public function complete(string $project_name) {
        //$this->logger->info($project_name.'99999');
        $project_id = Db::select("SELECT project_id FROM sr_projects WHERE project_name=". "'" .$project_name."'");
        $project_id = $project_id[0]['project_id'];
        $list = Db::select("SELECT name,template_id,project_id,type,param,new_text FROM sr_templates WHERE project_id = $project_id");
        $eq_goup = Db::select("SELECT group_id FROM sr_groups WHERE project_id=$project_id");



        //$this->fenom->assign('elements',$eq_goup[0]['elements']);

        //$this->logger->addWarning('pre_groups', $list);

        if ( !$eq_goup ) {
            $insertId = Db::insert([
                'project_id' => [$project_id, 'i'],
                'elements' => [ json_encode($list), 's']
            ], 'sr_groups', null, true);

            foreach ( $list as $element ) {
                $check_group = Db::select("SELECT group_id FROM sr_replacements WHERE template_id=".$element['template_id']);

                if ( !$check_group ) {

                    Db::insert([
                        'project_id'=> [$element['project_id'], 'i'],
                        'group_id'=>[$insertId, 'i'],
                        'template_id'=>[$element['template_id'], 'i'],
                        'type'=>[$element['type'], 's'],
                        'selector'=>[$element['param'], 's']
                    ], 'sr_replacements');

                }
            }

        } else {

            foreach ( $list as $element ) {
                $check_group = Db::select("SELECT group_id FROM sr_replacements WHERE template_id=".$element['template_id']);
                if ( !$check_group ) {
                    foreach ($eq_goup as $group_id) {
                        Db::insert([
                            'project_id'=> [$element['project_id'], 'i'],
                            'group_id'=>[$group_id["group_id"], 'i'],
                            'template_id'=>[$element['template_id'], 'i'],
                            'type'=>[$element['type'], 's'],
                            'selector'=>[$element['param'], 's']
                        ], 'sr_replacements');
                    }

                }
            }
        }




        $groups = Db::select("SELECT * FROM sr_groups WHERE project_id=$project_id");
        $old_groups = Db::select("SELECT * FROM sr_replacements WHERE project_id=$project_id");
        $projects = Db::select("SELECT project_id,user_id,project_name FROM sr_projects JOIN sr_users WHERE email="."'".$_SESSION['user']['email']."'");

        //$this->logger->addWarning('groups', $eq_goup);
        $this->fenom->assign('projects', $projects);
        $this->fenom->assign('groups', $groups);
        $this->fenom->assign('old_groups', $old_groups);
        $this->fenom->assign('project_name', $project_name);
        $this->fenom->assign('list', $list);
        $this->fenom->assign('project_id', $project_id);
        $this->fenom->display('complete.tpl');
    }

    public function reset ($project_name) {

        $project_id = Db::select("SELECT project_id FROM sr_projects WHERE project_name ="."'".$project_name."'");

        $project_id = $project_id[0]['project_id'];
        //$this->logger->info($project_id);
        $result = Db::delete('sr_templates', 'project_id='.'"'.$project_id.'"');
        Db::delete('sr_groups', 'project_id='.'"'.$project_id.'"');
        Db::delete('sr_replacements', 'project_id='.'"'.$project_id.'"');

        if ( $result ) {
            return $project_id;
        }
    }
    public function insertGroup($groups) {

        //$this->logger->addWarning('groups', $groups);

        foreach ( $groups as $key=>$group ) {

           if ( is_array($group)  ) {
               // $this->logger->addWarning('group', $group);
               $group_id = Db::select("SELECT group_id FROM sr_groups WHERE project_id=".$groups['project_id'] . " AND group_id='".$group['group_id']."'");
               //$this->logger->addWarning($get_param);
                if (  !$group_id ) {

                    $groups_params = [
                        'project_id'=>[$groups['project_id'], 'i'],
                        'channel_name'=>[$group['channel_name'], 's'],
                        'elements'=>[$group['replacements'], 's']
                    ];
                    $groupId = Db::insert($groups_params,'sr_groups');

                    foreach (json_decode($group['replacements']) as $element) {

                        $element_params = [
                            'project_id'=>[$element->project_id, 'i'],
                            'group_id'=>[$groupId, 'i'],
                            'template_id'=>$element->template_id,
                            'type'=>$element->type,
                            'selector'=>$element->selector,
                            'new_text'=>$element->new_text
                        ];
                        Db::insert($element_params, 'sr_replacements');
                    }

                    //$this->logger->info('---'.$groupId);

                } else {

                   // $this->logger->info($group['ne ravno']);
                    foreach (json_decode($group['replacements']) as $element) {

                        $check_group = Db::select("SELECT new_text FROM sr_replacements WHERE replace_id=".$element['replace_id']);

                        if (!$check_group) {
                            $element_params = [
                                'project_id'=>$element->project_id,
                                'group_id'=>$element->group_id,
                                'template_id'=>$element->template_id,
                                'type'=>$element->type,
                                'selector'=>$element->selector,
                                'new_text'=>$element->new_text
                            ];
                            Db::insert($element_params, 'sr_replacements');
                        }
                        else if ($check_group != $element['new_text']) {
                            Db::update(['new_text'=>$element['new_text']], 'sr_replacements', 'replace_id='.$element['replace_id']);
                        }

                    }

                    Db::update(['elements'=>$group['replacements'],'channel_name'=>$group['channel_name']], 'sr_groups', "project_id=".$groups['project_id']. " AND group_id=". $group['group_id']);

                }
            }
        }
    }

    public function getScript() {
        $this->fenom->display('getscript.tpl');
    }

    public function addNewGroup ($new_group) {
        $ids=[];
        $new_group_params = [
            'project_id'=>[$new_group['project_id'], 'i'],
            'elements'=>[json_encode($new_group['elements']),'s']
        ];
        $last_group_id = Db::insert($new_group_params, 'sr_groups', null, true);

        foreach ( $new_group['elements'] as $key=>$elements ) {
            $new_replacement_params = [
                'project_id'=>[$new_group['project_id'], 'i'],
                'group_id'=>[$last_group_id, 'i'],
                'template_id'=>[$elements['template_id'], 'i'],
                'type'=>[$elements['type'], 's'],
                'selector'=>[$elements['param'], 's']
            ];
            $ids[$key] = Db::insert($new_replacement_params, 'sr_replacements', null, true);
        }

        $backToClient = Db::select('SELECT project_id,group_id FROM sr_groups WHERE group_id='.$last_group_id);
        //$backToClient2 = Db::select('SELECT project_id,group_id FROM sr_groups, sr_replacements WHERE group_id='.$last_group_id);
        $backToClient[0]['ids'] = $ids;
        $this->logger->addWarning('join', $backToClient[0]);

        return json_encode($backToClient[0]);
    }

    public function removeGroup ($group_id) {
        Db::delete('sr_replacements', 'group_id='.$group_id);
        Db::delete('sr_groups', 'group_id='.$group_id);
    }

    public function saveGroup () {
        //$this->logger->addWarning('save', $_POST);

        $old_val_group = Db::select("SELECT elements, channel_name FROM sr_groups WHERE group_id=".$_POST['group_id']);
        $old_val_replacements = Db::select("SELECT replace_id,template_id,new_text FROM sr_replacements WHERE group_id =".$_POST['group_id']);

        $i=1;
        $y=1;

        if ($old_val_group) {
            $elements = json_decode($old_val_group[0]['elements']);
            foreach ($elements as $key=>$val) {
                if ($val->new_text != $_POST['element-'.$i]) {
                    $val->new_text = $_POST['element-'.$i];
                   // $this->logger->info('111');
                }
                else {
                   // $this->logger->info('222');
                }

                $i++;

            }
            $old_val_group[0]['elements'] = json_encode($elements, JSON_UNESCAPED_UNICODE);

            if ( $old_val_group[0]['channel_name'] != $_POST['channel_name'] ) {
                $old_val_group[0]['channel_name'] = $_POST['channel_name'];
            }
            Db::update(['channel_name'=>$old_val_group[0]['channel_name'], 'elements'=>$old_val_group[0]['elements']], 'sr_groups', 'group_id='.$_POST['group_id']);
        }

        for ($s=0; $s<$_POST['elements_count']; $s++) {
            $old_text = Db::select("SELECT new_text FROM sr_replacements WHERE group_id=".$_POST['group_id']." AND replace_id=".$_POST['replace-id-'.($s+1)]);

            //$this->logger->info($old_text['new_text']);
           // $this->logger->info($_POST['element-'.($s+1)]);

            if ( $old_text['new_text'] != $_POST['element-'.($s+1)] ) {
                Db::update(['new_text'=>$_POST['element-'.($s+1)]], 'sr_replacements', 'group_id='.$_POST['group_id'].' AND replace_id='.$_POST['replace-id-'.($s+1)]);
            }
        }


        //$old_val

        $this->complete($_POST['project_name']);
    }
    public function removeProject(){
        $project_id = $_GET['project_id'];
       // $this->logger->addWarning('get',$_GET);
        Db::delete('sr_projects', 'project_id='.$project_id);
    }
    public function removeElement () {
        //$this->logger->addWarning('gget', $_GET);
        $project_id = $_GET['project_id'];
        $template_id = $_GET['template_id'];

        Db::delete('sr_templates', 'template_id='.$template_id);

        $this->sendToClient($project_id);
    }
}