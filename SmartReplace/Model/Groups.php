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

    public function complete(int $project_id, int $user_id, int $page_id) {

        $list = Db::fetchAll("SELECT * FROM sr_templates WHERE page_id = $page_id");
        $eq_group = Db::fetchAll("SELECT group_id FROM sr_groups WHERE page_id=$page_id");
        $project_name = Db::fetchAll('SELECT project_name FROM sr_projects WHERE project_id='.$project_id)[0]['project_name'];
        $page_name = Db::fetchAll('SELECT page_name FROM sr_pages WHERE page_id='.$page_id)[0]['page_name'];

        if ( !$eq_group ) {
            $insertId = Db::insert('sr_groups',[
                'page_id' => $page_id,
                'elements' => json_encode($list)
            ]);

            foreach ( $list as $element ) {
                $check_group = Db::fetchAll("SELECT group_id FROM sr_replacements WHERE template_id=".$element['template_id']);

                if ( !$check_group ) {

                    Db::insert('sr_replacements',[
                        'page_id' => $element['page_id'],
                        'group_id'   =>$insertId,
                        'template_id'=>$element['template_id'],
                        'type'       =>$element['type'],
                        'selector'   =>$element['param'],
                        'old_text'   =>$element['data']
                    ]);

                }
            }

        } else {

            foreach ( $list as $element ) {
                $check_group = Db::fetchAll("SELECT group_id FROM sr_replacements WHERE template_id=".$element['template_id']);
                if ( !$check_group ) {
                    foreach ($eq_group as $group_id) {
                        Db::insert('sr_replacements' ,[
                            'page_id' => $element['page_id'],
                            'group_id'   =>$group_id["group_id"],
                            'template_id'=>$element['template_id'],
                            'type'       =>$element['type'],
                            'selector'   =>$element['param'],
                            'old_text'   =>$element['data']
                        ]);
                    }
                }
            }
        }

        $groups = Db::fetchAll("SELECT * FROM sr_groups WHERE page_id=$page_id");
        $elements = Db::fetchAll("SELECT * FROM sr_replacements WHERE page_id=$page_id");
        $project = Db::fetchAll("SELECT project_id,user_id,project_name FROM sr_projects WHERE project_id=".$project_id);
        $page = Db::fetchAll("SELECT page_id,page_name, code_status FROM sr_pages WHERE page_id=".$page_id);

        return [
            'project'     =>$project,
            'page'        =>$page,
            'groups'      =>$groups,
            'elements'    =>$elements,
            'project_name'=>$project_name,
            'page_name'   =>$page_name,
            'list'        =>$list,
            'project_id'  =>$project_id,
            'page_id'     =>$page_id
        ];
    }

    public function removeGroup ($group_id, $project_id, $user_id) {
        $equal_user_id = Db::fetchAll("SELECT user_id FROM sr_projects WHERE project_id=".$project_id)[0]['user_id'];

        if ( $equal_user_id == $user_id ) {
            Db::delete('sr_replacements', 'group_id='.$group_id);
            Db::delete('sr_groups', 'group_id='.$group_id);
        }

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

        //$this->checkScript($post['project_name'], $post['project_id']);
        header ('location: /complete?project_id='.$post['project_id'].'&page_id='.$post['page_id']);
        exit;
    }

    public function addNewGroup ($new_group) {
        $ids=[];
        $this->logger->info('got it');

        $new_group_params = [
            'page_id'=>$new_group['page_id'],
            'elements'  =>json_encode($new_group['elements'])
        ];

        $last_group_id = Db::insert('sr_groups', $new_group_params);
        $this->logger->addWarning($last_group_id);

        foreach ( $new_group['elements'] as $key=>$elements ) {
            $new_replacement_params = [
                'page_id'    =>$new_group['page_id'],
                'group_id'   =>$last_group_id,
                'template_id'=>$elements['template_id'],
                'type'       =>$elements['type'],
                'selector'   =>$elements['param'],
                'old_text'   =>$elements['old_text']
            ];
            $ids[$key] = Db::insert('sr_replacements', $new_replacement_params);
        }

        $backToClient = Db::fetchAll('SELECT page_id,group_id FROM sr_groups WHERE group_id='.$last_group_id);

        $backToClient[0]['ids'] = $ids;
        return json_encode($backToClient[0]);
    }
    public function checkScript ($site_url, $project_id, $page_id, $page_url) {

        $client  =  new GuzzleHttp\Client();
        $res = $client->request('GET', $site_url.$page_url.'sr=001');

        $page = $res->getBody();

        $check_script = strstr($page, 'sr.service.js');
        if($check_script){
            Db::update('sr_pages', ['code_status'=>true],'page_id='.$page_id);
        }else{
            Db::update('sr_pages', ['code_status'=>false],'page_id='.$page_id);
        }

        header ('location: /complete?project_id='.$project_id.'&page_id='.$page_id);
        exit;
    }


}