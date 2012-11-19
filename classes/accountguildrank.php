<?php
if(!defined('INITIALIZED'))
	exit;

// special class to load ranks of X account by one query
class AccountGuildRank extends GuildRank
{
	public static $fields = array('guild_id', 'name', 'level');
	public static $extraFields = array(array('ownerid', 'guilds'), array('id', 'players'), array('rank_id', 'players'));

	function getID()
	{
		return $this->data['rank_id'];
	}

	function getOwnerID()
	{
		return $this->data['ownerid'];
	}

	function getPlayerID()
	{
		return $this->data['id'];
	}
}