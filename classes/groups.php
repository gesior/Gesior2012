<?php
if(!defined('INITIALIZED'))
	exit;

class Groups implements Iterator, Countable
{
	private $groups = array();
	private $XML;
	public $iterator = 0;

	public function __construct($file)
	{
		$XML = new DOMDocument();
		if(!$XML->load($file))
			new Error_Critic('', 'Groups::__construct - cannot load file <b>' . htmlspecialchars($file) . '</b>');

		$this->XML = $XML;

		foreach($XML->getElementsByTagName('group') as $group)
		{
			if($group->hasAttribute('id') && $group->hasAttribute('name'))
			{
				$groupData = array();
				$groupData['id'] = $group->getAttribute('id');
				$groupData['name'] = $group->getAttribute('name');
				$this->groups[$groupData['id']] = new Group($groupData);
			}
			else
				new Error_Critic('#C', 'Cannot load group. <b>id</b> or/and <b>name</b> parameter is missing');
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