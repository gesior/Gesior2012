<?php

if (!defined('INITIALIZED')) {
    exit;
}

if (!Visitor::isLogged()) {
    echo 'You are not logged in. Login first to buy points.';
    return;
}

require_once('./custom_scripts/paghiper/config.php');

echo '<h2>Automatic PagHiper (PIX) payments</h2>';

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

if (isset($_GET['payment_option'])) {
    $paymentOption = (int)$_GET['payment_option'];
    if (!isset($paghiperConfigs[$paymentOption])) {
        echo 'Invalid option.';
        return;
    }

    $email = Visitor::getAccount()->getMail();
    $paghiperConfig = $paghiperConfigs[$paymentOption];
    echo '<form action="?subtopic=paghiper_redirect" method="post">';
    echo '<input type="hidden" name="payment_option" value="' . $paymentOption . '" />';
    echo '<table>';
    echo '<tr><td>Payer e-mail</td>';
    echo '<td><input type="text" name="payer_email" value="' . htmlspecialchars($email) . '" required /></td>';
    echo '</tr>';
    echo '<tr><td>Payer name</td><td><input type="text" name="payer_name" required /></td></tr>';
    echo '<tr><td>Payer CPF/CNPJ</td><td><input type="text" name="payer_cpf" required /></td></tr>';
    echo '<tr><td></td><td><input type="submit" value="Start payment" /></td></tr>';
    echo '</table>';
    echo '</form>';
    return;
}

echo '<table cellspacing="0" style="width:100%"><tr><td colspan="2"><b>Select offer:</b></td></tr>';
foreach ($paghiperConfigs as $configId => $paghiperConfig) {
    echo '<tr>';
    echo '<td>Buy ' . $paghiperConfig['premium_points'] . ' premium points for ' . ($paghiperConfig['money_amount'] / 100) . ' BRL</td>';
    echo '<td style="text-align:center"><a href="?subtopic=paghiper&payment_option=' . $configId . '">Pay</a></td>';
    echo '</tr>';
}
echo '</table>';
