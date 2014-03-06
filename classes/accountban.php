<?php
if(!defined('INITIALIZED'))
	exit;

class AccountBan extends ObjectData
{
	public static $table = 'account_bans';
	public $data = array('reason' => null, 'banned_at' => null, 'expires_at' => null, 'banned_by' => null);
	public static $fields = array('account_id', 'reason', 'banned_at', 'expires_at', 'banned_by');

    public function __construct($search_text = null)
    {
		if($search_text != null)
			$this->load($search_text);
    }

	public function load($search_text)
	{
		$search_string = $this->getDatabaseHandler()->fieldName('account_id') . ' = ' . $this->getDatabaseHandler()->quote($search_text);
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

    public function delete()
    {
        $this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('account_id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['account_id']));

        unset($this->data['id']);
    }

	public function isLoaded()
	{
		return isset($this->data['account_id']);
	}

	public function setAccountID($value){$this->data['account_id'] = $value;}
	public function getAccountID(){return $this->data['account_id'];}
	public function setReason($value){$this->data['reason'] = $value;}
	public function getReason(){return $this->data['reason'];}
	public function setBannedAt($value){$this->data['banned_at'] = $value;}
	public function getBannedAt(){return $this->data['banned_at'];}
	public function setExpiresAt($value){$this->data['expires_at'] = $value;}
	public function getExpiresAt(){return $this->data['expires_at'];}
	public function setBannedBy($value){$this->data['banned_by'] = $value;}
	public function getBannedBy(){return $this->data['banned_by'];}
}