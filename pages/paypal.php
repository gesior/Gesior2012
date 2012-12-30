<?php
if(!defined('INITIALIZED'))
	exit;

if($logged)
{
	require_once('./custom_scripts/paypal/config.php');
	echo '<h2>Automatic PayPal shop system</h2><br><b>Here are the steps you need to make:</b><br>
	1. You need a valid creditcard <b>or</b> a PayPal account with a required amount of money.<br>
	2. Choose how many points you want buy.<br />
	3. Click on the donate/buy button.<br>
	4. Make a transaction on PayPal.<br>
	5. After the transaction points will be automatically added to your account.<br>
	6. Go to Item shop and use your points.</b><br /><br />';

	echo '<style>
	table
	{
	border-collapse:collapse;
	}
	table, td, th
	{
	border:1px solid black;
	}
	</style>';

	echo '<table cellspacing="0" style="width:100%"><tr><td colspan="2"><b>Select offer:</b></td></tr>';
	foreach($paypals as $paypal)
	{
		echo '<tr><td>Buy ' . $paypal['premium_points'] . ' premium points for ' . $paypal['money_amount'] . ' ' . $paypal['money_currency'] . '</td><td style="text-align:center"><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="' . $paypal_payment_type . '">
		<input type="hidden" name="business" value="' . $paypal['mail'] . '">
		<input type="hidden" name="item_name" value="' . htmlspecialchars($paypal['name']) . '">
		<input type="hidden" name="custom" value="' . $account_logged->getID() . '">
		<input type="hidden" name="amount" value="' . htmlspecialchars($paypal['money_amount']) . '">
		<input type="hidden" name="currency_code" value="' . htmlspecialchars($paypal['money_currency']) . '">
		<input type="hidden" name="no_note" value="0">
		<input type="hidden" name="no_shipping" value="1">
		<input type="hidden" name="notify_url" value="' . $paypal_report_url . '">
		<input type="hidden" name="return" value="' . $paypal_return_url . '">
		<input type="hidden" name="rm" value="0">
		<input type="image" src="' . $paypal_image . '" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
		</form></td></tr>';
	}
	echo '</table>';
}
else
	echo 'You are not logged in. Login first to buy points.';