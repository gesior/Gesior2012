<?php

if (!defined('INITIALIZED')) {
    exit;
}

require_once('./custom_scripts/hotpay/config.php');

// debug config problems: file_put_contents(microtime(true) . '.log', json_encode($_POST, JSON_PRETTY_PRINT));

function hotpayValidateRequest($HASLO_Z_USTAWIEN)
{
    if (empty($_POST["KWOTA"]) ||
        empty($_POST["ID_PLATNOSCI"]) ||
        empty($_POST["ID_ZAMOWIENIA"]) ||
        empty($_POST["STATUS"]) ||
        empty($_POST["SEKRET"]) ||
        empty($_POST["SECURE"]) ||
        empty($_POST["HASH"])
    ) {
        return false;
    }

    return hash(
            "sha256",
            $HASLO_Z_USTAWIEN . ";" .
            $_POST["KWOTA"] . ";" .
            $_POST["ID_PLATNOSCI"] . ";" .
            $_POST["ID_ZAMOWIENIA"] . ";" .
            $_POST["STATUS"] . ";" .
            $_POST["SECURE"] . ";" .
            $_POST["SEKRET"]
        ) == $_POST["HASH"];
}

if (!hotpayValidateRequest($hotpayHASLO_Z_USTAWIEN)) {
    exit('invalid hash');
}

$status = $_POST["STATUS"];
$amount = $_POST["KWOTA"];
$transactionId = $_POST["ID_PLATNOSCI"];
$accountId = $_POST["ID_ZAMOWIENIA"];

if ($status !== "SUCCESS") {
    exit('not successful payment');
}

$paymentConfig = null;
foreach ($hotpayConfigs as $hotpayConfig) {
    if ($amount == $hotpayConfig['money_amount']) {
        $paymentConfig = $hotpayConfig;
        break;
    }
}
if (!$paymentConfig) {
    exit('payment config not found');
}

$logFile = 'custom_scripts/hotpay/reported_ids/' . $transactionId . '.log';
if (file_exists($logFile)) {
    exit('repeated notification');
}

$account = new Account($accountId);
if (!$account->isLoaded()) {
    exit('account ' . htmlspecialchars($accountId) . ' not found');
}

$logData = 'accountID:' . $accountId . ',amount:' . $amount . ' PLN,points:' . $paymentConfig['premium_points'];
if (file_put_contents($logFile, $logData) === false) {
    exit('failed to save transaction file');
}

$account->setPremiumPoints($account->getPremiumPoints() + $paymentConfig['premium_points']);
$account->save();

exit('ok');
