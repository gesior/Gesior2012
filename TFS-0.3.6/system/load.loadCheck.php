<?php
if(!defined('INITIALIZED'))
	exit;

if(file_exists('disable.txt'))
	die(file_get_contents('disable.txt'));

if(file_exists('install.txt'))
	die('You must install AAC. Please visit:<br /><a href="http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/install.php">http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/install.php</a>');