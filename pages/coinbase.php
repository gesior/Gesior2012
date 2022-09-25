<?php

require_once('./custom_scripts/coinbase/config.php');

if (!Visitor::isLogged()) {
    $main_content .= 'You must login first.';
    return;
}
/** @var Account $account */
$account = Visitor::getAccount();

if ($action == '') {
    if (!$coinbaseEnabled) {
        echo '<div class="cryptoError">New crypto payments are disabled.</div>';
    }
    if ($coinbaseEnabled && isset($_POST['amount'])) {
        $amountString = str_replace(',', '.', $_POST['amount']);
        $amount = floatval($amountString);
        if ($amount >= $coinbaseMinimumPayment) {
            if ($amount <= $coinbaseMaximumPayment) {
                $points = $amount * $coinbasePremiumPointsMultiplier;
                if ($points >= 1) {
                    $payments = CoinbasePayment::getByAccount($account, 10);
                    /** @var CoinbasePayment $lastPayment */
                    $lastPayment = $payments->getResult(9);
                    if (!$lastPayment || $lastPayment->getCreatedAt() < time() - 600) {
                        $coinbase = new Coinbase($coinbaseApiKey);
                        $payment = $coinbase->createPayment($points, $amount, $coinbaseCurrency, $account);
                        if ($payment) {
                            $url = '?subtopic=coinbase&action=view&code=' . $payment->getCode();
                            echo '<a href="' . $url . '">Click to go to payment</a>';
                            header('Location: ' . $url);
                            exit;
                        } else {
                            echo '<div class="cryptoError">Failed to create payment. Try again.</div>';
                        }
                    } else {
                        echo '<div class="cryptoError">You have created 10 Coinbase payments within 10 minutes.' .
                            '</div>';
                    }
                } else {
                    echo '<div class="cryptoError">You must buy at least 1 point.</div>';
                }
            } else {
                echo '<div class="cryptoError">You cannot transfer more than ' . $coinbaseMaximumPayment . ' ' .
                    $coinbaseCurrency . '.</div>';
            }
        } else {
            echo '<div class="cryptoError">You must transfer at least ' . $coinbaseMinimumPayment . ' ' .
                $coinbaseCurrency . '.</div>';
        }
    }

    echo '<style>.cryptoError { font-size: 18px; color: red;}</style>';
    echo '<a href="?subtopic=coinbase&action=list" style="float:right">VIEW YOUR PAYMENTS LIST</a>';
    if ($coinbaseEnabled) {
        echo '<h1>Coinbase crypto payments</h1>';
        echo '<h3>For every transferred 1 ' . $coinbaseCurrency . ', you will receive ' .
            $coinbasePremiumPointsMultiplier . ' premium points.</h3>';
        echo 'We accept Bitcoin, Ethereum and many more!<br />';
        echo '<form action="?subtopic=coinbase" method="POST">' .
            '<b>Type how much crypto you want to transfer: <input type="text" name="amount" value="5.00" /> ' .
            $coinbaseCurrency . '</b> <input type="submit" value="Pay with crypto" /></form>';
    }
} elseif ($action == 'list') {
    echo '<a href="?subtopic=coinbase" style="float:right">CREATE NEW PAYMENT</a>';
    echo '<h2>Coinbase payments list</h2>';

    $limit = $account->getPageAccess() >= 3 ? 1000 : 100;
    if ($account->getPageAccess() >= 3) {
        echo '<b>FILTER: </b>';
        echo '<a href="?subtopic=coinbase&action=list">ALL</a>, ';
        echo '<a href="?subtopic=coinbase&action=list&type=completed">COMPLETED</a>, ';
        echo '<a href="?subtopic=coinbase&action=list&type=unresolved">UNRESOLVED</a>, ';
        echo '<a href="?subtopic=coinbase&action=list&type=resolved">RESOLVED</a>, ';
        echo '<a href="?subtopic=coinbase&action=list&type=refunded">REFUNDED</a><br />';

        $type = (string)$_GET['type'];
        $payments = CoinbasePayment::getAll($type, $limit);
    } else {
        $payments = CoinbasePayment::getByAccount($account, $limit);
    }

    if (count($payments) == 0) {
        echo 'No payments';
    } else {
        echo '<div>Showing last ' . $limit . ' payments.</div><br />';
        echo '<table border="0" cellspacing="1" cellpadding="4" width="100%">';
        echo '<tr>';
        echo '<th>Created</th>';
        echo '<th>Code</th>';
        echo '<th>Amount</th>';
        if ($account->getPageAccess() >= 3) {
            echo '<th>Delivered</th>';
        }
        echo '<th>Status</th>';
        echo '<th>View</th>';
        echo '</tr>';
        foreach ($payments as $i => $payment) {
            $bgColor = (($i % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
            echo '<tr bgcolor="' . $bgColor . '">';
            echo '<td>' . date('Y-m-d H:i:s', $payment->getCreatedAt()) . '</td>';
            echo '<td>' . $payment->getCode() . '</td>';
            echo '<td>' . $payment->getAmount() . ' ' . $payment->getCurrency() . ' - ' .
                $payment->getPoints() . ' points</td>';
            if ($account->getPageAccess() >= 3) {
                echo '<td>' . $payment->getPointsDelivered() . ' points</td>';
            }
            echo '<td>' . $payment->getStatus() . '</td>';
            echo '<td><a href="?subtopic=coinbase&action=view&code=' . $payment->getCode() . '">Details</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    }
} elseif ($action == 'view') {
    echo '<a href="?subtopic=coinbase&action=list" style="float:right">VIEW YOUR PAYMENTS LIST</a>';

    if (isset($_REQUEST['code']) && !empty($_REQUEST['code'])) {
        $code = $_REQUEST['code'];
        $coinbasePayment = new CoinbasePayment($code, CoinbasePayment::LOADTYPE_CODE);
        if ($coinbasePayment->isLoaded()) {
            if ($coinbasePayment->getAccount()->getID() == $account->getID() || $account->getPageAccess() >= 3) {
                if ($account->getPageAccess() >= 3) {
                    if (isset($_REQUEST['update_status'])) {
                        $coinbase = new Coinbase($coinbaseApiKey);
                        if (!$coinbase->updatePaymentStatus($coinbasePayment)) {
                            echo 'Failed to load status!';
                        }
                    }
                    if (isset($_REQUEST['delivered_points']) && $coinbasePayment->getStatus() == 'RESOLVED') {
                        $points = (int) $_REQUEST['delivered_points'];
                        if ($points > $coinbasePayment->getPoints() * 10) {
                            echo '<div style="font-size: 18px; color: red">You cannot add more than 10 times the number of points for a given transaction</div>';
                        } elseif ($coinbasePayment->updatePointsDelivered($points)) {
                            echo '<div style="font-size: 18px; color: darkgreen">Updated delivered points to: ' .
                                $points . '</div>';
                        } else {
                            echo '<div style="font-size: 18px; color: red">Failed to update delivered points</div>';
                        }
                    }
                }

                $i = 0;
                echo '<h2>Payment ID: ' . $coinbasePayment->getCode() . '</h2>';
                echo '<h3>Status: ' . $coinbasePayment->getStatus() . '<br />';
                if ($account->getPageAccess() >= 3) {
                    echo '<small>(Status updates automatically. If you think it is not up-to-date, ' .
                        '<a href="?subtopic=coinbase&action=view&code=' . $coinbasePayment->getCode() .
                        '&update_status">click here</a>)</small>';
                }
                echo '</h3>';
                echo '<table border="0" cellspacing="1" cellpadding="4" width="100%">';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Premium Points:</td>';
                echo '<td>' . $coinbasePayment->getPoints() . '</td>';
                echo '<td style="font-weight: bold">Premium Points delivered:</td>';
                if ($account->getPageAccess() >= 3 && $coinbasePayment->getStatus() == 'RESOLVED') {
                    echo '<td><b>RESOLVED PAYMENT</b><br />You must update points manually:<br />';
                    echo '<form action="?subtopic=coinbase&action=view&code=' . $coinbasePayment->getCode() .
                        '" method="POST">';
                    echo '<input type="text" name="delivered_points" value="' . $coinbasePayment->getPointsDelivered() .
                        '" /> ';
                    echo '<input type="submit" value="Update points" /></form>';
                    echo 'You can only increase amount of points.<br />';
                    echo 'It only adds difference to player\' account.<br />';
                    echo 'Example: If you update 50 points to 70,<br />';
                    echo 'only 20 points will be added to player\'s account.</td>';
                } else {
                    echo '<td>' . $coinbasePayment->getPointsDelivered() . '</td>';
                }
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Amount:</td>';
                echo '<td>' . $coinbasePayment->getAmount() . ' ' . $coinbasePayment->getCurrency() . '</td>';
                echo '<td style="font-weight: bold">Created:</td>';
                echo '<td>' . date('Y-m-d H:i:s', $coinbasePayment->getCreatedAt()) . '</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Expires:</td>';
                echo '<td>' . date('Y-m-d H:i:s', $coinbasePayment->getExpiresAt()) . '</td>';
                echo '<td style="font-weight: bold">Updated:</td>';
                echo '<td>' . date('Y-m-d H:i:s', $coinbasePayment->getUpdatedAt()) . '</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Pay using CoinBase.com:</td>';
                echo '<td colspan="3" style="vertical-align: middle;font-size: 18px">' .
                    '<a href="' . $coinbasePayment->getPaymentUrl() . '" target="_blank">' .
                    $coinbasePayment->getPaymentUrl() . '</h3></td>';
                echo '</tr>';

                echo '</table>';
                echo '<h2>Pay using crypto</h2>';
                echo '<table border="0" cellspacing="1" cellpadding="4" width="100%">';
                foreach (json_decode($coinbasePayment->getPaymentData(), true) as $i => $cryptoPayment) {
                    $bgColor = (($i % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                    echo '<tr bgcolor="' . $bgColor . '">';
                    echo '<td style="font-weight: bold">' . $cryptoPayment['currency'] . '</td>';
                    echo '<td><input type="text" size="42" value="' . $cryptoPayment['wallet'] . '"></td>';
                    echo '<td style="font-weight: bold">' . $cryptoPayment['currency'] . ' amount</td>';
                    echo '<td><input type="text" value="' . $cryptoPayment['amount'] . '"></td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '<h3>Payment operations</h3>';
                echo '<table border="0" cellspacing="1" cellpadding="4" width="100%">';
                echo '<tr>';
                echo '<th>Date</th>';
                echo '<th>Status</th>';
                echo '<th>Transaction</th>';
                echo '</tr>';
                foreach (json_decode($coinbasePayment->getPaymentTimeline(), true) as $i => $timeline) {
                    $bgColor = (($i % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                    echo '<tr bgcolor="' . $bgColor . '">';
                    $date = strtotime($timeline['time']);
                    echo '<td>' . date('Y-m-d H:i:s', $date) . '</td>';
                    echo '<td>' . $timeline['status'];
                    if (isset($timeline['context'])) {
                        echo ' - ' . $timeline['context'];
                    }
                    echo '</td>';
                    if (isset($timeline['payment'])) {
                        echo '<td>' . $timeline['payment']['network'] . ': ' . $timeline['payment']['transaction_id'] .
                            '<br />' . $timeline['payment']['value']['amount'] .
                            ' ' . $timeline['payment']['value']['currency'] . '</td>';
                    } else {
                        echo '<td>-</td>';
                    }
                    echo '</tr>';
                }
                echo '</table>';

                $i = 0;
                echo '<h3>Statuses description</h3>';
                echo '<table border="0" cellspacing="1" cellpadding="4" width="100%">';
                echo '<tr>';
                echo '<th>Status</th>';
                echo '<th>Description</th>';
                echo '</tr>';
                echo '<tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">New</td>';
                echo '<td>Waiting for payment.</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Pending</td>';
                echo '<td>Coinbase detected your crypto transfer. It\'s waiting for network confirmation.<br />' .
                    'For some crypto currencies it can take up to 1 hour.</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Completed</td>';
                echo '<td>Your crypto transfer is confirmed. Premium points delivered.</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Expired</td>';
                echo '<td>You had 60 minutes to start crypto transfer. If you want to buy points, ' .
                    'you should <a href="?subtopic=coinbase">create new payment</a>.<br />' .
                    'If you transfer to "Expired" wallet, admin of OTS will have to manually accept your payment.</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Unresolved</td>';
                echo '<td>Something wrong went with your crypto payment. OTS admin must accept it manually.<br />' .
                    '<b>Delayed</b> - payment made after the 60 minutes has passed<br />' .
                    '<b>Overpaid</b> - paid more than requested amount<br />' .
                    '<b>Underpaid</b> - paid less than requested amount<br />' .
                    '<b>Multiple</b> - more than one payment made<br />' .
                    '<b>Other</b> - unknown payment issue' .
                    '</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Resolved</td>';
                echo '<td>OTS admin accepted your payment. Premium points will be delivered soon.</td>';
                echo '</tr>';
                $bgColor = (($i++ % 2 == 1) ? $config['site']['darkborder'] : $config['site']['lightborder']);
                echo '<tr bgcolor="' . $bgColor . '">';
                echo '<td style="font-weight: bold">Refunded</td>';
                echo '<td>Your crypto funds were sent back to wallet you provided.</td>';
                echo '</tr>';
                echo '</table>';
            } else {
                echo 'This payment is not assigned to your account.';
            }
        } else {
            echo 'Payment not found.';
        }
    }
}

