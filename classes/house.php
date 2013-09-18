<?php
if(!defined('INITIALIZED'))
	exit;

class House extends ObjectData
{
	public static $table = 'houses';
	public $data = array('owner' => null, 'paid' => null, 'warnings' => null, 'name' => null, 'town_id' => null, 'size' => null,  'rent' => null, 'beds' => null, 'bid' => null, 'bid_end' => null, 'last_bid' => null, 'highest_bidder' => null);
	public static $fields = array('id', 'owner', 'paid', 'warnings',  'name', 'town_id', 'size', 'rent', 'beds', 'bid', 'bid_end', 'last_bid', 'highest_bidder');

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
	public function setOwner($value){$this->data['owner'] = $value;}
	public function getOwner(){return $this->data['owner'];}
	public function setPaid($value){$this->data['paid'] = $value;}
	public function getPaid(){return $this->data['paid'];}
	public function setWarnings($value){$this->data['warnings'] = $value;}
	public function getWarnings(){return $this->data['warnings'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setTown($value){$this->data['town_id'] = $value;}
	public function getTown(){return $this->data['town_id'];}
	public function setSize($value){$this->data['size'] = $value;}
	public function getSize(){return $this->data['size'];}
	public function setRent($value){$this->data['rent'] = $value;}
	public function getRent(){return $this->data['rent'];}
	public function setBeds($value){$this->data['beds'] = $value;}
	public function getBeds(){return $this->data['beds'];}
	public function setBid($value){$this->data['bid'] = $value;}
	public function getBid(){return $this->data['bid'];}
	public function setBidEnd($value){$this->data['bid_end'] = $value;}
	public function getBidEnd(){return $this->data['bid_end'];}
	public function setLastBid($value){$this->data['last_bid'] = $value;}
	public function getLastBid(){return $this->data['last_bid'];}
	public function setHighestBidder($value){$this->data['highest_bidder'] = $value;}
	public function getHighestBidder(){return $this->data['highest_bidder'];}
}