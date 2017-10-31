<?php
/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 30.10.17
 * Time: 18:18
 */

namespace Megagroup\SmartReplace;
use Megagroup\SmartReplace\Controllers;

class Menu
{
    private static $alias;
    private static $first_level;
    private static $all_items;
    private static $menu_type;
    private $fenom;

    public function __construct($fenom)
    {
        $this->fenom = $fenom;
        self::$menu_type = Db::select('SELECT alias,type FROM menu');

        foreach (self::$menu_type as $menu) {
            $this->get_items($menu['alias'], $menu['type']);
        }
    }

    public function get_items ($alias = null, $type) {
        if( $type == 1 ) {
            self::$first_level = Db::select("SELECT m.alias, mi.*, p.url, p.name FROM menu m JOIN menu_items mi JOIN pages p WHERE m.id=mi.menu_id AND mi.level=1 AND m.alias='$alias' AND p.item_id=mi.id ORDER BY mi.left_key;
");
            $this->fenom->assign($alias, self::$first_level);
        } elseif ( $type == 2 ) {
            self::$all_items = Db::select("SELECT m.alias, mi.*, p.url, p.name FROM menu m JOIN menu_items mi JOIN pages p WHERE m.id=mi.menu_id AND m.alias='$alias' AND p.item_id=mi.id ORDER BY mi.left_key;
");
            $this->fenom->assign($alias, self::$all_items);
        }
    }
}