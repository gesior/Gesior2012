<?php
	if (!defined('INITIALIZED')) {
		exit;
	}

	if (Website::getWebsiteConfig()->isSetKey('mysqlHost')) {
		define('SERVERCONFIG_SQL_HOST', 'mysqlHost');
		define('SERVERCONFIG_SQL_PORT', 'mysqlPort');
		define('SERVERCONFIG_SQL_USER', 'mysqlUser');
		define('SERVERCONFIG_SQL_PASS', 'mysqlPass');
		define('SERVERCONFIG_SQL_DATABASE', 'mysqlDatabase');
	} else {
		new Error_Critic('#E-3', 'There is no key <b>mysqlHost</b> in server config');
	}

	Website::setDatabaseDriver(Database::DB_MYSQL);
	if (Website::getWebsiteConfig()->isSetKey(SERVERCONFIG_SQL_HOST)) {
		Website::getDBHandle()->setDatabaseHost(Website::getWebsiteConfig()->getValue(SERVERCONFIG_SQL_HOST));
	} else {
		new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_HOST . '</b> in site config file.');
	}

	if (Website::getWebsiteConfig()->isSetKey(SERVERCONFIG_SQL_PORT)) {
		Website::getDBHandle()->setDatabasePort(Website::getWebsiteConfig()->getValue(SERVERCONFIG_SQL_PORT));
	} else {
		new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_PORT . '</b> in site config file.');
	}

	if (Website::getWebsiteConfig()->isSetKey(SERVERCONFIG_SQL_DATABASE)) {
		Website::getDBHandle()->setDatabaseName(Website::getWebsiteConfig()->getValue(SERVERCONFIG_SQL_DATABASE));
	} else {
		new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_DATABASE . '</b> in site config file.');
	}

	if (Website::getWebsiteConfig()->isSetKey(SERVERCONFIG_SQL_USER)) {
		Website::getDBHandle()->setDatabaseUsername(Website::getWebsiteConfig()->getValue(SERVERCONFIG_SQL_USER));
	} else {
		new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_USER . '</b> in site config file.');
	}

	if (Website::getWebsiteConfig()->isSetKey(SERVERCONFIG_SQL_PASS)) {
		Website::getDBHandle()->setDatabasePassword(Website::getWebsiteConfig()->getValue(SERVERCONFIG_SQL_PASS));
	} else {
		new Error_Critic('#E-7', 'There is no key <b>' . SERVERCONFIG_SQL_PASS . '</b> in site config file.');
	}

	$SQL = Website::getDBHandle();
