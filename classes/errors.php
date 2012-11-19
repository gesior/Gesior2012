<?php
if(!defined('INITIALIZED'))
	exit;

class Errors
{
	const TYPE_BOTH = 0; // parameter for some functions to return 'errors and notices'
	const TYPE_NOTICE = 1; // show information for website user, for example 'this name contains illegal letters' [when create account]
	const TYPE_ERROR = 2; // show important information about bug that administrator must fix, for example 'file ./logs/paypal_transactions.log does not exist'
	const TYPE_CRITIC = 3; // show error information and block script execution

	private $errors = array();
	private $notices = array();

	public function addNotice($id = null, $text = null)
	{
		$this->notices[] = new Error($id, $text, Errors::TYPE_NOTICE);
	}

	public function addError($id = null, $text = null)
	{
		$this->errors[] = new Error($id, $text, Errors::TYPE_ERROR);
	}

	public function addCriticError($id = null, $text = null, $errors = array())
	{
		throw new Error_Critic($id, $text, $errors);
	}

	public function addErrors($array)
	{
		$this->errors = array_merge($this->errors, $array);
	}

	public function addNotices($array)
	{
		$this->notices = array_merge($this->notices, $array);
	}

	public function getErrorsList($type = Errors::TYPE_BOTH)
	{
		if($type == Errors::TYPE_BOTH)
			return array_merge($this->notices, $this->errors);
		elseif($type == Errors::TYPE_NOTICE)
			return $this->notices;
		elseif($type == Errors::TYPE_ERROR)
			return $this->errors;
		else
			return array();
	}

	public function isErrorsListEmpty($type = Errors::TYPE_BOTH)
	{
		if($type == Errors::TYPE_BOTH)
			$arr = array_merge($this->notices, $this->errors);
		elseif($type == Errors::TYPE_NOTICE)
			$arr = $this->notices;
		elseif($type == Errors::TYPE_ERROR)
			$arr = $this->errors;
		else
			$arr = array();
			return empty($arr);
	}

	public function getErrorsCount($type = Errors::TYPE_BOTH)
	{
		if($type == Errors::TYPE_BOTH)
			return count($this->notices) + count($this->errors);
		elseif($type == Errors::TYPE_NOTICE)
			return count($this->notices);
		elseif($type == Errors::TYPE_ERROR)
			return count($this->errors);
		else
			return 0;
	}
}