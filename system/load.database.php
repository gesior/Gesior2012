<?php
if(!defined('INITIALIZED'))
	exit;

if(Website::getServerConfig()->isSetKey('mysqlHost'))
{
	define('SERVERCONFIG_SQL_HOST', 'mysqlHost');
	define('SERVERCONFIG_SQL_PORT', 'mysqlPort');
	define('SERVERCONFIG_SQL_USER', 'mysqlUser');
	define('SERVERCONFIG_SQL_PASS', 'mysqlPass');
	define('SERVERCONFIG_SQL_DATABASE', 'mysqlDatabase');
	define('SERVERCONFIG_SQLITE_FILE', 'sqlFile');
}
else
	new Error_Critic('#E-3', 'There is no key <b>mysqlHost</b> in server config', array(new Error('INFO', 'use server config cache: <b>' . (Website::getWebsiteConfig()->getValue('useServerConfigCache') ? 'true' : 'false') . '</b>')));
Website::setDatabaseDriver(Database::DB_MYSQL);
if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_HOST))
	Website::getDBHandle()->setDatabaseHost(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_HOST));
else
	new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_HOST . '</b> in server config file.');
if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PORT))
	Website::getDBHandle()->setDatabasePort(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PORT));
else
	new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_PORT . '</b> in server config file.');
if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_DATABASE))
	Website::getDBHandle()->setDatabaseName(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_DATABASE));
else
	new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_DATABASE . '</b> in server config file.');
if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_USER))
	Website::getDBHandle()->setDatabaseUsername(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_USER));
else
	new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_USER . '</b> in server config file.');
if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PASS))
	Website::getDBHandle()->setDatabasePassword(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PASS));
else
	new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_PASS . '</b> in server config file.');

Website::setPasswordsEncryption(Website::getServerConfig()->getValue('passwordType'));
$SQL = Website::getDBHandle();