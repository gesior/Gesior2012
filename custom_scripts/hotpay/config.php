<?php

// it only disables possibility to create new payments, old payments will still add points
$hotpayEnabled = false;

/*
 * Adres do notyfikacji do ustawienia w panelu to: https://twoja-domena.pl/hotpay_report.php
 * Dodać w firewall, Cloudflare itp. IP serwerów HotPay. Lista jest w dokumentacji HotPay.
 * HotPay serwery notyfikacji: https://dokumentacja.hotpay.pl/#inicjalizacja-patnosci
*/

$hotpayHASLO_Z_USTAWIEN = 'haslo_ustawione_w_panelu_hotpay';
$hotpaySEKRET_USLUGI = 'token_wygenerowany_przez_hotpay';
$hotpayNAZWA_USLUGI = 'ustawiona_nazwa_uslugi';

$hotpayADRES_POWROTU = 'https://yourots.com/?subtopic=shopsystem';

$hotpayConfigs = [
    [
        'money_amount' => 1, // PLN
        'premium_points' => 50,
    ],
    [
        'money_amount' => 10, // PLN
        'premium_points' => 550,
    ],
];
