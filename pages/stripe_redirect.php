<?php

if (!$logged) {
    echo 'You are not logged in. You cannot buy points.';
    return;
}

$id = (int)$_GET['id'];

require_once('./custom_scripts/stripe/config.php');
require_once('./vendor/autoload.php');

if (!isset($stripe_payments[$id])) {
    echo 'Invalid config!';
    return;
}
$stripe_payment = $stripe_payments[$id];

\Stripe\Stripe::setApiKey($stripe_secret_key);

$custom = Visitor::getAccount()->getId() . '_' . $stripe_server_name;

$checkout_session = \Stripe\Checkout\Session::create([
    'line_items' => [
        [
            'price' => $stripe_payment['price_id'],
            'quantity' => 1,
        ]
    ],
    'metadata' => [
        'custom' => $custom,
    ],
    'mode' => 'payment',
    'success_url' => $stripe_return_url,
    'cancel_url' => $stripe_return_url,
]);

header("HTTP/1.1 303 See Other");
header("Location: " . $checkout_session->url);

$main_content .= '<center><h3 class="panel-title">Stripe - Click to start payment</h3>';
$main_content .= '<form action="' . $checkout_session->url . '" id="startPayment" method="get">';

$main_content .= '<input class="btn btn-login btn-success dropdown-toggle" value="REDIRECTING YOU TO STRIPE SITE" type="submit">
</form></center><script>$("#startPayment").submit()</script>';
