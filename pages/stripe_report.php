<?php

require_once('./custom_scripts/stripe/config.php');
require_once('./vendor/autoload.php');

\Stripe\Stripe::setApiKey($stripe_secret_key);

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

if (empty($payload)) {
    http_response_code(200);
    exit();
}

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload,
        $sig_header,
        $stripe_endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    echo 'Invalid payload';
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    echo 'Invalid signature';
    exit();
}

if ($event->type == 'checkout.session.completed') {
    if ($event->data->object->payment_status == 'paid') {
        $session = \Stripe\Checkout\Session::retrieve([
            'id' => $event->data->object->id,
            'expand' => ['line_items'],
        ]);

        $system = 'stripe';
        $account_id = -1;
        $transactionTime = 0;
        $ip = -1;
        $server = '';
        $payment_config_id = -1;
        $date = time();

        if (isset($event->data->object->metadata->custom)) {
            $data = explode('_', $event->data->object->metadata->custom, 2);
            if (count($data) == 2) {
                $account_id = $data[0];
                $server = $data[1];
            }
        }
        $premium_points = -1;
        $add_points = 0;

        $payerMail = 'unknown@nomail.com';
        if (isset($event->data->object->customer_details->email)) {
            $payerMail = $event->data->object->customer_details->email;
        }
        $status = 'paid';

        $system_transaction_id = $event->data->object->id;

        $priceId = '';
        if (isset($session->line_items->data[0]->price->id)) {
            $priceId = $session->line_items->data[0]->price->id;
        }
        $currency = 'N/A';
        if (isset($session->line_items->data[0]->currency)) {
            $currency = $session->line_items->data[0]->currency;
        }
        $amount = 0;
        if (isset($session->line_items->data[0]->amount_total)) {
            $amount = $session->line_items->data[0]->amount_total / 100;
        }

        if ($server == $stripe_server_name) {
            foreach ($stripe_payments as $i => $pay) {
                if ($priceId == $pay['price_id']) {
                    $payment_config_id = $i . ';' . $pay['price_id'] . ';' . $pay['money_amount'] . ';' . $pay['money_currency'];
                    $premium_points = $pay['premium_points'];
                    break;
                }
            }

            if ($account_id > 0 && $premium_points > 0) {
                $logFile = './custom_scripts/stripe/reported_ids/' . $system_transaction_id . '.log';
                if (!file_exists($logFile)) {
                    $account = new Account($account_id);
                    if ($account->isLoaded()) {
                        $logData = 'accountID:' . $account_id . PHP_EOL .
                            'mail:' . $payerMail . PHP_EOL .
                            'amount:' . $amount . ' ' . $currency . PHP_EOL .
                            'points:' . $premium_points . PHP_EOL .
                            'system_transaction_id:' . $system_transaction_id . PHP_EOL .
                            'addTime:' . $date;
                        if (file_put_contents($logFile, $logData)) {
                            $account->setPremiumPoints($account->getPremiumPoints() + $premium_points);
                            $account->save();
                        } else {
                            http_response_code(400);
                            echo 'Cannot write to file: ' . $logFile;
                            exit();
                        }
                    }
                }
            }
        }
    }
}

http_response_code(200);
exit;
