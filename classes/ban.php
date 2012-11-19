<?php
if(!defined('INITIALIZED'))
	exit;

class Ban extends ObjectData
{
	const TYPE_IP = 1;
	const TYPE_PLAYER = 2;
	const TYPE_ACCOUNT = 3;
	const TYPE_NOTATION = 4;
	const TYPE_STATEMENT = 5;

	const PLAYERBAN_NONE = 0;
	const PLAYERBAN_REPORT = 1;
	const PLAYERBAN_LOCK = 2;
	const PLAYERBAN_BANISHMENT = 3;

	public static $table = 'bans';
	public $data = array('type' => null, 'value' => null, 'param' => null, 'active' => null, 'expires' => null, 'added' => null, 'admin_id' => null, 'comment' => null);
	public static $fields = array('id', 'type', 'value', 'param', 'active', 'expires', 'added', 'admin_id', 'comment');

    public function __construct($search_text = null)
    {
		if($search_text != null)
			$this->load($search_text);
    }

	public function load($search_text)
	{
		$search_string = $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($search_text);
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


	public function setID($value){$this->data['id'] = $value;}
	public function getID(){return $this->data['id'];}
	public function setType($value){$this->data['type'] = $value;}
	public function getType(){return $this->data['type'];}
	public function setValue($value){$this->data['value'] = $value;}
	public function getValue(){return $this->data['value'];}
	public function setParam($value){$this->data['param'] = $value;}
	public function getParam(){return $this->data['param'];}
	public function setActive($value){$this->data['active'] = $value;}
	public function getActive(){return $this->data['active'];}
	public function setExpires($value){$this->data['expires'] = $value;}
	public function getExpires(){return $this->data['expires'];}
	public function setAdded($value){$this->data['added'] = $value;}
	public function getAdded(){return $this->data['added'];}
	public function setAdminID($value){$this->data['admin_id'] = $value;}
	public function getAdminID(){return $this->data['admin_id'];}
	public function setComment($value){$this->data['comment'] = $value;}
	public function getComment(){return $this->data['comment'];}
}