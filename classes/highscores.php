<?php
if(!defined('INITIALIZED'))
	exit;

class Highscores extends DatabaseList
{
	const SKILL_FIST = 'fist';
	const SKILL_CLUB = 'club';
	const SKILL_SWORD = 'sword';
	const SKILL_AXE = 'axe';
	const SKILL_DISTANCE = 'dist';
	const SKILL_SHIELD = 'shielding';
	const SKILL_FISHING = 'fishing';
	const SKILL__MAGLEVEL = 7;
	const SKILL__LEVEL = 8;

	public $highscoreConfig;
	public $skillType;
	public $vocation = '';

	public function __construct($type, $limit = 5, $page = 0, $vocation = '')
	{
		$this->highscoreConfig = Website::getWebsiteConfig();
		parent::__construct();
		$this->skillType = $type;
		$this->setLimit($limit);
		$this->setOffset($page * $limit);
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
		$this->addOrder(new SQL_Order(new SQL_Field('skill_' . $this->skillType), SQL_Order::DESC));
		$this->addOrder(new SQL_Order(new SQL_Field('skill_' . $this->skillType . '_tries'), SQL_Order::DESC));
		$this->addExtraField(new SQL_Field('flag', 'accounts'));
		$this->addExtraField(new SQL_Field('skill_' . $this->skillType, 'players', 'value'));
		$filter = new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::EQUAL, new SQL_Field('id', 'accounts'));

		if($this->vocation != '')
			$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('vocation', 'players'), SQL_Filter::EQUAL, $this->vocation));

		if($this->highscoreConfig->isSetKey('groups_hidden'))
			foreach($this->highscoreConfig->getValue('groups_hidden') as $_group_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('group_id', 'players'), SQL_Filter::NOT_EQUAL, $_group_filter));

		if($this->highscoreConfig->isSetKey('accounts_hidden'))
			foreach($this->highscoreConfig->getValue('accounts_hidden') as $_account_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::NOT_EQUAL, $_account_filter));

		$this->setFilter($filter);
	}

	public function loadMagic()
	{
		$this->setClass('Highscore');
		$this->addOrder(new SQL_Order(new SQL_Field('maglevel'), SQL_Order::DESC));
		$this->addOrder(new SQL_Order(new SQL_Field('manaspent'), SQL_Order::DESC));
		$this->addExtraField(new SQL_Field('flag', 'accounts'));
		$filter = new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::EQUAL, new SQL_Field('id', 'accounts'));

		if($this->vocation != '')
			$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('vocation', 'players'), SQL_Filter::EQUAL, $this->vocation));

		if($this->highscoreConfig->isSetKey('groups_hidden'))
			foreach($this->highscoreConfig->getValue('groups_hidden') as $_group_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('group_id', 'players'), SQL_Filter::NOT_EQUAL, $_group_filter));

		if($this->highscoreConfig->isSetKey('accounts_hidden'))
			foreach($this->highscoreConfig->getValue('accounts_hidden') as $_account_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::NOT_EQUAL, $_account_filter));;

		$this->setFilter($filter);
	}

	public function loadLevel()
	{
		$this->setClass('Highscore');
		$this->addOrder(new SQL_Order(new SQL_Field('experience'), SQL_Order::DESC));
		$this->addExtraField(new SQL_Field('flag', 'accounts'));
		$filter = new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::EQUAL, new SQL_Field('id', 'accounts'));

		if($this->vocation != '')
			$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('vocation', 'players'), SQL_Filter::EQUAL, $this->vocation));

		if($this->highscoreConfig->isSetKey('groups_hidden'))
			foreach($this->highscoreConfig->getValue('groups_hidden') as $_group_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('group_id', 'players'), SQL_Filter::NOT_EQUAL, $_group_filter));

		if($this->highscoreConfig->isSetKey('accounts_hidden'))
			foreach($this->highscoreConfig->getValue('accounts_hidden') as $_account_filter)
				$filter = new SQL_Filter($filter, SQL_Filter::CRITERIUM_AND, new SQL_Filter(new SQL_Field('account_id', 'players'), SQL_Filter::NOT_EQUAL, $_account_filter));

		$this->setFilter($filter);
	}
}
