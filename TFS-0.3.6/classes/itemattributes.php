<?php
if(!defined('INITIALIZED'))
	exit;

class ItemAttributes
{
	public $attributes = array();
	public $attributesCount = 0;
	public $attrString = '';

	public function __construct($attributes)
	{
		$this->loadAttributes($attributes);
	}

	private function getByte()
	{
		$ret = ord($this->attrString[0]);
		$this->attrString = substr($this->attrString, 1);
		return $ret;
	}

	private function getU16()
	{
		$ret = ord($this->attrString[0]) + ord($this->attrString[1]) * 256;
		$this->attrString = substr($this->attrString, 2);
		return $ret;
	}

	private function getU32()
	{
		$ret = ord($this->attrString[0]) + ord($this->attrString[1]) * 256 + ord($this->attrString[2]) * 65536 + ord($this->attrString[3]) * 16777216;
		$this->attrString = substr($this->attrString, 4);
		return $ret;
	}

	private function getString($length)
	{
		$ret = substr($this->attrString, 0, $length);
		$this->attrString = substr($this->attrString, $length);
		return $ret;
	}

	public function loadAttributes($attributes)
	{
		$this->attrString = $attributes;
		if(!empty($this->attrString) && $this->getByte() == 128)
		{
			$this->attributesCount = $this->getU16();
			while(!empty($this->attrString))
			{
				$key = $this->getString($this->getU16());
				$dataType = $this->getByte();
				if($dataType == 1)
					$value = $this->getString($this->getU32());
				elseif($dataType == 2)
					$value = $this->getU32();
				$this->attributes[$key] = $value;
			}
		}
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	public function getAttributesList()
	{
		return array_keys($this->attributes);
	}

	public function hasAttribute($attributeName)
	{
		return isset($this->attributes[$attributeName]);
	}

	public function getAttribute($attributeName)
	{
		if(!$this->hasAttribute($attributeName))
			throw new Exception(__METHOD__ . ' - Attribute: ' . htmlspecialchars($attributeName) . ' - does not exist!');

		return $this->attributes[$attributeName];
	}
}