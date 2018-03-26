<?php

use MF\DB\MySQL;
use MF\DB\MySQL as DB;

require_once __DIR__ . '/../../lib/vendor/autoload.php';
$config = require __DIR__.'/../../config/config.php';

$mysql_driver = New DB\Driver(
    $config['DB']['user'],
    $config['DB']['password'],
    $config['DB']['database'],
    $config['DB']['host'],
    $config['DB']['port']
);

$mysql_driver -> connect();
MySQL::setDriver($mysql_driver);


$projects = Db::fetchAll("SELECT * from sr_projects");


foreach ($projects as $project_key => $project) {
    $site_url = $project['project_name'];
    $project_id = $project['project_id'];
    $parse_url = parse_url($site_url);
    $code_status = $project['code_status'];
    $page_url = "/";

    if (isset($parse_url['path'])) {
        $url_without_path = str_replace($parse_url['path'], '', $site_url);
        $page_url = $parse_url['path'];
        Db::query("UPDATE sr_projects SET project_name=$url_without_path WHERE project_id=$project_id");
    }
    DB::query("INSERT INTO sr_pages (page_id, project_id, page_name, code_status) VALUES (null,$project_id,$page_url,$code_status)");
    DB::query("ALTER TABLE sr_projects DROP COLUMN code_status");

    $page_id = Db::fetchAll("SELECT page_id FROM sr_pages WHERE project_id = $project_id")[0]['page_id'];

    DB::query("UPDATE sr_groups SET project_id=$page_id WHERE project_id=$project_id");
    DB::query("UPDATE sr_replacements SET project_id=$page_id WHERE project_id=$project_id");
    DB::query("UPDATE sr_templates SET project_id=$page_id WHERE project_id=$project_id");

    DB::query("ALTER TABLE sr_groups CHANGE COLUMN project_id page_id INT(11) NOT NULL DEFAULT 0");
    DB::query("ALTER TABLE sr_replacements CHANGE COLUMN project_id page_id INT(11) NOT NULL DEFAULT 0");
    DB::query("ALTER TABLE sr_templates CHANGE COLUMN project_id page_id INT(11) NOT NULL DEFAULT 0");

}