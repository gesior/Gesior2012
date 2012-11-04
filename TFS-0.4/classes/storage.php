<?php
if(!defined('INITIALIZED'))
	exit;

class PlayerStorage extends ObjectData
{
	// for DatabaseList and to read key of X player, to edit player storage use function of class Player
	public static $table = 'player_storage';
	public $data = array('key' => null, 'value' => null);
	public static $fields = array('player_id', 'key', 'value');

    public function __construct($player_id = null, $key = null)
    {
		if($player_id != null && $key != null)
			$this->load($player_id, $key);
    }

	public function load($player_id, $key)
	{
		$search_string = $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($player_id) . ' AND ' . $this->getDatabaseHandler()->fieldName('key') . ' = ' . $this->getDatabaseHandler()->quote($key);
		$fieldsArray = array();
		foreach(self::$fields as $fieldName)
			$fieldsArray[$fieldName] = $this->getDatabaseHandler()->fieldName($fieldName);
		$this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
	}

	public function isLoaded()
	{
		return isset($this->data['player_id']);
	}

	public function getPlayerID(){return $this->data['player_id'];}
	public function getKey(){return $this->data['key'];}
	public function getValue(){return $this->data['value'];}
}