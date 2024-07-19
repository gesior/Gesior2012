<?php

if (!defined('INITIALIZED')) {
    exit;
}

if (!Visitor::isLogged()) {
    echo 'You are not logged in. Login first to buy points.';
    return;
}

require_once('./custom_scripts/paghiper/config.php');

if (!$paghiper_active) {
    echo 'PagHiper integration is disabled.';
    return;
}

if (!isset($_POST['payment_option']) ||
    !isset($_POST['payer_email']) ||
    !isset($_POST['payer_name']) ||
    !isset($_POST['payer_cpf'])) {
    echo 'Missing form parameter!';
    return;
}

$paymentOption = (int)$_POST['payment_option'];
if (!isset($paghiperConfigs[$paymentOption])) {
    echo 'Invalid option.';
    return;
}
$paghiperConfig = $paghiperConfigs[$paymentOption];

$payerEmail = (string)$_POST['payer_email'];
$payerName = (string)$_POST['payer_name'];
$payerCpf = (string)$_POST['payer_cpf'];
if (empty($payerEmail) || empty($payerName) || empty($payerCpf)) {
    echo 'Form fields cannot be empty!';
    return;
}

if (filter_var($payerEmail, FILTER_VALIDATE_EMAIL) !== $payerEmail) {
    echo 'Invalid e-mail format!';
    return;
}

$pagHiperIntegration = new PagHiperIntegration(
    $paghiper_api_key,
    $paghiper_auth_token,
    $paghiper_report_url,
    $paghiperConfigs
);
try {
    $qrCodeBase64 = $pagHiperIntegration->createPayment(
        Visitor::getAccount(),
        $paghiperConfig['money_amount'],
        $paghiperConfig['offer_name'],
        $paghiperConfig['premium_points'],
        $payerEmail,
        $payerName,
        $payerCpf
    );
    echo '<h2>PIX payment - scan with app</h2>';
    echo '<h3>' . $paghiperConfig['premium_points'] . ' premium points for ' . ($paghiperConfig['money_amount'] / 100) . ' BRL</h3>';
    echo '<img src="data:image/png;base64,' . $qrCodeBase64 . '" />';
} catch (LogicException $exception) {
    echo '<h2>Payment error: ' . $exception->getMessage() . '</h2>';
    echo '<h3><a href="?subtopic=paghiper">Try again</a></h3>';
}
