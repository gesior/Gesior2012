<?php
if(!defined('INITIALIZED'))
	exit;

// class for 'lists' only, to use it you must set list filter:
// $yourList->setFilter(new SQL_Filter(new SQL_Field('id', 'players'), SQL_Filter::EQUAL, new SQL_Field('player_id', 'player_deaths')));
class PlayerDeath extends ObjectData
{
	public static $table = 'player_deaths';
	public $data = array('player_id' => null, 'time' => null, 'level' => null, 'killed_by' => null, 'is_player' => null, 'mostdamage_by' => null, 'mostdamage_is_player' => null, 'unjustified' => null, 'mostdamage_unjustified' => null);
	public static $fields = array('player_id', 'time', 'level', 'killed_by', 'is_player', 'mostdamage_by', 'mostdamage_is_player', 'unjustified', 'mostdamage_unjustified');
	public static $extraFields = array(array('id', 'players'), array('name', 'players'));

    public function __construct($player_id = null)
    {
		if($player_id != null)
			$this->load($player_id);
    }

	public function load($player_id)
	{
		$search_string = $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($player_id);
		$fieldsArray = array();
		foreach(self::$fields as $fieldName)
			$fieldsArray[$fieldName] = $this->getDatabaseHandler()->fieldName($fieldName);
		$this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
	}

	public function save($forceInsert = false)
	{
		if(!isset($this->data['id']) || $forceInsert)
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
		else
		{
			$updates = array();
			foreach(self::$fields as $key)
				if($key != 'id')
					$updates[] = $this->getDatabaseHandler()->fieldName($key) . ' = ' . $this->getDatabaseHandler()->quote($this->data[$key]);
			$this->getDatabaseHandler()->query('UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) . ' SET ' . implode(', ', $updates) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));
		}
	}

	public function isLoaded()
	{
		return isset($this->data['player_id']);
	}

	public function getKillerString()
	{
		if($this->data['is_player'])
		{
			return '<a href="index.php?subtopic=characters&name=' . urlencode($this->data['killed_by']) . '">' . htmlspecialchars($this->data['killed_by']) . '</a>';
		}
		else
		{
			return htmlspecialchars($this->data['killed_by']);
		}
	}

	public function getMostDamageString()
	{
		if($this->data['mostdamage_is_player'])
		{
			return '<a href="index.php?subtopic=characters&name=' . urlencode($this->data['mostdamage_by']) . '">' . htmlspecialchars($this->data['mostdamage_by']) . '</a>';
		}
		else
		{
			return htmlspecialchars($this->data['mostdamage_by']);
		}
	}

	public function setPlayerID($value){$this->data['player_id'] = $value;}
	public function getPlayerID(){return $this->data['player_id'];}
	public function setTime($value){$this->data['time'] = $value;}
	public function getTime(){return $this->data['time'];}
	public function setLevel($value){$this->data['level'] = $value;}
	public function getLevel(){return $this->data['level'];}
	public function setKilledBy($value){$this->data['killed_by'] = $value;}
	public function getKilledBy(){return $this->data['killed_by'];}
	public function setIsPlayer($value){$this->data['is_player'] = $value;}
	public function getIsPlayer(){return $this->data['is_player'];}
	public function setMostDamageBy($value){$this->data['mostdamage_by'] = $value;}
	public function getMostDamageBy(){return $this->data['mostdamage_by'];}
	public function setMostDamageIsPlayer($value){$this->data['mostdamage_is_player'] = $value;}
	public function getMostDamageIsPlayer(){return $this->data['mostdamage_is_player'];}
	public function setUnjustified($value){$this->data['unjustified'] = $value;}
	public function getUnjustified(){return $this->data['unjustified'];}
	public function setMostDamageUnjustified($value){$this->data['mostdamage_unjustified'] = $value;}
	public function getMostDamageUnjustified(){return $this->data['mostdamage_unjustified'];}
}