<?php

use Yaf\Application;

define('APPLICATION_PATH', dirname(__DIR__));

$application = new Application( APPLICATION_PATH . "/conf/application.ini");
$dispatcher = $application->getDispatcher();
$dispatcher->disableView(); //关闭自动渲染模板

$application->bootstrap()->run();