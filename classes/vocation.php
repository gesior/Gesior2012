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

	public function getPromotion()
	{
		return $this->data['promotion'];
	}

	public function getParentVocation()
	{
		return $this->data['fromvoc'];
	}

	public function getName()
	{
		return $this->data['name'];
	}

	public function getBaseId()
	{
		return $this->data['base_id'];
	}
}