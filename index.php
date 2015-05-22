<?php
ini_set('memory_limit',"256M");
define('APP_NAME','App');
define('APP_PATH', './App/');
define("APP_DEBUG",true);
// require_once('360_safe3.php');//360安全防护代码
define('SYSTEM_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
require './ThinkPHP/ThinkPHP.php';