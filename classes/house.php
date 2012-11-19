<?php
if(!defined('INITIALIZED'))
	exit;

class House extends ObjectData
{
	public static $table = 'houses';
	public $data = array('world_id' => null, 'owner' => null, 'paid' => null, 'warnings' => null, 'lastwarning' => null, 'name' => null, 'town' => null, 'size' => null, 'price' => null, 'rent' => null, 'doors' => null, 'beds' => null, 'tiles' => null, 'guild' => null, 'clear' => null);
	public static $fields = array('id', 'world_id', 'owner', 'paid', 'warnings', 'lastwarning', 'name', 'town', 'size', 'price', 'rent', 'doors', 'beds', 'tiles', 'guild', 'clear');

    public function __construct($house_id = null)
    {
		if($house_id != null)
			$this->load($house_id);
    }

	public function load($house_id)
	{
		$search_string = $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($house_id);
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
	public function setWorldID($value){$this->data['world_id'] = $value;}
	public function getWorldID(){return $this->data['world_id'];}
	public function setOwner($value){$this->data['owner'] = $value;}
	public function getOwner(){return $this->data['owner'];}
	public function setPaid($value){$this->data['paid'] = $value;}
	public function getPaid(){return $this->data['paid'];}
	public function setWarnings($value){$this->data['warnings'] = $value;}
	public function getWarnings(){return $this->data['warnings'];}
	public function setLastWarning($value){$this->data['lastwarning'] = $value;}
	public function getLastWarning(){return $this->data['lastwarning'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setTown($value){$this->data['town'] = $value;}
	public function getTown(){return $this->data['town'];}
	public function setSize($value){$this->data['size'] = $value;}
	public function getSize(){return $this->data['size'];}
	public function setPrice($value){$this->data['price'] = $value;}
	public function getPrice(){return $this->data['price'];}
	public function setRent($value){$this->data['rent'] = $value;}
	public function getRent(){return $this->data['rent'];}
	public function setDoors($value){$this->data['doors'] = $value;}
	public function getDoors(){return $this->data['doors'];}
	public function setBeds($value){$this->data['beds'] = $value;}
	public function getBeds(){return $this->data['beds'];}
	public function setTiles($value){$this->data['tiles'] = $value;}
	public function getTiles(){return $this->data['tiles'];}
	public function setGuild($value){$this->data['guild'] = $value;}
	public function getGuild(){return $this->data['guild'];}
	public function setClear($value){$this->data['clear'] = $value;}
	public function getClear(){return $this->data['clear'];}
}