<?php
if(!defined('INITIALIZED'))
	exit;

class DatabaseHandler
{
	//private $SQL;

	public function getDatabaseHandler()
	{
/* CUSTOM VERSION, FASTER, BUT IT IS POSSIBLE TO SHOW DATABASE PASSWORD WHEN ERROR OCCUR
	CAN BE INTERESTING IF YOU EXECUTE 1000 OR 10.000 SQL QUERIES PER PAGE, FOR NORMAL USEAGE NOT NEEDED
		if(!isset($this->SQL))
			$this->SQL = Website::getDBHandle();

		return $this->SQL;
*/
		return Website::getDBHandle();
	}
}