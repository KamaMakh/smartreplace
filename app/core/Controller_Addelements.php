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
            $smarty->assign('domain', $this->site_url);
            $smarty->assign('iframe', '<iframe class="main_iframe" id="main_iframe" src="http://megayagla.local/addelements/getcontent" frameborder="0" width="100%" height="600px"></iframe>');
            $smarty->display('addelements.tpl');
        }
    }

    public function getcontent () {

    }
}