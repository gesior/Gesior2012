<?php
if(!defined('INITIALIZED'))
	exit;

class WebsiteErrors
{
	const TYPE_BOTH = 0; // parameter for some functions to return 'errors and notices'
	const TYPE_NOTICE = 1; // show information for website user, for example 'this name contains illegal letters' [when create account]
	const TYPE_ERROR = 2; // show important information about bug that administrator must fix, for example 'file ./logs/paypal_transactions.log does not exist'
	const TYPE_CRITIC = 3; // show error information and block script execution

	private static $errors = array();
	private static $notices = array();

	public static function addNotice($id = null, $text = null)
	{
		self::$notices[] = new Error($id, $text, Errors::TYPE_NOTICE);
	}

	public static function addError($id = null, $text = null)
	{
		self::$errors[] = new Error($id, $text, Errors::TYPE_ERROR);
		throw new Error_Critic($id, $text, self::getErrorsList());
	}

	public static function addCriticError($id = null, $text = null, $errors = array())
	{
		throw new Error_Critic($id, $text, $errors);
	}

	public static function addNotices($array)
	{
		self::$notices = array_merge(self::$notices, $array);
	}

	public static function addErrors($array)
	{
		self::$errors = array_merge(self::$errors, $array);
		if(count($array) > 0)
			throw new Error_Critic($id, $text, self::getErrorsList());
	}

	public static function getErrorsList($type = Errors::TYPE_BOTH)
	{
		if($type == Errors::TYPE_BOTH)
			return array_merge(self::$notices, self::$errors);
		elseif($type == Errors::TYPE_NOTICE)
			return self::$notices;
		elseif($type == Errors::TYPE_ERROR)
			return self::$errors;
		else
			return array();
	}

	public static function isErrorsListEmpty($type = Errors::TYPE_BOTH)
	{
		if($type == Errors::TYPE_BOTH)
			$arr = array_merge(self::$notices, self::$errors);
		elseif($type == Errors::TYPE_NOTICE)
			$arr = self::$notices;
		elseif($type == Errors::TYPE_ERROR)
			$arr = self::$errors;
		else
			$arr = array();

		return empty($arr);
	}

	public static function getErrorsCount($type = Errors::TYPE_BOTH)
	{
		if($type == Errors::TYPE_BOTH)
			return count(self::$notices) + count(self::$errors);
		elseif($type == Errors::TYPE_NOTICE)
			return count(self::$notices);
		elseif($type == Errors::TYPE_ERROR)
			return count(self::$errors);
		else
			return 0;
	}
}