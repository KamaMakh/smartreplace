<?php
namespace Megagroup\DynamicContent;

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 17.09.17
 * Time: 16:39
 */
class PageController
{
    private $alias;

    public function __construct($url) {
        $this->alias = $url;
    }

    public function init() {
        global $fenom;
        $page = Db::select("SELECT * FROM pages WHERE url = '$this->alias'");

        if ( $page ) {
            $page = $page[0];

            $fenom->assign('h1', $page['name']);
            $fenom->assign('text', $page['content_text']);
            $fenom->display('text.tpl');
        } else {
            $fenom->display('404.tpl');
        }
    }
}