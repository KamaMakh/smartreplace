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

    public  function __construct(\Fenom $fenom)
    {
        $this->fenom = $fenom;
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

        }
    }
}