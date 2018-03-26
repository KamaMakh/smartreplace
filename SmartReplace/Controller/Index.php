<?php

namespace SmartReplace\Controller;

use MF\Request;
use SmartReplace\SmartReplace;
use Viron\ActionBase;
use SmartReplace\Model;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class Index Extends ActionBase
{
    public $logger;
    protected $user_id;
    protected $staff_id;
    protected $first_name;
    protected $last_name;

    public function __construct(\Viron\Request $request, ActionBase $parent = null)
    {
        parent::__construct($request, $parent);
        $logger = new Logger('my_logger');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../../logs/my_app.log', $logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());

        $this->logger = $logger;
        $this->user_id = $this->app->user->id;
        $this->staff_id = $this->app->user->outer_user_id;
        $this->first_name = $this->app->user->first_name;
        $this->last_name = $this->app->user->last_name;
    }

    public function index()
    {
        $projects = new Model\Main;
        $mode     = Request::getStrFromGet('mode', '');

        $page = [
            '_title'   => 'Мегагрупп smartreplace - Список проектов',
            'mode'     => 'main',
            'user_id'  => $this->user_id,
            'staff_id' => $this->staff_id,
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name
        ];

        if ( $mode == '' ) {
            $projects = $projects->init($this->user_id);
            $page['projects'] = $projects;
        }
        else if ( $mode == 'addNewProject' ) {
            $post = Request::getPost();
            $projects->addNewProject($post, $this->user_id);
        }
        else if ( $mode == 'editProjectName' ) {
            $post = Request::getPost();
            $projects->editProjectName($post);
        }
        else if ( $mode == 'removeProject' ) {
            $project_id = Request::getStrFromPost('project_id', '');
            $projects->removeProject($project_id, $this->user_id);
        }
//        else if ( $mode == 'checkScript' ) {
//            $site_url = Request::getStrFromGet('site_url', '');
//            $project_id = Request::getStrFromGet('project_id', '');
//            $projects->checkScript($site_url,$project_id);
//        }



        return $page;

    }

    public function Pages()
    {
        $pages = new Model\Pages();
        $mode     = Request::getStrFromGet('mode', '');
        $project_id = Request::getStrFromGet('project_id', '');
        $site_url = Request::getStrFromGet('site_url', '') ?? Request::getStrFromPost('site_url', '');

        $page = [
            '_title'   => 'Мегагрупп smartreplace - Список Страниц',
            'mode'     => 'main',
            'project_id' => $project_id,
            'site_url' => urldecode($site_url)
        ];

        if ( $mode == '' ) {
            $pages = $pages->init($project_id, urldecode($site_url));
            $page['pages'] = $pages;
        }
        else if ( $mode == 'addNewPage' ) {
            $post = Request::getPost();
            $pages->addNewPage($post, $project_id);
        }
        else if ( $mode == 'editPageName' ) {
            $post = Request::getPost();
            $pages->editPageName($post);
        }
        else if ( $mode == 'removePage' ) {
            $project_id = Request::getStrFromPost('project_id', '');
            $page_id = Request::getStrFromPost('page_id', '');
            $pages->removePage($page_id, $project_id);
        }
        else if ( $mode == 'checkScript' ) {
            $page_url = Request::getStrFromGet('page_url', '');
            $project_id = Request::getStrFromGet('project_id', '');
            $pages->checkScript($page_url, $site_url, $project_id);
        }



        return $page;

    }

    public function Addelements()
    {

        $elements   = new Model\Elements();
        $mode       = Request::getStrFromGet('mode', '');
        $page_id = Request::getStrFromGet('page_id', '');
        $project_id = Request::getStrFromGet('project_id', '');


        $page = [
            '_title'     => 'Мегагрупп smartreplace - Добавление элементов',
            'mode'       => 'addelements',
            'project_id' => $project_id,
            'page_id'    => $page_id,
        ];



        if ($mode == '') {
            $result = $elements->init($project_id, $page_id);
            $page['site_url'] = $result['site_url'];
            $page['page_url'] = $result['page_url'];
            $page['dataFields'] = $result['elements'];
            $page['firstCheck'] = $result['firstCheck'];
        }
        else if ($mode == 'insertToDb') {

            $elements->insertToDb(Request::getPost());

            $result = $elements->sendToClient($page_id);

            $page['dataFields'] = $result['elements'];
            $page['firstCheck'] = $result['firstCheck'];
            $page['mode'] = 'insertToDb';

        }
        else if ($mode == 'removeElement') {
            $check_user = $elements->removeElement(Request::getGet(), $this->user_id);

            if ( $check_user ) {
                if ( $page_id ) {
                    $result = $elements->sendToClient($page_id);
                    $page['dataFields'] = $result['elements'];
                    $page['firstCheck'] = $result['firstCheck'];
                    $page['mode'] = 'removeElement';
                }
            }
        }

        return $page;

    }

    public function Complete()
    {

        $groups     = new Model\Groups();
        $mode       = Request::getStrFromGet('mode', '');
        $project_id = Request::getStrFromGet('project_id', '');
        $page_id    = Request::getStrFromGet('page_id', '');



        $page = [
            '_title'   => 'Мегагрупп smartreplace - Список групп',
            'mode'     => 'complete'
        ];

        if ( $mode == '' ) {
            $result = $groups->complete($project_id, $this->user_id, $page_id);

            $page['project'] = $result['project'][0];
            $page['page'] = $result['page'][0];
            $page['groups'] = $result['groups'];
            $page['elements'] = $result['elements'];
            $page['project_name'] = $result['project_name'];
            $page['page_name'] = $result['page_name'];
            $page['list'] = $result['list'];
            $page['project_id'] = $result['project_id'];
            $page['page_id'] = $result['page_id'];
        }
        else if ( $mode == 'removeGroup' ) {
            $group_id = Request::getStrFromPost('group_id', '');
            $groups->removeGroup($group_id, $project_id ,$this->user_id );
        }
        else if ( $mode == 'saveGroup' ) {
            $post = Request::getPost();
            $page['project_id'] = $project_id;
            $page['page_id'] = $page_id;
            $groups->saveGroup($post);
        }
        else if ( $mode == 'addNewGroup' ) {

            $post = Request::getPost();

            $new_group = $groups->addNewGroup($post);

            $page['dataFields'] = $new_group;
            $page['mode'] = 'addNewGroup';

        }
        else if ( $mode == 'checkScript' ) {
            $site_url = Request::getStrFromGet('site_url', '');
            $project_id = Request::getStrFromGet('project_id', '');
            $page_id = Request::getStrFromGet('page_id', '');
            $groups->checkScript($site_url, $project_id, $page_id);
        }

        return $page;

    }

    public function Srapi (){

        $api  = new Model\Srapi();
        $mode = Request::getStrFromGet('mode', '');
        $get  = Request::getGet();



        $page = [
            '_title'   => 'Мегагрупп smartreplace - api',
            'mode'     => 'getGroup'
        ];

        if ( $mode == 'getGroup' ) {
            $replacements = $api->getGroup($get);
            $page['replacements'] = json_encode($replacements);

            //$this->logger->addWarning($page['replacements']);
            //header('Content-Type: application/json');
        }

        return $page;
    }

    public function Code () {
        $code = new Model\Code();
        $mode = Request::getStrFromGet('mode', '');
        $get  = Request::getGet();

        $page = [
            '_title'            => 'Мегагрупп smartreplace - Проверка кода',
            'mode'              => 'Code',
            'project_id'        => $get['project_id'],
            'page_id'           => $get['page_id'],
            'project_name'      => $get['site_url'],
            'page_name'         => $get['page_url'],
            'real_project_name' => $get['real_project_name']
        ];

        //$result = $code->init($get);

        if ( $mode == 'checkScript' ) {
            $result = $code->checkScript($get);
            $page['mode'] = 'checkScript';
            $page['code'] = $result;
        }

        return $page;
    }

}