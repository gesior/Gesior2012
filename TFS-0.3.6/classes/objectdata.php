<?php
if(!defined('INITIALIZED'))
	exit;

class ObjectData extends DatabaseHandler
{
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

	public function loadData($data)
	{
		$this->data = $data;
	}

	public function isLoaded()
	{
		return isset($this->data['id']);
	}

	public function get($fieldName)
	{
		if(isset($this->data[$fieldName]))
			return $this->data[$fieldName];
		else
			new Error_Critic(__METHOD__ . ' - Field ' . htmlspecialchars($fieldName) . ' does not exist in data / is not loaded.');
	}

	public function set($fieldName, $value)
	{
		$this->data[$fieldName] = $value;
	}

	public function getCustomField($fieldName)
	{
		if(isset($this->data[$fieldName]))
			return $this->data[$fieldName];
		else
			new Error_Critic(__METHOD__ . ' - Field ' . htmlspecialchars($fieldName) . ' does not exist in data / is not loaded.');
	}

	public function setCustomField($fieldName, $value)
	{
		if(isset($this->data[$fieldName]))
		{
			$this->data[$fieldName] = $value;
			$this->save();
		}
		else
			new Error_Critic(__METHOD__ . ' - Field ' . htmlspecialchars($fieldName) . ' does not exist in data / is not loaded. Cannot save it.');
	}
/*
 * for compability with old scripts
*/
	public function getId()
	{
		return $this->data['id'];
	}
}