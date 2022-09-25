<?php

require_once('./custom_scripts/coinbase/config.php');

$requestData = file_get_contents('php://input');
$receivedHmac = $_SERVER['HTTP_X_CC_WEBHOOK_SIGNATURE'];
$validHmac = hash_hmac('sha256', $requestData, $coinbaseSharedSecretForNotifications);

if ($receivedHmac != $validHmac && $coinbaseValidateNotificationsSecret) {
    error_log("[Coinbase::report] Invalid HMAC");
    http_response_code(400);
    exit('Invalid HMAC');
}

$json = json_decode($requestData, true);

if (empty($json['event']['data']['code'])) {
    error_log("[Coinbase::report] Payment code not found in request's body.");
    http_response_code(400);
    exit('Payment code not found in request JSON');
}

$code = $json['event']['data']['code'];
$coinbasePayment = new CoinbasePayment($code, CoinbasePayment::LOADTYPE_CODE);
if (!$coinbasePayment->isLoaded()) {
    error_log("[Coinbase::report] Invalid payment code: " . $code);
    http_response_code(400);
    exit('Invalid payment code: ' . htmlspecialchars($code));
}

$coinbase = new Coinbase($coinbaseApiKey);
if (!$coinbase->updatePaymentStatus($coinbasePayment)) {
    error_log("[Coinbase::report] Failed to update payment status: " . htmlspecialchars($code));
    http_response_code(400);
    exit('Failed to update payment status: ' . htmlspecialchars($code));
}

exit('OK');
