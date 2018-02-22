<?php

use MF\DB\MySQL as DB;

require_once __DIR__.'/../lib/vendor/autoload.php';
$config = require __DIR__.'/../config/config.php';

$app = New \SmartReplace\SmartReplace($config);
$app->run();