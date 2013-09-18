<?php
if(!defined('INITIALIZED'))
	exit;

require_once('./custom_scripts/zaypay/config.php');

if(isset($_GET['payment_id']) && isset($_GET["price_setting_id"]) && isset($options[$_GET["price_setting_id"]]))
{
	$option = $options[$_GET["price_setting_id"]];
	$zaypay = new Zaypay($option['price_id'], $option['price_key']);
	$zaypay_info = $zaypay->show_payment($_GET['payment_id']);
	if($zaypay_info["payment"]["status"] == 'paid' && !file_exists('./custom_scripts/zaypay/used_codes/zay_id_' . $zaypay_info["payment"]["id"]. '.txt'))
	{
		$_acc = explode('=', $zaypay_info["payment"]["your-variables"]);
		$acc = $_acc[1];
		if(file_put_contents('./custom_scripts/zaypay/used_codes/zay_id_' . $zaypay_info["payment"]["id"]. '.txt' , 'account:' . $acc . ',amount-euro:' . $zaypay_info["payment"]["amount-euro"] . ',points:' . $option['points']) !== false)
		{
			$account = new Account($acc);
			if($account->isLoaded())
			{
				$account->set('premium_points', ($account->getCustomField('premium_points')+$option['points']));
				$account->save();
			}
			echo '*ok*';
			exit;
		}
		else
		{
			echo '*ERROR: cannot save code to folder used_codes, make that folder writeable*';
			exit;
		}
	}
	else
	{
		echo '*ok*';
		exit;
	}
}
echo '*ERROR: bugged config or someone try to hack*';
exit;
