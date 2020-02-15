<?php
if(!defined('INITIALIZED'))
	exit;

class Player extends ObjectData
{
	const LOADTYPE_ID = 'player_id';
	const LOADTYPE_ACCOUNT_ID = 'account_id';
	public static $table = 'players';
	public $data = array('name' => null, );
	public static $fields = array('player_id', 'charname', 'account_id', 'account_nr', 'creation', 'lastlogin', 'gender', 'online', 'vocation', 'hideprofile', 'playerdelete', 'level', 'residence', 'oldname', 'comment', 'charIP');
	public $account;
	public static $onlineList;

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
			new Error_Critic('', 'Wrong Player search_by type.');
		$fieldsArray = array();
		foreach(self::$fields as $fieldName)
			$fieldsArray[] = $this->getDatabaseHandler()->fieldName($fieldName);

		$this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
	}

	public function loadById($id)
	{
		$this->load($id, self::LOADTYPE_ID);
	}

	public function loadByName($name)
	{
		$this->load($name, self::LOADTYPE_NAME);
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
				$updates[] = $this->getDatabaseHandler()->fieldName($key) . ' = ' . $this->getDatabaseHandler()->quote($this->data[$key]);
			$this->getDatabaseHandler()->query('UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) . ' SET ' . implode(', ', $updates) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));
		}
	}
/*
	public function getSkill($id)
	{
		if(isset(self::$skillNames[$id]))
			return $this->data['skill_' . self::$skillNames[$id]];
		else
			new Error_Critic('', 'Player::getSkill() - Skill ' . htmlspecialchars($id) . ' does not exist');
	}

	public function setSkill($id, $value)
	{
		if(isset(self::$skillNames[$id]))
			$this->data['skill_' . self::$skillNames[$id]] = $value;
	}

	public function getSkillCount($id)
	{
		if(isset(self::$skillNames[$id]))
			return $this->data['skill_' . self::$skillNames[$id] . '_tries'];
		else
			new Error_Critic('', 'Player::getSkillCount() - Skill ' . htmlspecialchars($id) . ' does not exist');
	}

	public function setSkillCount($id, $count)
	{
		if(isset(self::$skillNames[$id]))
			$this->data['skill_' . self::$skillNames[$id] . '_tries'] = $value;
	}
*/
	public function loadAccount()
	{
		$this->account = new Account($this->getAccountID());
	}

	public function getAccount($forceReload = false)
	{
		if(!isset($this->account) || $forceReload)
			$this->loadAccount();

		return $this->account;
	}

	public function setAccount(Account $account)
	{
		$this->account = $account;
        $this->setAccountID($account->getID());
        $this->setAccountNr($account->getLogin());
	}

	public function delete()
	{
        $this->db->query('UPDATE ' . $this->getDatabaseHandler()->tableName(self::$table) . ' SET ' . $this->getDatabaseHandler()->fieldName('deleted') . ' = 1 WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));

        unset($this->data['id']);
	}
/*
 * default tfs 0.3.6 fields
*/
	public function setID($value){$this->data['id'] = $value;}
	public function getID(){return $this->data['id'];}
	public function setAccountID($value){$this->data['account_id'] = $value;}
	public function getAccountID(){return $this->data['account_id'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setVocation($value){$this->data['vocation'] = $value;}
	public function getVocation(){return $this->data['vocation'];}
	public function setLevel($value){$this->data['level'] = $value;}
	public function getLevel(){return $this->data['level'];}
	public function setExperience($value){$this->data['experience'] = $value;}
	public function getExperience(){return $this->data['experience'];}
	public function setHealth($value){$this->data['health'] = $value;}
	public function getHealth(){return $this->data['health'];}
	public function setHealthMax($value){$this->data['healthmax'] = $value;}
	public function getHealthMax(){return $this->data['healthmax'];}
	public function setMana($value){$this->data['mana'] = $value;}
	public function getMana(){return $this->data['mana'];}
	public function setManaMax($value){$this->data['manamax'] = $value;}
	public function getManaMax(){return $this->data['manamax'];}
	public function setMagLevel($value){$this->data['maglevel'] = $value;}
	public function getMagLevel(){return $this->data['maglevel'];}
	public function setManaSpent($value){$this->data['manaspent'] = $value;}
	public function getManaSpent(){return $this->data['manaspent'];}
	public function setSex($value){$this->data['sex'] = $value;}
	public function getSex(){return $this->data['sex'];}
	public function setTown($value){$this->data['town_id'] = $value;}
	public function getTown(){return $this->data['town_id'];}
	public function setPosX($value){$this->data['posx'] = $value;}
	public function getPosX(){return $this->data['posx'];}
	public function setPosY($value){$this->data['posy'] = $value;}
	public function getPosY(){return $this->data['posy'];}
	public function setPosZ($value){$this->data['posz'] = $value;}
	public function getPosZ(){return $this->data['posz'];}
	public function setCapacity($value){$this->data['cap'] = $value;}
	public function getCapacity(){return $this->data['cap'];}
	public function setSoul($value){$this->data['soul'] = $value;}
	public function getSoul(){return $this->data['soul'];}
	public function setConditions($value){$this->data['conditions'] = $value;}
	public function getConditions(){return $this->data['conditions'];}
	public function setLastIP($value){$this->data['lastip'] = $value;}
	public function getLastIP(){return $this->data['lastip'];}
	public function setLastLogin($value){$this->data['lastlogin'] = $value;}
	public function getLastLogin(){return $this->data['lastlogin'];}
	public function setLastLogout($value){$this->data['lastlogout'] = $value;}
	public function getLastLogout(){return $this->data['lastlogout'];}
	public function setSkull($value){$this->data['skull'] = $value;}
	public function getSkull(){return $this->data['skull'];}
	public function setSkullTime($value){$this->data['skulltime'] = $value;}
	public function getSkullTime(){return $this->data['skulltime'];}
	public function setSave($value = 1){$this->data['save'] = (int) $value;}
	public function getSave(){return $this->data['save'];}
	public function setBlessings($value){$this->data['blessings'] = $value;}
	public function getBlessings(){return $this->data['blessings'];}
	public function setBalance($value){$this->data['balance'] = $value;}
	public function getBalance(){return $this->data['balance'];}
	public function setStamina($value){$this->data['stamina'] = $value;}
	public function getStamina(){return $this->data['stamina'];}
	public function setDeleted($value){$this->data['deleted'] = (int) $value;}
	public function isDeleted(){return (bool) $this->data['deleted'];}
	public function setLookBody($value){$this->data['lookbody'] = $value;}
	public function getLookBody(){return $this->data['lookbody'];}
	public function setLookFeet($value){$this->data['lookfeet'] = $value;}
	public function getLookFeet(){return $this->data['lookfeet'];}
	public function setLookHead($value){$this->data['lookhead'] = $value;}
	public function getLookHead(){return $this->data['lookhead'];}
	public function setLookLegs($value){$this->data['looklegs'] = $value;}
	public function getLookLegs(){return $this->data['looklegs'];}
	public function setLookType($value){$this->data['looktype'] = $value;}
	public function getLookType(){return $this->data['looktype'];}
	public function setLookAddons($value){$this->data['lookaddons'] = $value;}
	public function getLookAddons(){return $this->data['lookaddons'];}
/*
 * Custom AAC fields
 * create_ip , INT, default 0
 * create_date , INT, default 0
 * hide_char , INT, default 0
 * comment , TEXT, default ''
*/
	public function setCreateIP($value){$this->data['create_ip'] = $value;}
	public function getCreateIP(){return $this->data['create_ip'];}
	public function setCreateDate($value){$this->data['create_date'] = $value;}
	public function getCreateDate(){return $this->data['create_date'];}
	public function setHidden($value){$this->data['hide_char'] = (int) $value;}
	public function isHidden(){return (bool) $this->data['hide_char'];}
	public function setComment($value){$this->data['comment'] = $value;}
	public function getComment(){return $this->data['comment'];}
/*
 * for compability with old scripts
*/
	public function setGroup($value){$this->setGroupID($value);}
	public function getGroup(){return $this->getGroupID();}
	public function getCreated(){return $this->getCreateDate();}
	public function setCreated($value){$this->setCreateDate($value);}
	public function setCap($value){$this->setCapacity($value);}
	public function getCap(){return $this->getCapacity();}
	public function isSaveSet(){return $this->getSave();}
	public function unsetSave(){$this->setSave(0);}
	public function getTownId(){return $this->getTown();}
	public function getHideChar(){return $this->isHidden();}
	public function find($name){$this->loadByName($name);}

	public static function isPlayerOnline($playerID)
	{
		if(!isset(self::$onlineList))
		{
			self::$onlineList = array();
			$onlines = Website::getDBHandle()->query('SELECT ' . Website::getDBHandle()->fieldName('player_id') . ' FROM ' . Website::getDBHandle()->tableName('players_online'))->fetchAll();
			foreach($onlines as $online)
			{
				self::$onlineList[$online['player_id']] = $online['player_id'];
			}
		}

		return isset(self::$onlineList[$playerID]);
	}

	public function isOnline()
	{
		return self::isPlayerOnline($this->getID());
	}

	public function getOnline()
	{
		return self::isPlayerOnline($this->getID());
	}
}
