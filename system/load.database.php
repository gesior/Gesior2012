<?php
	if (!defined('INITIALIZED')) {
		exit;
	}

	if (Website::getServerConfig()->isSetKey('mysqlHost')) {
		define('SERVERCONFIG_SQL_HOST', 'mysqlHost');
		define('SERVERCONFIG_SQL_PORT', 'mysqlPort');
		define('SERVERCONFIG_SQL_USER', 'mysqlUser');
		define('SERVERCONFIG_SQL_PASS', 'mysqlPass');
		define('SERVERCONFIG_SQL_DATABASE', 'mysqlDatabase');
	} else {
        throw new LogicException('#E-3 There is no key <b>mysqlHost</b> in server config');
	}

	Website::setDatabaseDriver(Database::DB_MYSQL);
	if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_HOST)) {
		Website::getDBHandle()->setDatabaseHost(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_HOST));
	} else {
		throw new RuntimeException('#E-7 -There is no key <b>' . SERVERCONFIG_SQL_HOST . '</b> in server config file.');
	}

	if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PORT)) {
		Website::getDBHandle()->setDatabasePort(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PORT));
	} else {
		throw new RuntimeException('#E-7 -There is no key <b>' . SERVERCONFIG_SQL_PORT . '</b> in server config file.');
	}

	if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_DATABASE)) {
		Website::getDBHandle()->setDatabaseName(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_DATABASE));
	} else {
		throw new RuntimeException('#E-7 -There is no key <b>' . SERVERCONFIG_SQL_DATABASE . '</b> in server config file.');
	}

	if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_USER)) {
		Website::getDBHandle()->setDatabaseUsername(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_USER));
	} else {
		throw new RuntimeException('#E-7 -There is no key <b>' . SERVERCONFIG_SQL_USER . '</b> in server config file.');
	}

	if (Website::getServerConfig()->isSetKey(SERVERCONFIG_SQL_PASS)) {
		Website::getDBHandle()->setDatabasePassword(Website::getServerConfig()->getValue(SERVERCONFIG_SQL_PASS));
	} else {
		throw new RuntimeException('#E-7 -There is no key <b>' . SERVERCONFIG_SQL_PASS . '</b> in server config file.');
	}

	$SQL = Website::getDBHandle();
