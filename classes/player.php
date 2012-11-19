<?php
if(!defined('INITIALIZED'))
	exit;

class Player extends ObjectData
{
	const LOADTYPE_ID = 'id';
	const LOADTYPE_NAME = 'name';
	const LOADTYPE_ACCOUNT_ID = 'account_id';
	public static $table = 'players';
	public $data = array('name' => null, 'world_id' => null, 'group_id' => null, 'account_id' => null, 'level' => null, 'vocation' => null, 'health' => null, 'healthmax' => null, 'experience' => null, 'lookbody' => null, 'lookfeet' => null, 'lookhead' => null, 'looklegs' => null, 'looktype' => null, 'lookaddons' => null, 'maglevel' => null, 'mana' => null, 'manamax' => null, 'manaspent' => null, 'soul' => null, 'town_id' => null, 'posx' => null, 'posy' => null, 'posz' => null, 'conditions' => null, 'cap' => null, 'sex' => null, 'lastlogin' => null, 'lastip' => null, 'save' => null, 'skull' => null, 'skulltime' => null, 'rank_id' => null, 'guildnick' => null, 'lastlogout' => null, 'blessings' => null, 'balance' => null, 'stamina' => null, 'direction' => null, 'loss_experience' => null, 'loss_mana' => null, 'loss_skills' => null, 'loss_containers' => null, 'loss_items' => null, 'premend' => null, 'online' => null, 'marriage' => null, 'promotion' => null, 'deleted' => null, 'description' => null, 'create_ip' => null, 'create_date' => null, 'comment' => null, 'hide_char' => null);
	public static $fields = array('id', 'name', 'world_id', 'group_id', 'account_id', 'level', 'vocation', 'health', 'healthmax', 'experience', 'lookbody', 'lookfeet', 'lookhead', 'looklegs', 'looktype', 'lookaddons', 'maglevel', 'mana', 'manamax', 'manaspent', 'soul', 'town_id', 'posx', 'posy', 'posz', 'conditions', 'cap', 'sex', 'lastlogin', 'lastip', 'save', 'skull', 'skulltime', 'rank_id', 'guildnick', 'lastlogout', 'blessings', 'balance', 'stamina', 'direction', 'loss_experience', 'loss_mana', 'loss_skills', 'loss_containers', 'loss_items', 'premend', 'online', 'marriage', 'promotion', 'deleted', 'description', 'create_ip', 'create_date', 'comment', 'hide_char');
	public static $skillFields = array('player_id', 'skillid', 'value',	'count');
	public $items;
	public $storages;
	public $skills;
	public $account;
	public $rank;

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

	public function getItems($forceReload = false)
	{
		if(!isset($this->items) || $forceReload)
			$this->items = new ItemsList($this->getID());

		return $this->items;
	}

	public function saveItems()
	{
		if(isset($this->items))
		{
			// if any script changed ID of player, function should save items with new player id
			$this->items->setPlayerId($this->getID());
			$this->items->save();
		}
		else
			new Error_Critic('', 'Player::saveItems() - items not loaded, cannot save');
	}

	public function loadStorages()
	{
		$this->storages = array();
		// load all
		$storages = $this->getDatabaseHandler()->query('SELECT ' . $this->getDatabaseHandler()->fieldName('player_id') . ', ' . $this->getDatabaseHandler()->fieldName('key') . 
			', ' . $this->getDatabaseHandler()->fieldName('value') . ' FROM ' .$this->getDatabaseHandler()->tableName('player_storage') .
			' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']))->fetchAll();
		foreach($storages as $storage)
		{
			$this->storages[$storage['key']] = $storage['value'];
		}
	}

	public function saveStorages()
	{
		if(isset($this->storages))
		{
			$this->getDatabaseHandler()->query('DELETE FROM ' .$this->getDatabaseHandler()->tableName('player_storage') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));
			foreach($this->storages as $key => $value)
			{
				//save each
				$this->getDatabaseHandler()->query('INSERT INTO ' . $this->getDatabaseHandler()->tableName('player_storage') . ' (' . $this->getDatabaseHandler()->fieldName('player_id') . ', ' . 
					$this->getDatabaseHandler()->fieldName('key') . ', ' . $this->getDatabaseHandler()->fieldName('value') . ', ) VALUES (' . 
					$this->getDatabaseHandler()->quote($this->data['id']) . ', ' . $this->getDatabaseHandler()->quote($key) . ', ' . $this->getDatabaseHandler()->quote($value) . ')');
			}
		}
		else
			new Error_Critic('', 'Player::saveStorages() - storages not loaded, cannot save');
	}

	public function getStorage($key)
	{
		if(!isset($this->storages))
		{
			$this->loadStorages();
		}
		if(isset($this->storages[$key]))
			return $this->storages[$key];
		else
			return null;
	}

	public function getStorages()
	{
		if(!isset($this->storages))
		{
			$this->loadStorages();
		}
		return $this->storages;
	}

	public function setStorage($key, $value)
	{
		if(!isset($this->storages))
		{
			$this->loadStorages();
		}
		$this->storages[$key] = $value;
	}

	public function removeStorage($key)
	{
		if(!isset($this->storages))
		{
			$this->loadStorages();
		}
		if(isset($this->storages[$key]))
			unset($this->storages[$key]);
	}

	public function loadSkills()
	{
		$fieldsArray = array();
		foreach(self::$skillFields as $fieldName)
			$fieldsArray[] = $this->getDatabaseHandler()->fieldName($fieldName);

		$skills = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->fieldName('player_skills') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($this->getID()))->fetchAll();
		$this->skills = array();
		foreach($skills as $skill)
			$this->skills[$skill['skillid']] = $skill;
	}

	public function getSkills($forceReload = false)
	{
		if(!isset($this->skills) || $forceReload)
			$this->loadSkills();

		return $this->skills;
	}

	public function getSkill($id, $forceReload = false)
	{
		if(!isset($this->skills) || $forceReload)
			$this->loadSkills();

		if(isset($this->skills[$id]))
			return $this->skills[$id]['value'];
		else
			new Error_Critic('', 'Player::getSkill() - Skill ' . htmlspecialchars($id) . ' does not exist');
	}

	public function setSkill($id, $value)
	{
		$this->skills[$id]['value'] = $value;
	}

	public function getSkillCount($id, $forceReload = false)
	{
		if(!isset($this->skills) || $forceReload)
			$this->loadSkills();

		if(isset($this->skills[$id]))
			return $this->skills[$id]['count'];
		else
			new Error_Critic('', 'Player::getSkillCount() - Skill ' . htmlspecialchars($id) . ' does not exist');
	}

	public function setSkillCount($id, $count)
	{
		$this->skills[$id]['count'] = $count;
	}

	public function saveSkills()
	{
		if(isset($this->skills))
		{
			$this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName('player_skills') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($this->getID()));

			if(count($this->skills) > 0)
			{
				$keys = array();
				foreach(self::$skillFields as $key)
					$keys[] = $this->getDatabaseHandler()->fieldName($key);

				$query = 'INSERT INTO ' . $this->getDatabaseHandler()->tableName('player_skills') . ' (' . implode(', ', $keys) . ') VALUES ';
				foreach($this->skills as $skill)
				{
					$fieldValues = array();
					foreach(self::$skillFields as $key)
						if($key != 'player_id')
							$fieldValues[] = $this->getDatabaseHandler()->quote($skill[$key]);
						else
							$fieldValues[] = $this->getDatabaseHandler()->quote($this->getID());
					$this->getDatabaseHandler()->query($query . '(' . implode(', ', $fieldValues) . ')');
				}
			}
		}
		else
			new Error_Critic('', 'Player::saveSkills() - skills not loaded, cannot save');
	}

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

	public function setAccount($account)
	{
		$this->account = $account;
		$this->setAccountID($account->getID());
	}

	public function loadRank()
	{
		$this->rank = new GuildRank($this->getRankID());
	}

	public function getRank($forceReload = false)
	{
		if(!isset($this->rank) || $forceReload)
			$this->loadRank();

        if($this->data['rank_id'] == 0)
        {
            return null;
        }

		return $this->rank;
	}

	public function setRank($rank = null)
	{
		if(isset($rank))
		{
			$this->rank = $rank;
			$this->setRankID($rank->getID());
		}
		else
		{
			$this->rank = new GuildRank();
			$this->setRankID(0);
		}
	}

	public function hasGuild()
	{
		return $this->getRank()->isLoaded();
	}

	public function removeGuildInvitations()
	{
		$this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName('guild_invites') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($this->getID()));
	}

	public function unban()
	{
		$bans = new DatabaseList('Ban');
		$filterType = new SQL_Filter(new SQL_Field('type'), SQL_Filter::EQUAL, Ban::TYPE_PLAYER);
		$filterValue = new SQL_Filter(new SQL_Field('value'), SQL_Filter::EQUAL, $this->data['id']);
		$filterActive = new SQL_Filter(new SQL_Field('active'), SQL_Filter::EQUAL, 1);
		$filter = new SQL_Filter($filterType, SQL_Filter::CRITERIUM_AND, $filterValue);
		$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, $filterActive);
		$bans->setFilter($filter);
		foreach($bans as $ban)
		{
			$ban->setActive(0);
			$ban->save();
		}
	}

	public function isBanned()
	{
		$bans = new DatabaseList('Ban');
		$filterType = new SQL_Filter(new SQL_Field('type'), SQL_Filter::EQUAL, Ban::TYPE_PLAYER);
		$filterParam = new SQL_Filter(new SQL_Field('param'), SQL_Filter::EQUAL, Ban::PLAYERBAN_BANISHMENT);
		$filterValue = new SQL_Filter(new SQL_Field('value'), SQL_Filter::EQUAL, $this->data['id']);
		$filterActive = new SQL_Filter(new SQL_Field('active'), SQL_Filter::EQUAL, 1);
		$filter = new SQL_Filter($filterType, SQL_Filter::CRITERIUM_AND, $filterValue);
		$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, $filterActive);
		$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, $filterParam);
		$bans->setFilter($filter);
		$isBanned = false;
		foreach($bans as $ban)
		{
			if($ban->getExpires() <= 0 || $ban->isExpires() > time())
				$isBanned = true;
		}
		return $isBanned;
	}

	public function isNamelocked()
	{
		$bans = new DatabaseList('Ban');
		$filterType = new SQL_Filter(new SQL_Field('type'), SQL_Filter::EQUAL, Ban::TYPE_PLAYER);
		$filterParam = new SQL_Filter(new SQL_Field('param'), SQL_Filter::EQUAL, Ban::PLAYERBAN_LOCK);
		$filterValue = new SQL_Filter(new SQL_Field('value'), SQL_Filter::EQUAL, $this->data['id']);
		$filterActive = new SQL_Filter(new SQL_Field('active'), SQL_Filter::EQUAL, 1);
		$filter = new SQL_Filter($filterType, SQL_Filter::CRITERIUM_AND, $filterValue);
		$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, $filterActive);
		$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, $filterParam);
		$bans->setFilter($filter);
		return (count($bans) > 0);
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
	public function setWorldID($value){$this->data['world_id'] = $value;}
	public function getWorldID(){return $this->data['world_id'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setGroupID($value){$this->data['group_id'] = $value;}
	public function getGroupID(){return $this->data['group_id'];}
	public function setVocation($value){$this->data['vocation'] = $value;}
	public function getVocation(){return $this->data['vocation'];}
	public function setPromotion($value){$this->data['promotion'] = $value;}
	public function getPromotion(){return $this->data['promotion'];}
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
	public function setRankID($value){$this->data['rank_id'] = $value;}
	public function getRankID(){return $this->data['rank_id'];}
	public function setGuildNick($value){$this->data['guildnick'] = $value;}
	public function getGuildNick(){return $this->data['guildnick'];}
	public function setSave($value = 1){$this->data['save'] = (int) $value;}
	public function getSave(){return $this->data['save'];}
	public function setBlessings($value){$this->data['blessings'] = $value;}
	public function getBlessings(){return $this->data['blessings'];}
	public function setBalance($value){$this->data['balance'] = $value;}
	public function getBalance(){return $this->data['balance'];}
	public function setStamina($value){$this->data['stamina'] = $value;}
	public function getStamina(){return $this->data['stamina'];}
	public function setDirection($value){$this->data['direction'] = $value;}
	public function getDirection(){return $this->data['direction'];}
	public function setLossExperience($value){$this->data['loss_experience'] = $value;}
	public function getLossExperience(){return $this->data['loss_experience'];}
	public function setLossMana($value){$this->data['loss_mana'] = $value;}
	public function getLossMana(){return $this->data['loss_mana'];}
	public function setLossSkills($value){$this->data['loss_skills'] = $value;}
	public function getLossSkills(){return $this->data['loss_skills'];}
	public function setLossContainers($value){$this->data['loss_containers'] = $value;}
	public function getLossContainers(){return $this->data['loss_containers'];}
	public function setLossItems($value){$this->data['loss_items'] = $value;}
	public function getLossItems(){return $this->data['loss_items'];}
	public function setOnline($value){$this->data['online'] = (int) $value;}
	public function getOnline(){return (bool) $this->data['online'];}
	public function setMarriage($value){$this->data['marriage'] = $value;}
	public function getMarriage(){return $this->data['marriage'];}
	public function setDeleted($value){$this->data['deleted'] = (int) $value;}
	public function isDeleted(){return (bool) $this->data['deleted'];}
	public function setDescription($value){$this->data['description'] = $value;}
	public function getDescription(){return $this->data['description'];}
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
	public function setWorld($value){$this->setWorldID($value);}
	public function getWorld(){return $this->getWorldID();}
	public function isOnline(){return $this->getOnline() == 1;}
	public function getCreated(){return $this->getCreateDate();}
	public function setCreated($value){$this->setCreateDate($value);}
	public function setCap($value){$this->setCapacity($value);}
	public function getCap(){return $this->getCapacity();}
	public function isSaveSet(){return $this->getSave();}
	public function unsetSave(){$this->setSave(0);}
	public function getTownId(){return $this->getTown();}
	public function getHideChar(){return $this->isHidden();}
	public function find($name){$this->loadByName($name);}
}