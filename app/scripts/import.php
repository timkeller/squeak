<?php

// -----------------------------------------
// Custom importer I wrote for IMDB datafiles
// -----------------------------------------

ini_set("memory_limit","500M");

if(!defined('APP_ROOT')) define('APP_ROOT',	dirname(dirname(__FILE__)) . "");
require_once APP_ROOT . '/config.php';
require_once APP_ROOT . '/framework/ez_sql_core.php';
require_once APP_ROOT . '/framework/ez_sql_mysql.php';

$filename = "keywords.list";


// CONNECT TO THE DATABASE
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}

	

$handle = fopen($filename, "r");

if ($handle) {
    $document_id = null;
    while (!feof($handle)) {
        $buffer = fgets($handle, 4096);
        list($movie, $keyword) = explode("\t", $buffer);
        $movie = trim($movie);
        $keyword = trim($keyword);
        
        // Create Tag from keyword
        if($keyword != '') $r = $db->query("INSERT INTO tags SET name = '{$keyword}'");
        
        // Create Doc from keyword
        if($result = $db->query("INSERT INTO documents SET name = '{$movie}'")){
        	$document_id = $db->insert_id;	
        }
        if(!is_null($document_id) and $keyword != '') $db->query("INSERT INTO pivot (document_id, tag_id) VALUES ('{$document_id}', '{$keyword}')");
    }
    fclose($handle);
}

fclose($handle);

mysqli_close($mysqli);


