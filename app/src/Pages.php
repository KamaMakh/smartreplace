<?php

namespace Megagroup\SmartReplace;
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 30.10.17
 * Time: 18:18
 */

class Pages
{
    private $alias;
    private $fenom;

    public function __construct(string $url, $fenom) {
        $this->alias = $url;
        $this->fenom = $fenom;
    }

    public function init() {
        $page = new Db();
        $page = $page::select("SELECT * FROM pages WHERE url = '$this->alias'");

        if ( $page ) {
            $page = $page[0];

            $this->fenom->assign('h1', $page['name']);
            $this->fenom->assign('text', $page['content_text']);
            $this->fenom->display('text.tpl');
        } else {
            echo 'lllllll'.$page;
            $this->fenom->display('404.tpl');
        }
    }
}