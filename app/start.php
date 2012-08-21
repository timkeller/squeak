<?php

// -----------------------------------------
// Error Report
// -----------------------------------------
error_reporting(E_ALL ^ E_NOTICE);


// -----------------------------------------
// Config
// -----------------------------------------
if(!defined('APP_ROOT')) define('APP_ROOT',	dirname(dirname(__FILE__)) . "/app");
if(!defined('LIB_ROOT')) define('LIB_ROOT',	dirname(dirname(__FILE__)) . "/vendor");

set_include_path('.' . PATH_SEPARATOR . LIB_ROOT . PATH_SEPARATOR);

date_default_timezone_set("Africa/Johannesburg");

require_once APP_ROOT . '/config.php';

// -----------------------------------------
// Template
// -----------------------------------------
require_once LIB_ROOT . '/twig/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();

$loader = new Twig_Loader_Filesystem(APP_ROOT . '/views/');
$twig = new Twig_Environment($loader, array(
    'cache' => '/tmp/compilation_cache',
));

// -----------------------------------------
// Squeak
// -----------------------------------------
require_once APP_ROOT . '/framework/functions.php';
Squeak_Autoloader::register();

// -----------------------------------------
// Database
// -----------------------------------------
require_once APP_ROOT . '/framework/ez_sql_core.php';
require_once APP_ROOT . '/framework/ez_sql_mysql.php';

$db = new ezSQL_mysql(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);


// -----------------------------------------
// Route
// -----------------------------------------

$fourohfour = APP_ROOT . '/controllers/404.php';

$request = explode("/", substr($_SERVER['REQUEST_URI'],1));

// Route Controller
if($request[0] == '') {
	$request[0] = 'Index';
	$request[1] = 'Index';
}

$file = APP_ROOT . '/controllers/' . $request[0] . '.php';
if (file_exists($file)) {
	require_once $file;
} 
else {
	require_once $fourohfour;
}

// Route Action
if(isset($request[1])) {
	$handler = array( ucwords($request[0]).'Controller', ucwords($request[1]).'Action');
}
else {
	$handler = array( $request[0], 'IndexAction');
}

// Give the rest /of/the/parameters to the function
$params = array_splice($request, 2);

// Go!
if ( is_callable($handler) ) { 
	call_user_func_array( $handler , $params ); 
	call_user_func_array( array($request[0].'Controller', '_render') , $request ); 
}
else {
	require_once $fourohfour;
	call_user_func_array( array("Fourohfour", "IndexAction") , array() ); 
}


