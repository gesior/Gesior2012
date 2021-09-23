<?php
if(!defined('INITIALIZED'))
	exit;

class Account extends ObjectData
{
	const LOADTYPE_ID = 'id';
	const LOADTYPE_NAME = 'name';
	const LOADTYPE_MAIL = 'email';
	public static $table = 'accounts';
	public $data = array('name' => null, 'password' => null, 'premium_ends_at' => 0, 'email' => '', 'key' => '', 'create_ip' => 0, 'creation' => 0, 'premium_points' => 0, 'page_access' => 0, 'location' => '', 'rlname' => '', 'email_new' => '', 'email_new_time' => 0, 'email_code' => '', 'next_email' => 0, 'last_post' => 0, 'flag' => 'pl');
	public static $fields = array('id', 'name', 'password', 'premium_ends_at', 'email', 'key', 'create_ip', 'creation', 'premium_points', 'page_access', 'location', 'rlname', 'email_new', 'email_new_time', 'email_code', 'next_email', 'last_post', 'flag');
	public $players;
	public $bans;
    /**
     * @var DatabaseList|PlayerTrade[]
     */
    private $playerTrades;

    public function __construct($search_text = null, $search_by = self::LOADTYPE_ID)
    {
		if($search_text != null)
			$this->load($search_text, $search_by);
    }

	public function load($search_text, $search_by = self::LOADTYPE_ID)
	{
		if(in_array($search_by, self::$fields))
			$search_string = $this->getDatabaseHandler()->fieldName($search_by) . ' = ' . $this->getDatabaseHandler()->quote($search_text);
		else
		    throw new InvalidArgumentException('Wrong Account search_by type.');
		$fieldsArray = array();
		foreach(self::$fields as $fieldName)
			$fieldsArray[$fieldName] = $this->getDatabaseHandler()->fieldName($fieldName);
		$this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
	}

	public function loadById($id)
	{
		$this->load($id, 'id');
	}

	public function loadByName($name)
	{
		$this->load($name, 'name');
	}

	public function loadByEmail($mail)
	{
		$this->load($mail, 'email');
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

	public function getPlayers($forceReload = false)
	{
		if(!isset($this->players) || $forceReload)
		{
			$this->players = new DatabaseList('Player');
			$this->players->setFilter(new SQL_Filter(new SQL_Field('account_id'), SQL_Filter::EQUAL, $this->getID()));
			$this->players->addOrder(new SQL_Order(new SQL_Field('name')));
		}
		return $this->players;
	}

	public function unban()
	{
        $this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName('account_bans') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('account_id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));

        unset($this->bans);
	}

	public function loadBans($forceReload = false)
	{
		if(!isset($this->bans) || $forceReload)
		{
			$this->bans = new DatabaseList('AccountBan');
			$filter = new SQL_Filter(new SQL_Field('account_id'), SQL_Filter::EQUAL, $this->data['id']);
			$this->bans->setFilter($filter);
		}
	}

	public function isBanned($forceReload = false)
	{
		$this->loadBans($forceReload);
		return count($this->bans) > 0;
	}

	public function getBanTime($forceReload = false)
	{
		$this->loadBans($forceReload);
		$lastExpires = 0;
		foreach($this->bans as $ban)
		{
			if($ban->getExpiresAt() <= 0)
			{
				$lastExpires = 0;
				break;
			}
			if($ban->getExpiresAt() > time() && $ban->getExpiresAt() > $lastExpires)
				$lastExpires = $ban->getExpiresAt();
		}
		return $lastExpires;
	}

    public function getPlayerTrades($forceReload = false)
    {
        if (!isset($this->playerTrades) || $forceReload) {
            $this->playerTrades = new DatabaseList('PlayerTrade');
            $filter = new SQL_Filter(new SQL_Field('account_seller_id'), SQL_Filter::EQUAL, $this->getID());
            $this->playerTrades->setFilter($filter);
            $this->playerTrades->addOrder(new SQL_Order(new SQL_Field('status')));
        }
        return $this->playerTrades;
    }

    public function delete()
    {
        $this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));

        unset($this->data['id']);
    }

	public function setID($value){$this->data['id'] = $value;}
	public function getID(){return $this->data['id'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setPassword($value)
	{
		$this->data['password'] = Website::encryptPassword($value, $this);
	}
	public function getPassword(){return $this->data['password'];}
    public function setPremiumEndsAt($value){$this->data['premium_ends_at'] = $value;}
    public function getPremiumEndsAt(){return $this->data['premium_ends_at'];}
	public function setMail($value){$this->data['email'] = $value;}
	public function getMail(){return $this->data['email'];}
	public function setKey($value){$this->data['key'] = $value;}
	public function getKey(){return $this->data['key'];}
/*
 * Custom AAC fields
 * create_ip , INT, default 0
 * premium_points , INT, default 0
 * page_access, INT, default 0
 * location, VARCHAR(255), default ''
 * rlname, VARCHAR(255), default ''
*/
	public function setCreateIP($value){$this->data['create_ip'] = $value;}
	public function getCreateIP(){return $this->data['create_ip'];}
	public function setCreateDate($value){$this->data['creation'] = $value;}
	public function getCreateDate(){return $this->data['creation'];}
	public function setPremiumPoints($value){$this->data['premium_points'] = $value;}
	public function getPremiumPoints(){return $this->data['premium_points'];}
	public function setPageAccess($value){$this->data['page_access'] = $value;}
	public function getPageAccess(){return $this->data['page_access'];}
	
	public function setLocation($value){$this->data['location'] = $value;}
	public function getLocation(){return $this->data['location'];}
	public function setRLName($value){$this->data['rlname'] = $value;}
	public function getRLName(){return $this->data['rlname'];}
	public function setFlag($value){$this->data['flag'] = $value;}
	public function getFlag(){return $this->data['flag'];}
/*
 * for compability with old scripts
*/
	public function getEMail(){return $this->getMail();}
	public function setEMail($value){$this->setMail($value);}
	public function getPlayersList(){return $this->getPlayers();}

	public function isValidPassword($password)
	{
		return ($this->data['password'] == Website::encryptPassword($password, $this));
	}

	public function find($name){$this->loadByName($name);}
	public function findByEmail($email){$this->loadByEmail($email);}
    public function isPremium(){return ($this->getPremiumEndsAt() > time());}
	public function getLastLogin(){return 0;}
    public function getPremDays()
    {
        return max(0, ceil(($this->getPremiumEndsAt() - time()) / 86400));
    }
}
