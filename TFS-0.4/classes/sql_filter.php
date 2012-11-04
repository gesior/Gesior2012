<?php
if(!defined('INITIALIZED'))
	exit;

class SQL_Filter
{
    const EQUAL = ' = ';
    const LOWER = ' < ';
    const GREATER = ' > ';
    const NOT_EQUAL = ' != ';
    const NOT_LOWER = ' >= ';
    const NOT_GREATER = ' <= ';
    const LIKE = ' LIKE ';
    const NOT_LIKE = ' NOT LIKE ';
    const CRITERIUM_AND = ' AND ';
    const CRITERIUM_OR = ' OR ';

	public $leftSide;
	public $filterType;
	public $rightSide;
	public $bracket = false;
	public $tables = array();

	public function __construct($first, $type, $second, $bracket = false)
	{
		$this->leftSide = $first;
		if($this->leftSide instanceof SQL_Field || $this->leftSide instanceof SQL_Filter)
			$this->addTables($this->leftSide->getTables());
		$this->filterType = $type;
		$this->rightSide = $second;
		if($this->rightSide instanceof SQL_Field || $this->rightSide instanceof SQL_Filter)
			$this->addTables($this->rightSide->getTables());
			
		$this->bracket = $bracket;
	}

    public function getTables()
    {
        return $this->tables;
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

	public function __toString()
	{
		if($this->leftSide instanceof SQL_Field or $this->leftSide instanceof SQL_Filter)
			$ret = $this->leftSide->__toString();
		else
			$ret = Website::getDBHandle()->quote($this->leftSide);

		$ret .= $this->filterType;

		if($this->rightSide instanceof SQL_Field or $this->rightSide instanceof SQL_Filter)
			$ret .= $this->rightSide->__toString();
		else
			$ret .= Website::getDBHandle()->quote($this->rightSide);

		if($this->bracket)
			return '(' . $ret . ')';
		else
			return $ret;
	}
}