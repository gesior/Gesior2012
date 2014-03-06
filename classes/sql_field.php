<?php
if(!defined('INITIALIZED'))
	exit;

class SQL_Field
{
	public $name;
	public $table;
	public $alias;
	public function __construct($name, $table = '', $alias = '')
    {
        $this->name = $name;
        $this->table = $table;
        $this->alias = $alias;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getTables()
    {
        return array($this->getTable());
    }

	public function getAlias()
	{
		return $this->alias;
	}

	public function hasAlias()
	{
		return !empty($this->alias);
	}

	public function __toString()
    {
		$table = '';
        $name = Website::getDBHandle()->fieldName($this->name);

        if(!empty($this->table))
            $table = Website::getDBHandle()->tableName($this->table) . '.';

        return $table . $name;
    }
}