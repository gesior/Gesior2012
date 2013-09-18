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

	public function getBanReasonName($reasonId)
	{
		switch($reasonId)
		{
			case 0:
				return "Offensive Name";
			case 1:
				return "Invalid Name Format";
			case 2:
				return "Unsuitable Name";
			case 3:
				return "Name Inciting Rule Violation";
			case 4:
				return "Offensive Statement";
			case 5:
				return "Spamming";
			case 6:
				return "Illegal Advertising";
			case 7:
				return "Off-Topic Public Statement";
			case 8:
				return "Non-English Public Statement";
			case 9:
				return "Inciting Rule Violation";
			case 10:
				return "Bug Abuse";
			case 11:
				return "Game Weakness Abuse";
			case 12:
				return "Using Unofficial Software to Play";
			case 13:
				return "Hacking";
			case 14:
				return "Multi-Clienting";
			case 15:
				return "Account Trading or Sharing";
			case 16:
				return "Threatening Gamemaster";
			case 17:
				return "Pretending to Have Influence on Rule Enforcement";
			case 18:
				return "False Report to Gamemaster";
			case 19:
				return "Destructive Behaviour";
			case 20:
				return "Excessive Unjustified Player Killing";
			case 21:
				return "Invalid Payment";
			case 22:
				return "Spoiling Auction";
			default:
				return "Unknown Reason";
		}
	}

	public function limitTextLength($text, $length_limit)
	{
		if(strlen($text) > $length_limit)
			return substr($text, 0, strrpos(substr($text, 0, $length_limit), " ")).'...';
		else
			return $text;
	}
}