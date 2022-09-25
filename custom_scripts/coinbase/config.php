<?php

// IT IS https://commerce.coinbase.com/ integration, not https://coinbase.com/

/*
 * This payment integration is free. I spent much time and money on testing all possible crypto related hacks.
 * If you are using it on your OTS, would be nice, if you send me some $$$ for pizza :)
 * Donations:
 * - PayPal: jerzy@skalski.at
 * - Crypto: https://commerce.coinbase.com/checkout/d954f7c1-6914-4a6d-add1-f083a25fde84
 * - More information: https://donate.ots.me
 */

// it only disables possibility to create new payments, old payments will still add points
$coinbaseEnabled = true;

// API key: https://beta.commerce.coinbase.com/settings/security (click 'New API key')
$coinbaseApiKey = '';

// shared secret: https://beta.commerce.coinbase.com/settings/notifications (click grey button 'Show shared secret')
// on same page you can add your OTS website URL, to receive automatic payment notifications,
// click 'Add new endpoint' and set URL to 'https://yourdomain.com/coinbase_report.php'
$coinbaseSharedSecretForNotifications = '';

// if your server does not get notifications and in webserver logs you got errors '[Coinbase::report] Invalid HMAC',
// you can disable verification, it won't compromise security - integration calls API to verify every notification
$coinbaseValidateNotificationsSecret = true;

$coinbaseCurrency = 'USD';
// coinbase.com transfers have limited minimum value, 1 USD is too low, 2 USD works
$coinbaseMinimumPayment = 2;
$coinbaseMaximumPayment = 500;

// amount of points for 1 '$coinbaseCurrency' ex. 1 USD * 10 = player gets 10 points for 1 USD
$coinbasePremiumPointsMultiplier = 10;
