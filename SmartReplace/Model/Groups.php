<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 20.02.18
 * Time: 12:51
 */

namespace SmartReplace\Model;

use MF\DB\MySQL as DB;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use MF\Request;
use GuzzleHttp;

class Groups
{
    public  function __construct()
    {
        $logger = new Logger('my_logger');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/my_app.log', $logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());

        $this->logger = $logger;
    }

    public function complete(int $project_id, int $user_id) {

        $list = Db::fetchAll("SELECT name,template_id,project_id,type,param FROM sr_templates WHERE project_id = $project_id");
        $eq_group = Db::fetchAll("SELECT group_id FROM sr_groups WHERE project_id=$project_id");
        $project_name = Db::fetchAll('SELECT project_name FROM sr_projects WHERE project_id='.$project_id)[0]['project_name'];

        if ( !$eq_group ) {
            $insertId = Db::insert('sr_groups',[
                'project_id' => $project_id,
                'elements' => json_encode($list)
            ]);

            foreach ( $list as $element ) {
                $check_group = Db::fetchAll("SELECT group_id FROM sr_replacements WHERE template_id=".$element['template_id']);

                if ( !$check_group ) {

                    Db::insert('sr_replacements',[
                        'project_id'=> $element['project_id'],
                        'group_id'=>$insertId,
                        'template_id'=>$element['template_id'],
                        'type'=>$element['type'],
                        'selector'=>$element['param']
                    ]);

                }
            }

        } else {

            foreach ( $list as $element ) {
                $check_group = Db::fetchAll("SELECT group_id FROM sr_replacements WHERE template_id=".$element['template_id']);
                if ( !$check_group ) {
                    foreach ($eq_group as $group_id) {
                        Db::insert('sr_replacements' ,[
                            'project_id'=> $element['project_id'],
                            'group_id'=>$group_id["group_id"],
                            'template_id'=>$element['template_id'],
                            'type'=>$element['type'],
                            'selector'=>$element['param']
                        ]);
                    }
                }
            }
        }

        $groups = Db::fetchAll("SELECT * FROM sr_groups WHERE project_id=$project_id");
        $elements = Db::fetchAll("SELECT * FROM sr_replacements WHERE project_id=$project_id");
        $project = Db::fetchAll("SELECT project_id,user_id,project_name,code_status FROM sr_projects WHERE project_id=".$project_id);

        return [
            'project'=>$project,
            'groups'=>$groups,
            'elements'=>$elements,
            'project_name'=>$project_name,
            'list'=>$list,
            'project_id'=>$project_id
        ];
    }

    public function removeGroup ($group_id) {
        Db::delete('sr_replacements', 'group_id='.$group_id);
        Db::delete('sr_groups', 'group_id='.$group_id);
    }

    public function saveGroup ($post) {

        $old_val_group = Db::fetchAll("SELECT elements, channel_name FROM sr_groups WHERE group_id=".$post['group_id']);
        $i=1;

        if ($old_val_group) {
            $elements = json_decode($old_val_group[0]['elements']);
            foreach ($elements as $key=>$val) {
                if ($val->new_text != $post['element-'.$i]) {
                    $val->new_text = $post['element-'.$i];
                }
                $i++;
            }
            $old_val_group[0]['elements'] = json_encode($elements, JSON_UNESCAPED_UNICODE);

            if ( $old_val_group[0]['channel_name'] != $post['channel_name'] ) {
                $old_val_group[0]['channel_name'] = $post['channel_name'];
            }
            Db::update('sr_groups', ['channel_name'=>$old_val_group[0]['channel_name'], 'elements'=>$old_val_group[0]['elements']],  'group_id='.$post['group_id']);
        }

        for ($s=0; $s<$post['elements_count']; $s++) {
            $old_text = Db::fetchAll("SELECT new_text FROM sr_replacements WHERE group_id=".$post['group_id']." AND replace_id=".$post['replace-id-'.($s+1)]);

            if ( $old_text['new_text'] != $post['element-'.($s+1)] ) {
                Db::update('sr_replacements', ['new_text'=>$post['element-'.($s+1)]], 'group_id='.$post['group_id'].' AND replace_id='.$post['replace-id-'.($s+1)]);
            }
        }

        $this->checkScript($post['project_name'], $post['project_id']);
    }

    public function addNewGroup ($new_group) {
        $ids=[];
        $this->logger->info('got it');

        $new_group_params = [
            'project_id'=>$new_group['project_id'],
            'elements'=>json_encode($new_group['elements'])
        ];

        $last_group_id = Db::insert('sr_groups', $new_group_params);
        $this->logger->addWarning($last_group_id);

        foreach ( $new_group['elements'] as $key=>$elements ) {
            $new_replacement_params = [
                'project_id'=>$new_group['project_id'],
                'group_id'=>$last_group_id,
                'template_id'=>$elements['template_id'],
                'type'=>$elements['type'],
                'selector'=>$elements['param']
            ];
            $ids[$key] = Db::insert('sr_replacements', $new_replacement_params);
        }

        $backToClient = Db::fetchAll('SELECT project_id,group_id FROM sr_groups WHERE group_id='.$last_group_id);

        $backToClient[0]['ids'] = $ids;
        return json_encode($backToClient[0]);
    }
    public function checkScript ($site_url, $project_id) {

        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $site_url);

        $page = $res->getBody();

        $check_script = strstr($page, 'sr.service.js');
        if($check_script){
            Db::update('sr_projects', ['code_status'=>true],'project_id='.$project_id);
        }else{
            Db::update('sr_projects', ['code_status'=>false],'project_id='.$project_id);
        }

        header ('location: /complete?project_id='.$project_id);
        exit;
    }


}