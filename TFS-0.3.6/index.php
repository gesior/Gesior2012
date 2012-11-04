<?php
// comment to show E_NOTICE [undefinied variable etc.], comment if you want make script and see all errors
error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);

// true = show sent queries and SQL queries status/status code/error message
define('DEBUG_DATABASE', false);

define('INITIALIZED', true);

// if not defined before, set 'false' to load all normal
if(!defined('ONLY_PAGE'))
	define('ONLY_PAGE', false);
	
// check if site is disabled/requires installation
include_once('./system/load.loadCheck.php');

// fix user data, load config, enable class auto loader
include_once('./system/load.init.php');

// DATABASE
include_once('./system/load.database.php');
if(DEBUG_DATABASE)
	Website::getDBHandle()->setPrintQueries(true);
// DATABASE END

// LOGIN
if(!ONLY_PAGE)
	include_once('./system/load.login.php');
// LOGIN END

// COMPAT
// some parts in that file can be blocked because of ONLY_PAGE constant
include_once('./system/load.compat.php');
// COMPAT END

// LOAD PAGE
include_once('./system/load.page.php');
// LOAD PAGE END

// LAYOUT
// with ONLY_PAGE we return only page text, not layout
if(!ONLY_PAGE)
	include_once('./system/load.layout.php');
else
	echo $main_content;
// LAYOUT END