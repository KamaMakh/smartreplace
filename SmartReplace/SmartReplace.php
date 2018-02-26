<?php

/**
 * Created by PhpStorm.
 * User: kamron
 * Date: 16.02.18
 * Time: 16:49
 */
namespace SmartReplace;
use SmartReplace\SmartReplace\Request;
use SmartReplace\Model\User;
use MF;
use MF\DB\MySQL;
use Oauth;
use \MF\DB\MySQL as DB;
use Viron\App;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;

class SmartReplace Extends App {

    private $_user_types = [
        'void' => 0,
        'client' => 1,
        'staff_agent' => 2
    ];

    private $_oauth_session = null;

    private $_config = [];

    private $_mysql_driver = null;

    private $_cache_driver = null;

    public $user = null;

    public $render = '\SmartReplace\SmartReplace\Render';
    public $logger;

    public function __construct ($config) {

        $logger = new Logger('my_logger');
        $logger->pushHandler(new StreamHandler(__DIR__.'/../logs/my_app.log', $logger::DEBUG));
        $logger->pushHandler(new FirePHPHandler());
        $this->logger = $logger;

        $_GET['mcs'] = 1;
        define('DEBUG',$config['develop']['debug']);
        MF\Log::addStorage(MF\Log::L_ERROR,$config['smart_replace']['app']['log']);

        $this->_config = $config;

        $this->_mysql_driver = New DB\Driver(
            $this->_config['DB']['user'],
            $this->_config['DB']['password'],
            $this->_config['DB']['database'],
            $this->_config['DB']['host'],
            $this->_config['DB']['port']
        );

        $this->_mysql_driver -> connect();
        MySQL::setDriver($this->_mysql_driver);

        $this->_cache_driver = New MF\Cache\Redis($this->_config['redis']['host']);
        MF\Cache::setUp($this->_cache_driver);

        if ($this->_config['develop']['profiling']) {
            DB::query('set profiling=1');
        }

        $uri_source = MF\Request::getSource();
        $uri_source2 = MF\Request::getFirstURIParam();

        //$this->logger->addWarning('uri', $uri_source);
        //$this->logger->addWarning('uri2'.$uri_source2);

        if (isset($uri_source2) && $uri_source2 == 'srapi') {
            parent::__construct($this->_config['smart_replace']);
            header('Access-Control-Allow-Origin: *');
            header('Content-Type: application/json, charset=UTF-8');
        } else {
            if ($this->_config['develop']['debug']) {

                $outer_id = 1142;
                $user_type_id = 2;

            } else {

                try {
                    $this->_oauth_session = Oauth\Session::init(
                        $this->_config['oauth']['client_id'],
                        $this->_config['oauth']['client_secret'],
                        $this->_config['oauth']['oauth_server'],
                        $this->_config['oauth']['attorney_servers']);

                } catch (\Exception $ex) {
                    MF\Response::redirect($this->_config['cabinet_url']);
                    exit();
                }

                if (isset($this->_oauth_session["real_uid"]) && $this->_oauth_session["real_uid"]) { //зашел наш сотрудник
                    try {
                        Oauth\Session::api($this->_config['oauth']['api']['staff_agent_info_api']);
                    } catch (\Throwable $e) {
                        $_COOKIE['mcsid'] = null;
                        $this->_oauth_session = Oauth\Session::init(
                            $this->_config['oauth']['client_id'],
                            $this->_config['oauth']['client_secret'],
                            $this->_config['oauth']['oauth_server'],
                            $this->_config['oauth']['attorney_servers']);
                    }

                    $user_type_id = $this->_user_types['staff_agent'];
                    $outer_id = $this->_oauth_session["real_uid"];

                } elseif (isset($this->_oauth_session["client_id"]) && $this->_oauth_session["client_id"]) { // Зашел клиент

                    try {
                        Oauth\Session::api($this->_config['oauth']['api']['client_info_api']);
                    } catch (\Throwable $e) {
                        $_COOKIE['mcsid'] = null;
                        $this->_oauth_session = Oauth\Session::init(
                            $this->_config['oauth']['client_id'],
                            $this->_config['oauth']['client_secret'],
                            $this->_config['oauth']['oauth_server'],
                            $this->_config['oauth']['attorney_servers']);
                    }

                    $user_type_id = $this->_user_types['client'];
                    $outer_id = $this->_oauth_session["client_id"];

                } else {
                    $this->log('Error: Can not fetch user from oauth session. No client_id or real_uid found', 0);
                    throw new \Exception('Ошибка авторизации');
                }

            }

            $this->user = New User($outer_id, $user_type_id, $this->_config['oauth']['api'],
                $this->_config['develop']['debug']);

            $this->user->access = MF\Request::getStrFromGet('mcs') ? '?mcs=1' : '';
            $this->user->access_prefix = MF\Request::getStrFromGet('mcs') ? '&mcs=1' : '';

            parent::__construct($this->_config['smart_replace']);
            header('Content-Type: text/html; charset=utf-8');
        }
    }

    public function run() {
        $this->dispatch(new SmartReplace\Request());
    }


    function __destruct()
    {
        if ($this->_config['develop']['profiling']) {
            $data = DB::fetchAll('show profiles');
            foreach ($data as $value) {
                DB::insert("profiles", [
                    'query'    => $value['Query'],
                    'duration' => $value['Duration'],
                ]);
            }
        }
    }

}