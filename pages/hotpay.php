<?php

if (!defined('INITIALIZED')) {
    exit;
}

if (!$logged) {
    echo 'You are not logged in. Login first to buy points.';
    return;
}

require_once('./custom_scripts/hotpay/config.php');

if (!$hotpayEnabled) {
    echo 'HotPay is disabled.';
    return;
}

echo '<h2>HotPay.pl payments</h2><br>';

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
foreach ($hotpayConfigs as $hotpayConfig) {
    echo '<tr><td>Buy ' . $hotpayConfig['premium_points'] . ' premium points for ' . $hotpayConfig['money_amount'] . ' PLN</td><td style="text-align:center">';

    $form = [
        "SEKRET" => $hotpaySEKRET_USLUGI,
        "NAZWA_USLUGI" => $hotpayNAZWA_USLUGI,
        "ADRES_WWW" => $hotpayADRES_POWROTU,
        "KWOTA" => $hotpayConfig['money_amount'],
        "ID_ZAMOWIENIA" => $account_logged->getID(),
        "EMAIL" => $account_logged->getMail(),
        "DANE_OSOBOWE" => "",
    ];
    $hash = hash(
        "sha256",
        $hotpayHASLO_Z_USTAWIEN . ";" .
        $form["KWOTA"] . ";" .
        $form["NAZWA_USLUGI"] . ";" .
        $form["ADRES_WWW"] . ";" .
        $form["ID_ZAMOWIENIA"] . ";" .
        $form["SEKRET"]
    );

    echo '<form id="order" action="https://platnosc.hotpay.pl/" method="post">';
    foreach ($form as $key => $value) {
        echo '<input name="' . $key . '" value="' . $value . '" type="hidden">';
    }

    echo '<input name="HASH" required value="' . $hash . '" type="hidden">';
    echo '<button type="submit">Pay</button></form>';

    echo '</td></tr>';
}
echo '</table>';
