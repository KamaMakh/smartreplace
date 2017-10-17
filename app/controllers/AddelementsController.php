<?php
namespace Megagroup\SmartReplace\Controllers;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 18.09.17
 * Time: 18:24
 */
class AddelementsController
{
    public $site_url;
    public $domain;
    public $class;


    function init() {
        global $fenom;
        $fenom->display('addelements.tpl');
    }

    public function getcontent() {
        $this->site_url = $_GET['site_url'];

        $url = explode('/', $this->site_url);

        $client  =  new \GuzzleHttp\Client();
        $res = $client->request('GET', $this->site_url);

        $page = $res->getBody();


        $new_url = $url[0] . '//' . $url[2];

        $page = str_replace('href="', 'href="'.$new_url.'/', $page);
        $page = str_replace('href=\'', 'href=\''.$new_url.'/', $page);
        $page = str_replace('href="'.$new_url.'/'.$new_url, 'href="'.$new_url, $page);

        $page = str_replace('src="', 'src="'.$new_url.'/', $page);
        $page = str_replace('src=\'', 'src=\''.$new_url.'/', $page);
        $page = str_replace('src="'.$new_url.'/'.$new_url, 'src="'.$new_url, $page);



        $page = $page . '<script src="/js/site.js"></script>'
                      .'<link rel="stylesheet" href="/css/site.css">'
                      .'<div class="">';

        echo $page;
    }

    public function insert_to_db() {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' && $_GET['createList'] == 1) {

            print_r($_GET);
        }
    }
}