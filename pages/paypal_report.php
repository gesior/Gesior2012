<?php
if(!defined('INITIALIZED'))
	exit;

require_once('./custom_scripts/paypal/config.php');
/*
 * PayPal IPN IPs (it can change in future)
 * https://ppmts.custhelp.com/app/answers/detail/a_id/92
 * search: notify.paypal.com (IPN delivery) 
*/
$paypalServerIps = [
    '64.4.240.0/21',
    '64.4.248.0/22',
    '66.211.168.0/22',
    '91.243.72.0/23',
    '173.0.80.0/20',
];
if(!Website::isIpInRanges($_SERVER['REMOTE_ADDR'], $paypalServerIps))
{
	echo 'wrong IP';
	exit;
}
$receiverMail = $_REQUEST['receiver_email']; // ots admin mail
$status = $_REQUEST['payment_status']; // payment status, we add only when is 'Completed'
$currency = $_REQUEST['mc_currency']; // money currency, like USD or EUR
$gross = $_REQUEST['mc_gross']; // amount of money, like: 10.00
$payerMail = $_REQUEST['payer_email']; // player mail
$accountID = $_REQUEST['custom']; // user account ID
$transactionID = $_REQUEST['txn_id']; // transaction ID

$logFile = 'custom_scripts/paypal/reported_ids/' . $transactionID . '.log';
if(!file_exists($logFile) && $status == 'Completed')
{
	foreach($paypals as $pay)
	{
		if($receiverMail == $pay['mail'] && $currency == $pay['money_currency'] && $gross == $pay['money_amount'])
		{
			$account = new Account($accountID);
			if($account->isLoaded())
			{
				if(file_put_contents($logFile, 'accountID:' . $accountID . ',mail:' . $payerMail . ',amount:' . $gross . ' ' . $currency . ',points:' . $pay['premium_points']) !== false)
				{
					$account->setPremiumPoints($account->getPremiumPoints() + $pay['premium_points']);
					$account->save();
				}
			}
			break;
		}
	}
}
exit;
