<?php
if(!defined('INITIALIZED'))
	exit;

// DEFINE VARIABLES FOR SCRIPTS AND LAYOUTS (no more notices 'undefinied variable'!)
if(!isset($_REQUEST['subtopic']) || empty($_REQUEST['subtopic']) || is_array($_REQUEST['subtopic']))
{
	$_REQUEST['subtopic'] = "latestnews";
}
else
	$_REQUEST['subtopic'] = (string) $_REQUEST['subtopic'];

if(Functions::isValidFolderName($_REQUEST['subtopic']))
{
	if(Website::fileExists("pages/" . $_REQUEST['subtopic'] . ".php"))
	{
		$subtopic = $_REQUEST['subtopic'];
	}
	else
		new Error_Critic('CRITICAL ERROR', 'Cannot load page <b>' . htmlspecialchars($_REQUEST['subtopic']) . '</b>, file does not exist.');
}
else
	new Error_Critic('CRITICAL ERROR', 'Cannot load page <b>' . htmlspecialchars($_REQUEST['subtopic']) . '</b>, invalid file name [contains illegal characters].');

// action that page should execute
if(isset($_REQUEST['action']))
	$action = (string) $_REQUEST['action'];
else
	$action = '';

$logged = false;
$account_logged = new Account();
$group_id_of_acc_logged = 0;
// with ONLY_PAGE option we want disable useless SQL queries
if(!ONLY_PAGE)
{
	// logged boolean value: true/false
	$logged = Visitor::isLogged();
	// Account object with account of logged player or empty Account
	$account_logged = Visitor::getAccount();
	// group of acc. logged
	if(Visitor::isLogged())
		$group_id_of_acc_logged = Visitor::getAccount()->getPageAccess();
}
$layout_name = './layouts/' . Website::getWebsiteConfig()->getValue('layout');

$title = ucwords($subtopic) . ' - ' . Website::getServerConfig()->getValue('serverName');

$topic = $subtopic;

$passwordency = Website::getServerConfig()->getValue('passwordType');
if($passwordency == 'plain')
	$passwordency = '';

$news_content = '';
$vocation_name = array();
foreach(Website::getVocations() as $vocation)
{
	$vocation_name[$vocation->getId()] = $vocation->getName();
}

$layout_ini = parse_ini_file($layout_name.'/layout_config.ini');
foreach($layout_ini as $key => $value)
	$config['site'][$key] = $value;

//###################### FUNCTIONS ######################
function microtime_float()
{
	return microtime(true);
}

function isPremium($premdays, $lastday)
{
	return Functions::isPremium($premdays, $lastday);
}

function saveconfig_ini($config)
{
	new Error_Critic('', 'function <i>saveconfig_ini</i> is deprecated. Do not use it.');
}

function password_ency($password, $account = null)
{
	new Error_Critic('', 'function <i>password_ency</i> is deprecated. Do not use it.');
}

function check_name($name)
{
	$name = (string) $name;
	$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM- [ ] '");
	if($temp != strlen($name))
		return false;
	if(strlen($name) > 25)
		return false;

	return true;
}

function check_account_name($name)
{
	$name = (string) $name;
	$temp = strspn("$name", "QWERTYUIOPASDFGHJKLZXCVBNM0123456789");
	if ($temp != strlen($name))
		return false;
	if(strlen($name) < 1)
		return false;
	if(strlen($name) > 32)
		return false;

	return true;
}

function check_name_new_char($name)
{
	$name = (string) $name;
	$name_to_check = strtolower($name);
	//first word can't be:
	$first_words_blocked = array('gm ','cm ', 'god ','tutor ', "'", '-');
	//names blocked:
	$names_blocked = array('gm','cm', 'god', 'tutor');
	//name can't contain:
	$words_blocked = array('gamemaster', 'game master', 'game-master', "game'master", '--', "''","' ", " '", '- ', ' -', "-'", "'-", 'fuck', 'sux', 'suck', 'noob', 'tutor');
	foreach($first_words_blocked as $word)
		if($word == substr($name_to_check, 0, strlen($word)))
			return false;
	if(substr($name_to_check, -1) == "'" || substr($name_to_check, -1) == "-")
		return false;
	if(substr($name_to_check, 1, 1) == ' ')
		return false;
	if(substr($name_to_check, -2, 1) == " ")
		return false;
	foreach($names_blocked as $word)
		if($word == $name_to_check)
			return false;
	for($i = 0; $i < strlen($name_to_check); $i++)
		if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
			return false;
	foreach($words_blocked as $word)
		if (!(strpos($name_to_check, $word) === false))
			return false;
	for($i = 0; $i < strlen($name_to_check); $i++)
		if($name_to_check[$i] == $name_to_check[($i+1)] && $name_to_check[$i] == $name_to_check[($i+2)])
			return false;
	for($i = 0; $i < strlen($name_to_check); $i++)
		if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
			return false;
	$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM- '");
	if ($temp != strlen($name))
		return false;
	if(strlen($name) < 1)
		return false;
	if(strlen($name) > 25)
		return false;

	return true;
}

function check_rank_name($name)
{
	$name = (string) $name;
	$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-[ ] ");
	if($temp != strlen($name))
		return false;
	if(strlen($name) < 1)
		return false;
	if(strlen($name) > 60)
		return false;

	return true;
}

function check_guild_name($name)
{
	$name = (string) $name;
	$words_blocked = array('--', "''","' ", " '", '- ', ' -', "-'", "'-", '  ');
	$temp = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-' ");
	if($temp != strlen($name))
		return false;
	if(strlen($name) < 1)
		return false;
	if(strlen($name) > 60)
		return false;

	foreach($words_blocked as $word)
		if (!(strpos($name, $word) === false))
			return false;

	return true;
}

function check_password($pass)
{
	$pass = (string) $pass;
	$temp = strspn("$pass", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890");
	if($temp != strlen($pass))
		return false;
	if(strlen($pass) > 40)
		return false;

	return true;
}

function check_mail($email)
{
	$email = (string) $email;
	$ok = "/[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,4}/";
	return (preg_match($ok, $email))? true: false;
}

function items_on_player($characterid, $pid)
{
	new Error_Critic('', 'function <i>items_on_player</i> is deprecated. Do not use it. It used too many queries!');
}

function getReason($reasonId)
{
	return Functions::getBanReasonName($reasonId);
}

//################### DISPLAY FUNCTIONS #####################
//return shorter text (news ticker)
function short_text($text, $chars_limit) 
{
	if(strlen($text) > $chars_limit)
		return substr($text, 0, strrpos(substr($text, 0, $chars_limit), " ")).'...';
	else
		return $text;
}
//return text to news msg
function news_place()
{
	return '';
}
//set monster of week
function logo_monster()
{
	new Error_Critic('', 'function <i>logo_monster</i> is deprecated. Do not use it!');
}

// we don't want to count AJAX scripts/guild images as page views, we also don't need status
if(!ONLY_PAGE)
{
	// STATUS CHECKER
	$statustimeout = 1;
	foreach(explode("*", str_replace(" ", "", $config['server']['statusTimeout'])) as $status_var)
		if($status_var > 0)
			$statustimeout = $statustimeout * $status_var;
	$statustimeout = $statustimeout / 1000;
	$config['status'] = parse_ini_file('cache/DONT_EDIT_serverstatus.txt');
	if($config['status']['serverStatus_lastCheck']+$statustimeout < time())
	{
		$config['status']['serverStatus_checkInterval'] = $statustimeout+3;
		$config['status']['serverStatus_lastCheck'] = time();
		$statusInfo = new ServerStatus($config['server']['ip'], $config['server']['statusPort'], 1);
		if($statusInfo->isOnline())
		{
			$config['status']['serverStatus_online'] = 1;
			$config['status']['serverStatus_players'] = $statusInfo->getPlayersCount();
			$config['status']['serverStatus_playersMax'] = $statusInfo->getPlayersMaxCount();
			$h = floor($statusInfo->getUptime() / 3600);
			$m = floor(($statusInfo->getUptime() - $h*3600) / 60);
			$config['status']['serverStatus_uptime'] = $h.'h '.$m.'m';
			$config['status']['serverStatus_monsters'] = $statusInfo->getMonsters();
		}
		else
		{
			$config['status']['serverStatus_online'] = 0;
			$config['status']['serverStatus_players'] = 0;
			$config['status']['serverStatus_playersMax'] = 0;
		}
		$file = fopen("cache/DONT_EDIT_serverstatus.txt", "w");
		$file_data = '';
		foreach($config['status'] as $param => $data)
		{
	$file_data .= $param.' = "'.str_replace('"', '', $data).'"
	';
		}
		rewind($file);
		fwrite($file, $file_data);
		fclose($file);
	}
	//PAGE VIEWS COUNTER
	$views_counter = "cache/DONT_EDIT_usercounter.txt";
	// checking if the file exists
	if (file_exists($views_counter))
	{
		$actie = fopen($views_counter, "r+"); 
		$page_views = fgets($actie, 9); 
		$page_views++; 
		rewind($actie); 
		fputs($actie, $page_views, 9); 
		fclose($actie); 
	}
	else
	{ 
		// the file doesn't exist, creating a new one with value 1
		$actie = fopen($views_counter, "w"); 
		$page_views = 1; 
		fputs($actie, $page_views, 9); 
		fclose($actie); 
	}
}