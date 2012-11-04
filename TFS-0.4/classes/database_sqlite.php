<?php
if(!defined('INITIALIZED'))
	exit;

class Database_SQLite extends Database
{
	public function __construct()
	{
		$this->setDatabaseDriver(self::DB_SQLITE);
	}

	public function connect()
	{
		try
		{
			parent::__construct('sqlite:' . $this->getDatabaseFile());
			$this->setConnected(true);
			return true;
		}
		catch(PDOException $error)
		{
			new Error_Critic('', 'CANNOT CONNECT TO DATABASE: ' . $error->getMessage());
			return false;
		}
	}

	public function fieldName($name)
	{
		if(strspn($name, "1234567890qwertyuiopasdfghjklzxcvbnm_") != strlen($name))
			new Error_Critic('', 'Invalid field name format.');

		return '"' . $name . '"';
	}

	public function tableName($name)
	{
		if(strspn($name, "1234567890qwertyuiopasdfghjklzxcvbnm_") != strlen($name))
			new Error_Critic('', 'Invalid table name format.');

		return '"' . $name . '"';
	}
}