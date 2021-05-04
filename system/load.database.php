<?php
if(!defined('INITIALIZED'))
	exit;

if(Website::getServerConfig()->isSetKey('sqlHost'))
{
    define('SERVERCONFIG_SQL_TYPE', 'sqlType');
    define('SERVERCONFIG_SQL_HOST', 'sqlHost');
    define('SERVERCONFIG_SQL_PORT', 'sqlPort');
    define('SERVERCONFIG_SQL_USER', 'sqlUser');
    define('SERVERCONFIG_SQL_PASS', 'sqlPass');
    define('SERVERCONFIG_SQL_DATABASE', 'sqlDatabase');
}
elseif(Website::getServerConfig()->isSetKey('mysqlHost'))
{
    define('SERVERCONFIG_SQL_TYPE', 'sqlType');
    define('SERVERCONFIG_SQL_HOST', 'mysqlHost');
    define('SERVERCONFIG_SQL_PORT', 'mysqlPort');
    define('SERVERCONFIG_SQL_USER', 'mysqlUser');
    define('SERVERCONFIG_SQL_PASS', 'mysqlPass');
    define('SERVERCONFIG_SQL_DATABASE', 'mysqlDatabase');
}
else
	throw new RuntimeException('There is no key <b>sqlHost</b> or <b>mysqlHost</b> in server config');
if(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_TYPE) == 'mysql')
{
	Website::setDatabaseDriver(Database::DB_MYSQL);
	if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_HOST))
		Website::getDBHandle()->setDatabaseHost(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_HOST));
	else
		throw new RuntimeException('There is no key <b>' . SERVERCONFIG_SQL_HOST . '</b> in server config file.');
	if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PORT))
		Website::getDBHandle()->setDatabasePort(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PORT));
	else
		throw new RuntimeException('There is no key <b>' . SERVERCONFIG_SQL_PORT . '</b> in server config file.');
	if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_DATABASE))
		Website::getDBHandle()->setDatabaseName(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_DATABASE));
	else
		throw new RuntimeException('There is no key <b>' . SERVERCONFIG_SQL_DATABASE . '</b> in server config file.');
	if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_USER))
		Website::getDBHandle()->setDatabaseUsername(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_USER));
	else
		throw new RuntimeException('There is no key <b>' . SERVERCONFIG_SQL_USER . '</b> in server config file.');
	if(Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PASS))
		Website::getDBHandle()->setDatabasePassword(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PASS));
	else
		throw new RuntimeException('There is no key <b>' . SERVERCONFIG_SQL_PASS . '</b> in server config file.');
}
else
	throw new RuntimeException('Database error. Unknown database type in <b>server config</b> . Must be equal to: "<b>mysql</b>" . Now is: "<b>' . Website::getServerConfig()->getValue(SERVERCONFIG_SQL_TYPE) . '</b>"');
Website::setPasswordsEncryption(Website::getServerConfig()->getValue('encryptionType'));
$SQL = Website::getDBHandle();
