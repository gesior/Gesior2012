<?php
if(!defined('INITIALIZED'))
	exit;

require_once('custom_scripts/contenidopago/config.php');

if(!$contenidopago_active)
	die('Automatic points disabled!');
	
if(isset($_POST['formcodigo']))
{
	$code = $_POST['code'];
/*
PART OF contenidopago.com DISABLED, BECAUSE OF TOO MANY HACKS REPORTS. IT SHOULD STILL WORK FINE WITHOUT THAT PART!
	if($logged)
	{
		$QueryString= "LinkUrl=" . urlencode($report_url) . "&codigo=" . urlencode($code) . "&idservicio=" . $idOfService;

		$result = '';
		if(intval(get_cfg_var('allow_url_fopen')) && function_exists('file_get_contents'))
		{
			$result=@file_get_contents("http://contenidopago.com/codigoval.php?".$QueryString); 
		}
		elseif(intval(get_cfg_var('allow_url_fopen')) && function_exists('file'))
		{
			if($content = @file("http://contenidopago.com/codigoval.php?".$QueryString))
				$result=@join('', $content);
		}
		else
		{
			$main_content .= "It appears that your web host has disabled all functions for handling remote pages and as a result the BackLinks software will not function on your web page. Please contact your web host for more information.";
		}

		if($result=='ok')
		{
			$account_logged->setPremiumPoints($account_logged->getPremiumPoints() + $points);
			$account_logged->save();
			$main_content .= 'You received ' . $points . ' premium points.';
		}
		elseif($result=='no')
		{
			$main_content .= 'This code is already used.';
		}
		else
		{
			$main_content .= 'Wrong code.';
		}
	}
	else
	{
		$main_content .= '<h3>You have to login to buy points!<br /><a href="?subtopic=accountmanagement" />LOGIN HERE</a></h3>';
	}
*/
}
else
{
	// now automatic codes part
	$name = $_GET['name'];
	$points = $_GET['puntos'];

	$hf = fopen('http://www.contenidopago.com/validate.php',r);
	$line = fgets($hf);
	$listOfIPs = explode('|',$line);


	$ip = $_SERVER['REMOTE_ADDR'];

	if(!in_array($ip, $listOfIPs))
		die("You are not able to use this system!");

	if($_GET['check'] == 1)
	{
		if(!empty($name))
		{
			$account = new Account($name, Account::LOADTYPE_NAME);
			if(!$account->isLoaded())
			{
				die("Account with name " . htmlspecialchars($name) . " does not exist.");
			}
			else 
			{
				die('ok');	
			}
		}
		else
			die("You did not set the user!");
	}

	if($_GET['paypal'] == 1)
	{
		if(!(empty($name)))
		{
			$account = new Account($name, Account::LOADTYPE_NAME);
			if(!$account->isLoaded())
			{
				die('This username does not exist: ' . htmlspecialchars($name));
			}
			else 
			{
				
				$account->setPremiumPoints($account->getPremiumPoints() + $points);
				$account->save();
			}
		}
		else
			die('You did not set the user!');

		die ('ok');
	}

	if(!(empty($name)))
	{
		$account = new Account($name, Account::LOADTYPE_NAME);
		if(!$account->isLoaded())
		{
			die('This username does not exist: ' . htmlspecialchars($name));
		}
		else 
		{
			
			$account->setPremiumPoints($account->getPremiumPoints() + $points);
			$account->save();
		}
	}
	else
		die('You did not set the user!');

	die ('ok');
}
