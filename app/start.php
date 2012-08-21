<?php

/* 
 * SQUEAK FRAMEWORK
 * A simple microframework for PHP
 * by Tim Keller
 * Copyright 2012
 */

// -----------------------------------------
// Tick-Tock
// -----------------------------------------
$tracking_time_start = microtime(true);

// -----------------------------------------
// Error Reporting
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

$loader = new Twig_Loader_Filesystem(array(APP_ROOT . '/views/', APP_ROOT . '/layouts/'));
$twig = new Twig_Environment($loader);


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
// Router
// -----------------------------------------

// Process Route from Request URI
$request = explode("/", substr($_SERVER['REQUEST_URI'],1));

// Location of the 404
$fourohfour = APP_ROOT . '/controllers/404.php';

// Default Routes
if($request[0] == '') {
	$request[0] = 'Index';
	$request[1] = 'Index';
}

// Default Action if only a controller is specified
if($request[1] == '') {
	$request[1] = 'Index';
}

// Location of controllers
$file = APP_ROOT . '/controllers/' . $request[0] . '.php';
if (file_exists($file)) require_once $file;
else require_once $fourohfour;

// Primary Controller Action route
$handler = array( ucwords($request[0]).'Controller', ucwords($request[1]).'Action');

// Additional Request parameters
$params = array_splice($request, 2);

// Execute
if ( is_callable($handler) ) 
{ 
	// Collect the template variables
	global $template_vars;
	if(count($template_vars)==0) $template_vars = array();

	// Call Controller and Action
	call_user_func_array( $handler , $params ); 

	// Render Template
	call_user_func_array( 	array($request[0].'Controller', '_render'), 
							array(	"request" 	=> $request,
									"variables"	=>$template_vars )); 
}
else 
{
	// Error 404
	require_once $fourohfour;
	call_user_func_array( array("Fourohfour", "IndexAction") , array() ); 
}


