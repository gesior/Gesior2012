<?php
if(!defined('INITIALIZED'))
	exit;

class Item
{
	public $data = array('pid' => null, 'sid' => null, 'itemtype' => null, 'count' => null, 'attributes' => null);
	public static $fields = array('player_id', 'pid', 'sid', 'itemtype', 'count', 'attributes');
	public static $table = 'player_items';
	public $attributes;

	const SLOT_FIRST = 1;
	const SLOT_HEAD = 1;
	const SLOT_NECKLACE = 2;
	const SLOT_BACKPACK = 3;
	const SLOT_ARMOR = 4;
	const SLOT_RIGHT = 5;
	const SLOT_LEFT = 6;
	const SLOT_LEGS = 7;
	const SLOT_FEET = 8;
	const SLOT_RING = 9;
	const SLOT_AMMO = 10;

	public static function addField($name)
	{
		if(!in_array($name, self::$fields))
			self::$fields[] = $name;
	}

	public static function removeField($name)
	{
		if(in_array($name, self::$fields))
			unset(self::$fields[$name]);
	}

	public static function getFieldsList()
	{
		return self::$fields;
	}

    public function __construct($data = null)
    {
		if($data != null)
			$this->loadData($data);
    }

	public function loadData(&$data)
	{
		$this->data = $data;
	}
	public function getPID(){return $this->data['pid'];}
	public function setPID($value){$this->data['pid'] = $value;}
	public function getSID(){return $this->data['sid'];}
	public function setSID($value){$this->data['sid'] = $value;}
	public function getID(){return $this->data['itemtype'];}
	public function setID($value){$this->data['itemtype'] = $value;}
	public function getCount(){return $this->data['count'];}
	public function setCount($value){$this->data['count'] = $value;}
	public function getAttributes(){return $this->data['attributes'];}
	public function setAttributes($value){$this->data['attributes'] = $value;}

	public function loadAttributes()
	{
		if(!isset($this->attributes))
			$this->attributes = new ItemAttributes($this->getAttributes());
	}

	public function getAttributesList()
	{
		$this->loadAttributes();
		return $this->attributes->getAttributesList();
	}

	public function hasAttribute($attributeName)
	{
		$this->loadAttributes();
		return $this->attributes->hasAttribute($attributeName);
	}

	public function getAttribute($attributeName)
	{
		$this->loadAttributes();
		return $this->attributes->getAttribute($attributeName);
	}
}