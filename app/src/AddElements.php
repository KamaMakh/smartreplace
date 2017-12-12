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
            .'<link rel="stylesheet" href="/css/site.css">';

        echo $page;
    }

    public function insertToDb(string $method,string $mode) {
        if ( $method == 'GET' && $mode == 'add') {
            if ( $_SESSION['user'] ) {

                $this->email = $_SESSION['user']['email'];
                $this->user_id = Db::select("SELECT id FROM sr_users WHERE email='$this->email'");
                $this->project_name = $_SESSION['user']['project_name'];

                $check_project = Db::select("SELECT * FROM sr_projects WHERE project_name='$this->project_name' ");

                if ( !$check_project ) {
                    $data_fields = [
                        'user_id'=> [$this->user_id[0]['id'], 'i'],
                        'project_name'=> [$this->project_name, 's']
                    ];

                    Db::insert($data_fields, 'sr_projects');
                    $check_project = Db::select("SELECT * FROM sr_projects WHERE project_name='$this->project_name' ");
                }

                $check_template = Db::select("SELECT * FROM sr_templates WHERE project_id=".$check_project[0]['project_id']." and param ="."'".$_GET['wayToElement']."'");

                if ( !$check_template ) {
                    $data_fields = [
                        'project_id'=> [$check_project[0]['project_id'], 'i'],
                        'param'=> [$_GET['wayToElement'], 's'],
                        'type'=> [$_GET['type'], 's'],
                        'data'=> [$_GET['inner'], 's']
                    ];
                    $this->logger->addWarning('data-fields', $data_fields);
                    Db::insert($data_fields, 'sr_templates');
                }

                $this->sendToClient();

            } else {

            }

        }
    }
    public function sendToClient () {
        $elements = Db::select("SELECT * FROM sr_templates");
        $this->logger->addWarning('elements', $elements);
        if ( $elements ) {
            echo json_encode($elements);
        }
    }
}