<?php
if(!defined('INITIALIZED'))
	exit;

class Guild extends ObjectData
{
	const LOADTYPE_ID = 'id';
	const LOADTYPE_NAME = 'name';

	const LEVEL_NOT_IN_GUILD = 0;
	const LEVEL_MEMBER = 1;
	const LEVEL_VICE = 2;
	const LEVEL_LEADER = 3;
	const LEVEL_OWNER = 4;
	public static $table = 'guilds';
	public $data = array('name' => null, 'ownerid' => null, 'creationdata' => null, 'motd' => null, 'description' => null, 'create_ip' => null, 'guild_logo' => null);
	public static $fields = array('id',  'name', 'ownerid', 'creationdata', 'motd', 'description', 'create_ip', 'guild_logo');
	public $invitedPlayers;
	public $ranks;
	public $owner;

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
			new Error_Critic('', 'Wrong guild search_by type.');
		$fieldsArray = array();
		foreach(self::$fields as $fieldName)
			$fieldsArray[] = $this->getDatabaseHandler()->fieldName($fieldName);

		$this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $search_string)->fetch();
	}

	public function loadById($id)
	{
		return $this->load($id, self::LOADTYPE_ID);
	}

	public function loadByName($name)
	{
		return $this->load($name, self::LOADTYPE_NAME);
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

	public function delete()
	{
		if($this->isLoaded())
		{
			foreach($this->getRanks(true) as $rank)
			{
				$rank->delete();
			}
			$this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));
			$_tmp = new self();
			$this->data = $_tmp->data;
			$this->invitedPlayers = $_tmp->invitedPlayers;
			$this->ranks = $_tmp->ranks;
			$this->owner = $_tmp->owner;
			unset($_tmp);
		}
		else
			new Error_Critic('', __METHOD__ . '() - cannot delete, guild not loaded');
	}

	public function kickPlayer($playerId)
	{
		if($playerId == $this->getOwnerID())
			$this->delete();
		else
		{
			$player = new Player($playerId);
			if($player->isLoaded())
			{
				$player->setRank();
				$player->save();
			}
		}
	}

	public function getInvitedPlayers($forceReload = false)
	{
		return $this->getInvitations($forceReload);
	}

	public function getInvitations($forceReload = false)
	{
		if(!isset($this->invitedPlayers) || $forceReload)
		{
			$invitedPlayers = new DatabaseList('Player');
			$filterGuild = new SQL_Filter(new SQL_Field('guild_id', 'guild_invites'), SQL_Filter::EQUAL, $this->getID());
			$filterPlayer = new SQL_Filter(new SQL_Field('id', 'players'), SQL_Filter::EQUAL, new SQL_Field('player_id', 'guild_invites'));
			$invitedPlayers->setFilter(new SQL_Filter($filterGuild, SQL_Filter::CRITERIUM_AND, $filterPlayer));
			$invitedPlayers->addOrder(new SQL_Order(new SQL_Field('name', 'players')));
			$this->invitedPlayers = $invitedPlayers;
		}
		return $this->invitedPlayers;
	}

	public function addInvitation($playerId, $reloadInvites = false)
	{
		$this->getDatabaseHandler()->query('INSERT INTO ' . $this->getDatabaseHandler()->tableName('guild_invites') . ' (' . $this->getDatabaseHandler()->fieldName('player_id') . ', ' . $this->getDatabaseHandler()->fieldName('guild_id') . ') VALUES (' . $this->getDatabaseHandler()->quote($playerId) . ', ' . $this->getDatabaseHandler()->quote($this->getID()) . ')');
		if($reloadInvites)
			$this->getInvitations(true);
	}

	public function removeInvitation($playerId)
	{
		$this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName('guild_invites') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($playerId) . ' AND ' . $this->getDatabaseHandler()->fieldName('guild_id') . ' = ' . $this->getDatabaseHandler()->quote($this->getID()));
	}

	public function isInvited($playerId, $forceReload = false)
	{
		foreach($this->getInvitations($forceReload) as $invitedPlayer)
			if($invitedPlayer->getID() == $playerId)
				return true;

		return false;
	}

	public function getRanks($forceReload = false)
	{
		if(!isset($this->ranks) || $forceReload)
		{
			$ranks = new DatabaseList('GuildRank');
			$ranks->setFilter(new SQL_Filter(new SQL_Field('guild_id'), SQL_Filter::EQUAL, $this->getID()));
			$ranks->addOrder(new SQL_Order(new SQL_Field('level'), SQL_Order::DESC));
			$this->ranks = $ranks;
		}

		return $this->ranks;
	}

	public function getOwner($forceReload = false)
	{
		if(!isset($this->owner) || $forceReload)
			$this->owner = new Player($this->getOwnerID());

		return $this->owner;
	}

	public function setOwner($owner)
	{
		$this->owner = $owner;
		$this->setOwnerID($owner->getID());
	}

	public function getGuildLogoLink()
	{
		return 'guild_image.php?id=' . $this->getID();
	}

	public function getID(){return $this->data['id'];}
	public function setID($value){$this->data['id'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getOwnerID(){return $this->data['ownerid'];}
	public function setOwnerID($value){$this->data['ownerid'] = $value;}
	public function getCreationData(){return $this->data['creationdata'];}
	public function setCreationData($value){$this->data['creationdata'] = $value;}
	public function getMOTD(){return $this->data['motd'];}
	public function setMOTD($value){$this->data['motd'] = $value;}
/*
 * Custom AAC fields
 * create_ip , INT, default 0
 * description , TEXT, default ''
 * guild_logo, MEDIUMBLOB, default NULL
*/
	public function setCreateIP($value){$this->data['create_ip'] = $value;}
	public function getCreateIP(){return $this->data['create_ip'];}
	public function getDescription(){return $this->data['description'];}
	public function setDescription($value){$this->data['description'] = $value;}
	public function getGuildLogo()
	{
		return $this->data['guild_logo'];
	}

	public function setGuildLogo($mimeType, $fileData)
	{
		$this->data['guild_logo'] = time() . ';data:' . $mimeType . ';base64,' . base64_encode($fileData);
	}
/*
 * for compability with old scripts
*/
	public function setCreateDate($value){$this->data['creationdata'] = $value;}
	public function getCreateDate(){return $this->data['creationdata'];}
	public function getGuildRanksList(){return $this->getRanks();}
	public function getGuildRanks(){return $this->getRanks();}
	public function listInvites(){return $this->getInvitations();}
	public function invite($player){$this->addInvitation($player->getID());}
	public function deleteInvite($player){$this->removeInvitation($player->getID());}
	public function acceptInvite($player)
	{
		$ranks = new DatabaseList('GuildRank');
		$ranks->setFilter(new SQL_Filter(new SQL_Field('guild_id'), SQL_Filter::EQUAL, $this->getID()));
		$ranks->addOrder(new SQL_Order(new SQL_Field('level'), SQL_Order::ASC));
		// load rank with lowest access level
		if($rank = $ranks->getResult(0))
		{
			$player->setRank($rank);
			$player->save();
			$player->removeGuildInvitations();
		}
		else
			new Error_Critic('', 'There is no rank in guild <b>' . htmlspecialchars($guild->getName()) . '</b>, cannot add player <b>' . htmlspecialchars($player->getName()) . '</b> to guild.');
	}
	public function find($name){$this->loadByName($name);}
}