<?php
if(!defined('INITIALIZED'))
	exit;

class PlayerNamelocks extends ObjectData
{
	public static $table = 'player_namelocks';
	public $data = array('player_id' => null, 'name' => null, 'new_name' => null, 'date' => null);
	public static $fields = array('player_id', 'name', 'new_name', 'date');

	public function save()
	{
		$keys = array();
		$values = array();
		foreach(self::$fields as $key)
			if($key != 'id')
			{
				$keys[] = $this->getDatabaseHandler()->fieldName($key);
				$values[] = $this->getDatabaseHandler()->quote($this->data[$key]);
			}
		$this->getDatabaseHandler()->query('INSERT INTO ' . $this->getDatabaseHandler()->tableName(self::$table) . ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')');
			$this->setID($this->getDatabaseHandler()->lastInsertId());
	}

	public function setPlayerID($value){$this->data['player_id'] = $value;}
	public function getPlayerID(){return $this->data['player_id'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setNewName($value){$this->data['new_name'] = $value;}
	public function getNewName(){return $this->data['new_name'];}
	public function setDate($value){$this->data['date'] = $value;}
	public function getDate(){return $this->data['date'];}
}