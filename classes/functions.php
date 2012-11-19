<?php
if(!defined('INITIALIZED'))
	exit;

class Functions
{
	public static $configs = array();

	public static function getExpForLevel($lv)
	{
		$lv--;
		$lv = (string) $lv;
		return bcdiv(bcadd(bcsub(bcmul(bcmul(bcmul("50", $lv), $lv), $lv),  bcmul(bcmul("150", $lv), $lv)), bcmul("400", $lv)), "3", 0);
	}

	public static function isValidFolderName($string)
	{
		return (strspn($string, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789_-") == strlen($string));
	}

	public static function isValidMail($email)
	{
		return (filter_var($email, FILTER_VALIDATE_EMAIL) != false);
	}

	public static function isPremium($premdays, $lastday)
	{
		return ($premdays - (date("z", time()) + (365 * (date("Y", time()) - date("Y", $lastday))) - date("z", $lastday)) > 0);
	}

	public function limitTextLength($text, $length_limit)
	{
		if(strlen($text) > $length_limit)
			return substr($text, 0, strrpos(substr($text, 0, $length_limit), " ")).'...';
		else
			return $text;
	}
}