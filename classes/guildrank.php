<?php
if(!defined('INITIALIZED'))
	exit;

class GuildRank extends ObjectData
{
	const LEVEL_MEMBER = 1;
	const LEVEL_VICE = 2;
	const LEVEL_LEADER = 3;
	public static $ranksList = array(self::LEVEL_MEMBER, self::LEVEL_VICE, self::LEVEL_LEADER);
	public static $ranksNamesList = array(self::LEVEL_MEMBER => 'Member', self::LEVEL_VICE => 'Vice Leader', self::LEVEL_LEADER => 'Leader');
	public static $table = 'guild_ranks';
	public $data = array('guild_id' => null, 'name' => null, 'level' => null,);
	public static $fields = array('id', 'guild_id', 'name', 'level');
	public $members;
	public $guild;

    public function __construct($rankId = null)
    {
		if($rankId != null)
			$this->load($rankId);
    }

	public function load($id)
	{
		$fieldsArray = array();
		foreach(self::$fields as $fieldName)
			$fieldsArray[] = $this->getDatabaseHandler()->fieldName($fieldName);

		$this->data = $this->getDatabaseHandler()->query('SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($id))->fetch();
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
			foreach($this->getMembers(true) as $member)
			{
				$member->setRank();
				$member->save();
			}
			$this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName(self::$table) . ' WHERE ' . $this->getDatabaseHandler()->fieldName('id') . ' = ' . $this->getDatabaseHandler()->quote($this->data['id']));
			$_tmp = new self();
			$this->data = $_tmp->data;
			$this->members = $_tmp->members;
			$this->guild = $_tmp->guild;
			unset($_tmp);
		}
		else
			new Error_Critic('', __METHOD__ . '() - cannot delete, guild rank not loaded');
	}

	public function getMembers($forceReload = false)
	{
		if(!isset($this->members) || $forceReload)
		{
			$members = new DatabaseList('Player');
			$filterGuild = new SQL_Filter(new SQL_Field('rank_id', 'guild_membership'), SQL_Filter::EQUAL, $this->getID());
			$filterPlayer = new SQL_Filter(new SQL_Field('id', 'players'), SQL_Filter::EQUAL, new SQL_Field('player_id', 'guild_membership'));
			$members->setFilter(new SQL_Filter($filterGuild, SQL_Filter::CRITERIUM_AND, $filterPlayer));
			$members->addOrder(new SQL_Order(new SQL_Field('name', 'players')));
			$this->members = $members;
		}
		return $this->members;


	}

	public function getGuild($forceReload = false)
	{
		if(!isset($this->guild) || $forceReload)
			$this->guild = new Guild($this->getGuildID());

		return $this->guild;
	}

	public function setGuild($guild)
	{
		$this->guild = $guild;
		$this->setGuildID($guild->getID());
	}

	public function getID(){return $this->data['id'];}
	public function setID($value){$this->data['id'] = $value;}
	public function getGuildID(){return $this->data['guild_id'];}
	public function setGuildID($value){$this->data['guild_id'] = $value;}
	public function getName(){return $this->data['name'];}
	public function setName($value){$this->data['name'] = $value;}
	public function getLevel(){return $this->data['level'];}
	public function setLevel($value){$this->data['level'] = $value;}
/*
 * for compability with old scripts
*/
	public function getPlayers(){return $this->getMembers();}
	public function getPlayersList(){return $this->getMembers();}
}