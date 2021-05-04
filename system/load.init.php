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
			throw new RuntimeException('Cannot load class <b>' . $className . '</b>, file <b>./classes/class.' . strtolower($className) . '.php</b> doesn\'t exist');
}
spl_autoload_register('autoLoadClass');

//load acc. maker config to $config['site']
$config = array();
include('./config/config.php');
//load server config $config['server']
$tmp_lua_config = new ConfigLUA(Website::getWebsiteConfig()->getValue('serverPath') . 'config.lua');
$config['server'] = $tmp_lua_config->getConfig();

// remove magic quotes, to make it compatible with some bad PHP configurations, 'stripslashes' in scripts is not needed anymore!
if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
{
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while(list($key, $val) = each($process))
	{
        foreach ($val as $k => $v)
		{
            unset($process[$key][$k]);
            if(is_array($v))
			{
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            }
			else
                $process[$key][stripslashes($k)] = stripslashes($v);
        }
    }
    unset($process);
}
