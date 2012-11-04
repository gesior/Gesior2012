<?php
if(!defined('INITIALIZED'))
	exit;

class DatabaseList extends DatabaseHandler implements Iterator, Countable
{
	public $data;
	public $iterator = 0;
	public $class;
	public $table;
	public $tables = array();
	public $fields = array();
	public $extraFields = array();
	public $filter;
	public $orders = array();
	public $limit;
	public $offset = 0;

	public function __construct($class = null)
	{
		if($class !== null)
			$this->setClass($class);
	}

	public function load()
	{
		$fieldsArray = array();

		if(count($this->fields) > 0)
			foreach($this->fields as $fieldName)
					$fieldsArray[$fieldName] = $this->getDatabaseHandler()->tableName($this->table) . '.' . $this->getDatabaseHandler()->fieldName($fieldName);

		if(count($this->extraFields) > 0)
			foreach($this->extraFields as $field)
				if(!$field->hasAlias())
					$fieldsArray[] = $this->getDatabaseHandler()->tableName($field->getTable()) . '.' . $this->getDatabaseHandler()->fieldName($field->getName());
				else
					$fieldsArray[] = $this->getDatabaseHandler()->tableName($field->getTable()) . '.' . $this->getDatabaseHandler()->fieldName($field->getName()) . ' AS ' . $this->getDatabaseHandler()->fieldName($field->getAlias());

		$tables = array();
		foreach($this->tables as $table)
			$tables[] = $this->getDatabaseHandler()->tableName($table);

		$filter = '';
		if($this->filter !== null)
			$filter = ' WHERE ' .$this->filter->__toString();

		$order = '';
		$orders = array();
		if(count($this->orders) > 0)
		{
			foreach($this->orders as $_tmp_order)
				$orders[] = $_tmp_order->__toString();
			if(count($orders) > 0)
				$order = ' ORDER BY ' . implode(', ', $orders);
		}

		$limit = '';
		if($this->limit !== null)
			$limit = ' LIMIT ' . (int) $this->limit;

		$offset = '';
		if($this->offset > 0)
			$offset = ' OFFSET ' . (int) $this->offset;

		$query = 'SELECT ' . implode(', ', $fieldsArray) . ' FROM ' . implode(', ', $tables) . $filter . $order . $limit . $offset;

		$this->data = $this->getDatabaseHandler()->query($query)->fetchAll();
	}

	public function getResult($id)
	{
		if(!isset($this->data))
			$this->load();
		if(isset($this->data[$id]))
		{
			if(!is_object($this->data[$id]))
			{
				$_tmp = new $this->class();
				$_tmp->loadData($this->data[$id]);
				return $_tmp;
			}
			else
				return $this->data[$id];
		}
		else
			return false;
	}

	public function addExtraField($field)
	{
		$this->extraFields[] = $field;
		$this->addTables($field->getTable());
	}

	public function addOrder($order)
	{
		$this->orders[] = $order;
	}

	public function setClass($class)
	{
		$this->class = $class;
		$instance = new $this->class();
		$this->fields = $instance::$fields;
		if(isset($instance::$extraFields))
			foreach($instance::$extraFields as $extraField)
			{
				if(!isset($extraField[2]))
					$this->extraFields[] = new SQL_Field($extraField[0], $extraField[1]);
				else
					$this->extraFields[] = new SQL_Field($extraField[0], $extraField[1], $extraField[2]);
				$this->tables[$extraField[1]] = $extraField[1];
			}
		$this->table = $instance::$table;
		$this->tables[$instance::$table] = $instance::$table;
	}

	public function setFilter($filter)
	{
		$this->addTables($filter->getTables());
		$this->filter = $filter;
	}

	public function setLimit($limit)
	{
		$this->limit = $limit;
	}

	public function setOffset($offset)
	{
		$this->offset = $offset;
	}

	public function addTables($tables)
	{
		if(is_array($tables))
		{
			foreach($tables as $table)
				if($table != '' && !in_array($table, $this->tables))
					$this->tables[$table] = $table;
		}
		elseif($tables != '' && !in_array($tables, $this->tables))
			$this->tables[$tables] = $tables;
	}

    public function current()
    {
        return $this->getResult($this->iterator);
    }

    public function rewind()
    {
		if(!isset($this->data))
			$this->load();
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
        return isset($this->data[$this->iterator]);
    }

    public function count()
    {
		if(!isset($this->data))
			$this->load();
        return count($this->data);
    }
}