<?php
if(!defined('INITIALIZED'))
	exit;

class Website extends WebsiteErrors
{
	public static $websiteConfig;
	public static $vocations = ['None', 'Sorcerer', 'Druid', 'Paladin', 'Knight'];
	public static $SQL;
	private static $passwordsEncryption = 'plain';

	public static function setDatabaseDriver($value)
	{
		self::$SQL = null;

		switch($value)
		{
			case Database::DB_MYSQL:
				self::$SQL = new Database_MySQL();
				break;
		}
	}

	public static function getDBHandle()
	{
		if(isset(self::$SQL))
			return self::$SQL;
		else
			new Error_Critic('#C-9', 'ERROR: <b>#C-9</b> : Class::Website - getDBHandle(), database driver not set.');
	}	

	public static function loadWebsiteConfig()
	{
		self::$websiteConfig = new ConfigPHP();
		global $config;
		self::$websiteConfig->setConfig($config['site']);
	}

	public static function getWebsiteConfig()
	{
		if(!isset(self::$websiteConfig))
			self::loadWebsiteConfig();

		return self::$websiteConfig;
	}

	public static function getConfig($fileNameArray)
	{
		$fileName = implode('_', $fileNameArray);

		if(Functions::isValidFolderName($fileName))
		{
			$_config = new ConfigPHP('./config/' . $fileName . '.php');
			return $_config;
		}
		else
			new Error_Critic('', __METHOD__ . ' - invalid folder/file name <b>' . htmlspecialchars('./config/' . $fileName . '.php') . '</b>');
	}

	public static function getFileContents($path)
	{
		$file = file_get_contents($path);

		if($file === false)
			new Error_Critic('', __METHOD__ . ' - Cannot read from file: <b>' . htmlspecialchars($path) . '</b>');

		return $file;
	}

	public static function putFileContents($path, $data, $append = false)
	{
		if($append)
			$status = file_put_contents($path, $data, FILE_APPEND);
		else
			$status = file_put_contents($path, $data);

		if($status === false)
			new Error_Critic('', __METHOD__ . ' - Cannot write to: <b>' . htmlspecialchars($path) . '</b>');

		return $status;
	}

	public static function deleteFile($path)
	{
		unlink($path);
	}

	public static function fileExists($path)
	{
		return file_exists($path);
	}

	public static function getPasswordsEncryption()
	{
		return self::$passwordsEncryption;
	}

	public static function encryptPassword($password, $account = null)
	{
		if(isset(self::$passwordsEncryption))
			if(self::$passwordsEncryption == 'plain')
				return $password;
			else
				return hash(self::$passwordsEncryption, $password);
		else
			new Error_Critic('#C-13', 'You cannot use Website::encryptPassword(\$password) when password encryption is not set.');
	}

    public static function getVocations()
    {

        return self::$vocations;
    }

    public static function getVocationName($id)
    {

        return self::$vocations[$id];
    }

	public static function getGroupName($id)
	{
		return 'Player';
	}

	public static function getCountryCode($IP)
	{
		$a = explode(".",$IP);
		if($a[0] == 10) // IPs 10.0.0.0 - 10.255.255.255 = private network, so can't geolocate
			return 'unknown';
		if($a[0] == 127) // IPs 127.0.0.0 - 127.255.255.255 = local network, so can't geolocate
			return 'unknown';
		if($a[0] == 172 && ($a[1] >= 16 && $a[1] <= 31)) // IPs 172.16.0.0 - 172.31.255.255 = private network, so can't geolocate
			return 'unknown';
		if($a[0] == 192 && $a[1] == 168) // IPs 192.168.0.0 - 192.168.255.255 = private network, so can't geolocate
			return 'unknown';
		if($a[0] >= 224) // IPs over 224.0.0.0 are not assigned, so can't geolocate
			return 'unknown';
		$longIP = $a[0] * 256 * 256 * 256 + $a[1] * 256 * 256 + $a[2] * 256 + $a[3]; // we need unsigned value
		if(!file_exists('cache/flags/flag' . $a[0]))
		{
			$flagData = @file_get_contents('http://country-flags.ots.me/flag' . $a[0]);
			if($flagData === false)
				return 'unknown';
			if(@file_put_contents('cache/flags/flag' . $a[0], $flagData) === false)
				return 'unknown';
		}
		$countries = unserialize(file_get_contents('cache/flags/flag' . $a[0])); // load file
		$lastCountryCode = 'unknown';
		foreach($countries as $fromLong => $countryCode)
		{
			if($fromLong > $longIP)
				break;
			$lastCountryCode = $countryCode;
		}
		return $lastCountryCode;
	}
}
