<?php
//require_once APPLICATION_PATH . '/../vendor/autoload.php';
require_once APPLICATION_PATH . '/../library/Universe/AclConfig.php';

use Abraham\TwitterOAuth\TwitterOAuth;
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _initSession()
    {
        Zend_Session::start();
    }
}
