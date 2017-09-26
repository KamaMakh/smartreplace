<?php
namespace App\core;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:39
 */
class Controller_page
{
    private $alias;

    public function __construct($url) {
        $this->alias = $url;
    }

    public function init() {
        global $smarty;
        $page = Db::select("SELECT * FROM pages WHERE url = '$this->alias'");

        if ( $page ) {
            $page = $page[0];

            $smarty->assign('h1', $page['name']);
            $smarty->assign('text', $page['content_text']);
            $smarty->display('text.tpl');
        } else {
            $smarty->display('404.tpl');
        }
    }
}