<?php
if(!defined('INITIALIZED'))
	exit;

class Highscores extends DatabaseList
{
	const SKILL_FIST = 0;
	const SKILL_CLUB = 1;
	const SKILL_SWORD = 2;
	const SKILL_AXE = 3;
	const SKILL_DISTANCE = 4;
	const SKILL_SHIELD = 5;
	const SKILL_FISHING = 6;
	const SKILL__MAGLEVEL = 7;
	const SKILL__LEVEL = 8;

	public $highscoreConfig;
	public $skillType;
	public $worldId;
	public $vocation = '';

	public function __construct($type, $limit = 5, $page = 0, $worldId = 0, $vocation = '')
	{
		$this->highscoreConfig = Website::getWebsiteConfig();
		parent::__construct();
		$this->skillType = $type;
		$this->setLimit($limit);
		$this->setOffset($page * $limit);
		$this->worldId = $worldId;
		$this->vocation = $vocation;
		switch($type)
		{
			case self::SKILL_FIST;
			case self::SKILL_CLUB;
			case self::SKILL_SWORD;
			case self::SKILL_AXE;
			case self::SKILL_DISTANCE;
			case self::SKILL_SHIELD;
			case self::SKILL_FISHING;
				$this->loadSkill();
				break;
			case self::SKILL__MAGLEVEL;
				$this->loadMagic();
				break;
			case self::SKILL__LEVEL;
				$this->loadLevel();
				break;
			default;
				new Error_Critic('', __METHOD__ . '(), unknown type: ' . htmlspecialchars($type));
				break;
		}
	}

	public function loadSkill()
	{
		$this->setClass('Highscore');
		$this->addOrder(new SQL_Order(new SQL_Field('value', 'player_skills'), SQL_Order::DESC));
		$this->addOrder(new SQL_Order(new SQL_Field('count', 'player_skills'), SQL_Order::DESC));
		$this->addExtraField(new SQL_Field('count', 'player_skills'));
		$this->addExtraField(new SQL_Field('value', 'player_skills'));
		$this->addExtraField(new SQL_Field('flag', 'accounts'));
		$filterWorld = new SQL_Filter(new SQL_Field('world_id', 'players'), SQL_Filter::EQUAL, $this->worldId);
		$filterPlayer = new SQL_Filter(new SQL_Field('id', 'players'), SQL_Filter::EQUAL, new SQL_Field('player_id', 'player_skills'));
		$filterSkill = new SQL_Filter(new SQL_Field('skillid', 'player_skills'), SQL_Filter::EQUAL, $this->skillType);
		$filter = new SQL_Filter($filterPlayer, SQL_Filter::CRITERIUM_AND, $filterSkill);

		if($this->highscoreConfig->isSetKey('groups_hidden'))
			foreach($this->highscoreConfig->getValue('groups_hidden') as $_group_filter)
				$filter = new SQL_Filter(new SQL_Filter(new SQL_Field('group_id', 'players'), SQL_Filter::NOT_EQUAL, $_group_filter), SQL_Filter::CRITERIUM_AND, $filter);

		if($this->highscoreConfig->isSetKey('accounts_hidden'))
			foreach($this->highscoreConfig->getValue('accounts_hidden') as $_account_filter)
				$filter = new SQL_Filter(new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::NOT_EQUAL, $_account_filter), SQL_Filter::CRITERIUM_AND, $filter);

		if($this->vocation != '')
			$filter = new SQL_Filter(new SQL_Filter(new SQL_Field('vocation', 'players'), SQL_Filter::EQUAL, $this->vocation), SQL_Filter::CRITERIUM_AND, $filter);

		$filter = new SQL_Filter($filterWorld, SQL_Filter::CRITERIUM_AND, $filter);
		$filter = new SQL_Filter(new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::EQUAL, new SQL_Field('id', 'accounts')), SQL_Filter::CRITERIUM_AND, $filter);
		$this->setFilter($filter);
	}

	public function loadMagic()
	{
		$this->setClass('Highscore');
		$this->addOrder(new SQL_Order(new SQL_Field('maglevel'), SQL_Order::DESC));
		$this->addOrder(new SQL_Order(new SQL_Field('manaspent'), SQL_Order::DESC));
		$this->addExtraField(new SQL_Field('flag', 'accounts'));
		$filter = new SQL_Filter(new SQL_Field('world_id', 'players'), SQL_Filter::EQUAL, $this->worldId);

		if($this->vocation != '')
			$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('vocation', 'players'), SQL_Filter::EQUAL, $this->vocation));

		if($this->highscoreConfig->isSetKey('groups_hidden'))
			foreach($this->highscoreConfig->getValue('groups_hidden') as $_group_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('group_id', 'players'), SQL_Filter::NOT_EQUAL, $_group_filter));

		if($this->highscoreConfig->isSetKey('accounts_hidden'))
			foreach($this->highscoreConfig->getValue('accounts_hidden') as $_account_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::NOT_EQUAL, $_account_filter));

		$filter = new SQL_Filter(new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::EQUAL, new SQL_Field('id', 'accounts')), SQL_Filter::CRITERIUM_AND, $filter);

		$this->setFilter($filter);
	}

	public function loadLevel()
	{
		$this->setClass('Highscore');
		$this->addOrder(new SQL_Order(new SQL_Field('experience'), SQL_Order::DESC));
		$this->addExtraField(new SQL_Field('flag', 'accounts'));
		$filter = new SQL_Filter(new SQL_Field('world_id', 'players'), SQL_Filter::EQUAL, $this->worldId);

		if($this->vocation != '')
			$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('vocation', 'players'), SQL_Filter::EQUAL, $this->vocation));

		if($this->highscoreConfig->isSetKey('groups_hidden'))
			foreach($this->highscoreConfig->getValue('groups_hidden') as $_group_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('group_id', 'players'), SQL_Filter::NOT_EQUAL, $_group_filter));

		if($this->highscoreConfig->isSetKey('accounts_hidden'))
			foreach($this->highscoreConfig->getValue('accounts_hidden') as $_account_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::NOT_EQUAL, $_account_filter));

		$filter = new SQL_Filter(new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::EQUAL, new SQL_Field('id', 'accounts')), SQL_Filter::CRITERIUM_AND, $filter);

		$this->setFilter($filter);
	}
}