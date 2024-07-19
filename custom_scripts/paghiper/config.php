<?php

// 'false' blocks new payments, it will still process old payment reports
$paghiper_active = false;

$paghiper_report_url = 'https://example.com/paghiper_report.php';
// https://paghiper.com/painel/credenciais/ - ApiKey
$paghiper_api_key = 'apk_';
// https://paghiper.com/painel/credenciais/ - Token
$paghiper_auth_token = '510I';

$paghiperConfigs = [];

$paghiperConfigs[] = [
    'money_amount' => 500, // cents
    'offer_name' => '100 Premium Points',
    'premium_points' => 100,
];

$paghiperConfigs[] = [
    'money_amount' => 1000, // cents
    'offer_name' => '200 Premium Points',
    'premium_points' => 200,
];
