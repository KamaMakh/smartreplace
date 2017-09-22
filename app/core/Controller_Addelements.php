<?php

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 18.09.17
 * Time: 18:24
 */
class Controller_Addelements
{
    public $site_url;
    public $domain;
    public $class;


    function init() {
        global $smarty;
        if ( isset($_GET['site_url']) ) {

            $this->site_url = $_GET['site_url'];

            $page = file_get_contents($this->site_url);

            $page = str_replace('href=', 'href="'.$this->site_url, $page);
            $page = str_replace($this->site_url.'"', $this->site_url, $page);

            $page = str_replace('src=', 'src="'.$this->site_url, $page);
            $page = str_replace($this->site_url.'"/', $this->site_url, $page);

            file_put_contents('html_text.txt', $page);

            $smarty->assign('iframe', '<iframe class="main_iframe" id="main_iframe" src="http://megayagla.local/addelements/getcontent" frameborder="0" width="100%" height="600px"></iframe>');
            $smarty->display('addelements.tpl');
        }
    }

    public function getcontent () {
        echo file_get_contents('html_text.txt');
    }
}