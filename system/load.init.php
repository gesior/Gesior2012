<?php
if(!defined('INITIALIZED'))
	exit;

$time_start = microtime(true);
session_start();

function autoLoadClass($className)
{
	if(!class_exists($className))
		if(file_exists('./classes/' . strtolower($className) . '.php'))
			include_once('./classes/' . strtolower($className) . '.php');
		else
			throw new RuntimeException('#E-7 -Cannot load class <b>' . $className . '</b>, file <b>./classes/class.' . strtolower($className) . '.php</b> doesn\'t exist');
}
spl_autoload_register('autoLoadClass');

//load acc. maker config to $config['site']
$config = array();
include('./config/config.php');
$tmp_lua_config = new ConfigLUA(Website::getWebsiteConfig()->getValue('serverPath') . 'config.lua');
$config['server'] = $tmp_lua_config->getConfig();
