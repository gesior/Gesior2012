<?php
if(!defined('INITIALIZED'))
	exit;

class Vocation
{
	private $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}

	public function getId()
	{
		return $this->data['id'];
	}

	public function getName()
	{
		return $this->data['name'];
	}
}