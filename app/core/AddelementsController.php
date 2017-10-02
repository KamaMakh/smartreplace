<?php
namespace Megagroup\DynamicContent;

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
        global $smarty;
        $smarty->display('addelements.tpl');
    }

    public function getcontent() {
        $this->site_url = $_GET['site_url'];

        $url = explode('/', $this->site_url);

        $page = file_get_contents($this->site_url);

        $new_url = $url[0] . '//' . $url[2];

        $page = str_replace('href="', 'href="'.$new_url.'/', $page);
        $page = str_replace('href=\'', 'href=\''.$new_url.'/', $page);
        $page = str_replace('href="'.$new_url.'/'.$new_url, 'href="'.$new_url, $page);

        $page = str_replace('src="', 'src="'.$new_url.'/', $page);
        $page = str_replace('src=\'', 'src=\''.$new_url.'/', $page);
        $page = str_replace('src="'.$new_url.'/'.$new_url, 'src="'.$new_url, $page);



        $page = $page . '<script src="/js/site.js"></script>';

        echo $page;
    }
}