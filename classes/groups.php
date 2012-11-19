<?php
if(!defined('INITIALIZED'))
	exit;

class Groups implements Iterator, Countable
{
	private $groups = array();
	public $iterator = 0;

	public function __construct()
	{
		foreach(Website::getDBHandle()->query('SELECT * FROM ' . Website::getDBHandle()->tableName('groups')) as $group)
		{
			$groupData = array();
			$groupData['id'] = $group['id'];
			$groupData['name'] = $group['name'];
			$this->groups[$groupData['id']] = new Group($groupData);
		}
	}
	/*
	 * Get group
	*/
	public function getGroup($id)
	{
		if(isset($this->groups[$id]))
			return $this->groups[$id];

		return false;
	}
	/*
	 * Get group name without getting group
	*/
	public function getGroupName($id)
	{
		if($group = self::getGroup($id))
			return $group->getName();

		return false;
	}

    public function current()
    {
        return $this->groups[$this->iterator];
    }

    public function rewind()
    {
        $this->iterator = 0;
    }

    public function next()
    {
        ++$this->iterator;
    }

    public function key()
    {
        return $this->iterator;
    }

    public function valid()
    {
        return isset($this->groups[$this->iterator]);
    }

    public function count()
    {
        return count($this->groups);
    }
}