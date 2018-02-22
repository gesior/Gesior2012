<?php
if(!defined('INITIALIZED'))
	exit;

class ItemsList extends DatabaseList
{
	private $player_id = 0;

    public function __construct($player_id = 0)
    {
		parent::__construct('Item');
		if($player_id != 0)
		{
			$this->setFilter(new SQL_Filter(new SQL_Field('player_id', 'player_items'), SQL_Filter::EQUAL, $player_id));
			$this->setPlayerId($player_id);
		}
    }

	public function load()
	{
		$this->setClass('Item');
		parent::load();
		if(count($this->data) > 0)
		{
			$_new_items = array();
			$_new_data = array();
			foreach($this->data as $i => $item)
			{
				$_new_items[$i] = new Item($item);
				$_new_data[] = &$_new_items[$i];
			}
			$this->data = $_new_data;
		}
	}

	public function save($deleteCurrentItems = true)
	{
		if(!isset($this->data))
			$this->data = array();
		if($this->player_id != 0)
		{
			if($deleteCurrentItems)
				$this->getDatabaseHandler()->query('DELETE FROM ' . $this->getDatabaseHandler()->tableName('player_items') . ' WHERE ' . $this->getDatabaseHandler()->fieldName('player_id') . ' = ' . $this->getDatabaseHandler()->quote($this->getPlayerId()));

			if(count($this->data) > 0)
			{
				$keys = array();
				foreach($this->fields as $key)
					$keys[] = $this->getDatabaseHandler()->fieldName($key);

				$query = 'INSERT INTO ' . $this->getDatabaseHandler()->tableName('player_items') . ' (' . implode(', ', $keys) . ') VALUES ';
				$items = array();
				foreach($this->data as $item)
				{
					$fieldValues = array();
					foreach($this->fields as $key)
						if($key != 'player_id' || $this->player_id == 0)
							$fieldValues[] = $this->getDatabaseHandler()->quote($item->data[$key]);
						else
							$fieldValues[] = $this->getDatabaseHandler()->quote($this->player_id);
					$items[] = '(' . implode(', ', $fieldValues) . ')';
				}
				$this->getDatabaseHandler()->query($query . implode(', ', $items));
			}
		}
		else
			new Error_Critic('Cannot save ItemsList. Player ID not set.');
	}

	public function setPlayerId($value)
	{
		$this->player_id = $value;
	}

	public function getPlayerId()
	{
		return $this->player_id;
	}

	public function getSlot($slot)
	{
		if(!isset($this->data))
			$this->load();
		foreach($this->data as $i => $item)
			if($item->getPID() == $slot)
				return $item;

		return false;
	}

	public function getItem($pid, $sid = null)
	{
		if(!isset($this->data))
			$this->load();
		if($sid != null)
		{
			foreach($this->data as $item)
				if($item->getPID() == $pid && $item->getSID() == $sid)
					return $item;
			return false;
		}
		else
		{
			$items = array();
			foreach($this->data as $item)
				if($item->getPID() == $pid)
					$items[$item->getSID()] = $item;
			return $items;
		}
	}
}