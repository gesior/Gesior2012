<?php
$initialized = true;
// true = show sent queries and SQL queries status/status code/error message
define('DEBUG_DATABASE', false);
// comment to show E_NOTICE [undefinied variable etc.], comment if you want make script and see all errors
error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);
include_once('./system/load.loadCheck.php');
//start :)
include_once('./system/load.init.php');

// DATABASE
include_once('./system/load.database.php');
if(DEBUG_DATABASE)
	Website::getDBHandle()->setPrintQueries(true);
// DATABASE END

// LOGIN
include_once('./system/load.login.php');
// LOGIN END

// COMPAT
include_once('./system/load.compat.php');
// COMPAT END

// LOAD PAGE
include_once('./system/load.page.php');
// LOAD PAGE END

// LAYOUT
include_once('./system/load.layout.php');
// LAYOUT END