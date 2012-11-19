<?php
if(!defined('INITIALIZED'))
	exit;

if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout')
	Visitor::logout();
if(isset($_REQUEST['account_login']) && isset($_REQUEST['password_login']))
{
	Visitor::setAccount($_REQUEST['account_login']);
	Visitor::setPassword($_REQUEST['password_login']);
	//Visitor::login(); // this set account and password from code above as login and password to next login attempt
	//Visitor::loadAccount(); // this is required to force reload account and get status of user
	$isTryingToLogin = true;
}
Visitor::login();