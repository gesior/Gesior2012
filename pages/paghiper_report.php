<?php
if(!defined('INITIALIZED'))
	exit;

require_once('./custom_scripts/paghiper/config.php');

$apiKey = $_REQUEST['apiKey'];
$transactionId = $_REQUEST['transaction_id'];
$notificationId = $_REQUEST['notification_id'];

$pagHiperIntegration = new PagHiperIntegration(
    $paghiper_api_key,
    $paghiper_auth_token,
    $paghiper_report_url,
    $paghiperConfigs
);

try {
    echo $pagHiperIntegration->processNotification($apiKey, $transactionId, $notificationId);
} catch (LogicException $exception) {
    echo 'error: ' . $exception->getMessage();
}
exit;
