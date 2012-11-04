<?php
if(!defined('INITIALIZED'))
	exit;

class SQL_Order
{
	const ASC = 'ASC';
	const DESC = 'DESC';
	public $field;
	public $order = self::ASC;

	public function __construct($field, $order = null)
	{
		$this->field = $field;
		if($order !== null)
			$this->order = $order;
	}

	public function getField()
	{
		return $this->field;
	}

	public function getOrder()
	{
		return $this->order;
	}

	public function __toString()
    {
        return $this->field->__toString() . ' ' . $this->order;
    }
}