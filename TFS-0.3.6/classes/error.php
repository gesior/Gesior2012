<?php
if(!defined('INITIALIZED'))
	exit;

class Error
{
	private $errorId = '';
	private $errorText = 'no text';
	private $errorType = Errors::TYPE_ERROR;

	public function __construct($id = null, $text = null, $type = null)
	{
		if(isset($id))
			$this->errorId = $id;
		if(isset($text))
			$this->errorText = $text;
		if(isset($type))
			$this->errorType = $type;
	}

	public function getId()
	{
		return $this->errorId;
	}

	public function getText()
	{
		return $this->errorText;
	}

	public function getType()
	{
		return $this->errorType;
	}
}