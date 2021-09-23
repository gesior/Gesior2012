<?php

const TRADE_DATE_FORMAT = 'Y-m-d H:i:s';

function trade_loginRequiredError() {
    echo 'You must login first!';
}

function trade_messageInfo($text) {
    return '<div class="alert alert-info">' . $text . '</div>';
}

function trade_messageWarning($text) {
    return '<div class="alert alert-warning">' . $text . '</div>';
}

function trade_messageError($text) {
    return '<div class="alert alert-danger">' . $text . '</div>';
}

function trade_messageSuccess($text) {
    return '<div class="alert alert-success">' . $text . '</div>';
}

function trade_getConfig($name) {
    global $config;
    return $config['site'][$name];
}

function trade_getUrl($action = '', $params = []) {
    return '?subtopic=charactertrade&action=' . $action . '&' . http_build_query($params);
}
echo '<link rel="stylesheet" href="css/bootstrap.min.css">';

if (empty($action)) {
    echo '<h2>Player trade offers</h2>';

    echo trade_messageInfo('On this site you can sell and buy characters safely.<br />' .
                              'Purchased character will be transferred to your account.');

    if (Visitor::isLogged()) {
        $accountPlayerTrades = Visitor::getAccount()->getPlayerTrades();
        echo '<h3>Your player trade offers</h3>';
        if ($accountPlayerTrades->count() > 0) {
            echo '<table class="table table-striped">';
            echo '<thead><tr><th>Character name</th><th>Create date</th><th>Price</th><th>Status</th><th></th></tr></thead>';
            echo '<tbody>';
            foreach ($accountPlayerTrades as $accountPlayerTrade) {
                $player = new Player($accountPlayerTrade->getPlayerId());
                if ($player->isLoaded()) {
                    echo '<tr>';
                    echo '<td><a href="?subtopic=characters&name=' . urlencode($player->getName()) . '">' . htmlspecialchars($player->getName()) . '</a></td>';
                    echo '<td>' . date(TRADE_DATE_FORMAT, $accountPlayerTrade->getCreateDate()) . '</td>';
                    echo '<td>' . $accountPlayerTrade->getPriceBuyer() . ' (' . $accountPlayerTrade->getPriceSeller() . ') PP</td>';
                    echo '<td>' . $accountPlayerTrade->getStatusName() . '<br />';
                    echo ($accountPlayerTrade->isPublic() ? '(public)' : '(private)');
                    echo '</td>';
                    echo '<td><a href="' . trade_getUrl('offer_view', ['secretCode' => $accountPlayerTrade->getSecretCode()]) . '">VIEW OFFER</a></td>';
                    echo '</tr>';
                }
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<b>You do not have any offer.</b>';
        }
    }

    echo '<div><a href="' . trade_getUrl('offer_create') . '" class="btn btn-success m-1"">Create offer</a></div>';

    /** @var DatabaseList|PlayerTrade[] $playerTrades */
    $playerTrades = new DatabaseList('PlayerTrade');
    $filterPublic = new SQL_Filter(new SQL_Field('type'), SQL_Filter::EQUAL, PlayerTrade::TYPE_PUBLIC);
    $filterActiveOffer = new SQL_Filter(new SQL_Field('status'), SQL_Filter::EQUAL, PlayerTrade::STATUS_ACTIVE);
    $filter = new SQL_Filter($filterPublic, SQL_Filter::CRITERIUM_AND, $filterActiveOffer);
    $playerTrades->setFilter($filter);
    $playerTrades->addOrder(new SQL_Order(new SQL_Field('id'), SQL_Order::DESC));

    echo '<h3>Public player trade offers</h3>';
    if ($playerTrades->count() > 0) {
        echo '<table class="table table-striped">';
        echo '<thead><tr><th>Character name</th><th>Create date</th><th>Price</th><th>Status</th><th></th></tr></thead>';
        echo '<tbody>';
        foreach ($playerTrades as $playerTrade) {
            $player = new Player($playerTrade->getPlayerId());
            if ($player->isLoaded()) {
                echo '<tr>';
                echo '<td><a href="?subtopic=characters&name=' . urlencode($player->getName()) . '">' . htmlspecialchars($player->getName()) . '</a></td>';
                echo '<td>' . date(TRADE_DATE_FORMAT, $playerTrade->getCreateDate()) . '</td>';
                echo '<td>' . $playerTrade->getPriceBuyer() . ' (' . $playerTrade->getPriceSeller() . ') PP</td>';
                echo '<td>' . $playerTrade->getStatusName() . '</td>';
                echo '<td><a href="' . trade_getUrl('offer_view',  ['secretCode' => $playerTrade->getSecretCode()]) . '">VIEW OFFER</a></td>';
                echo '</tr>';
            }
        }
        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<b>There are no public player trade offers</b>';
    }
} elseif ($action === 'offer_buy') {
    if (!Visitor::isLogged()) {
        trade_loginRequiredError();
        return;
    }

    $secretCode = (string) $_REQUEST['secretCode'];
    $playerTradeId = PlayerTrade::getIdFromSecretCode($secretCode);
    $playerTrade = new PlayerTrade($playerTradeId);

    if (!$playerTrade->isLoaded()) {
        echo trade_messageError('Offer with this ID does not exist.');
        return;
    }

    if (!$playerTrade->isValidSecretCode($secretCode)) {
        echo trade_messageError('Invalid code to offer.');
        return;
    }

    echo '<div class="row"><div class="col-12"><a href="' . trade_getUrl('offer_view', ['secretCode' => $playerTrade->getSecretCode()]) . '" class="btn btn-primary m-1">Back</a></div></div>';

    if (!$playerTrade->isActive()) {
        echo trade_messageError('This offer is not active. It was sold or canceled.');
        return;
    }

    $player = new Player($playerTrade->getPlayerId());
    if (!$player->isLoaded()) {
        echo trade_messageError('Player from that offer does not exist.');
        return;
    }

    if ($player->getAccountId() == Visitor::getAccount()->getId()) {
        echo trade_messageError('You cannot buy your own character.');
        return;
    }

    $csrf = md5(Visitor::getAccount()->getId() . Visitor::getAccount()->getPassword() . Visitor::getIP());

    if (Visitor::getAccount()->getPremiumPoints() < $playerTrade->getPriceBuyer()) {
        echo trade_messageError('This player costs ' . $playerTrade->getPriceBuyer() . ' premium points. You do not have enough.');
        return;
    }

    if (isset($_POST['csrf'])) {
        if ($_POST['csrf'] === $csrf) {
            $removePointsQuery = Website::getDBHandle()->prepare(
                'UPDATE `accounts`' .
                ' SET `premium_points` = `premium_points` - ' . $playerTrade->getPriceBuyer() .
                ' WHERE `id` = ' . Visitor::getAccount()->getId() .
                ' AND `premium_points` >= ' . $playerTrade->getPriceBuyer()
            );
            // zabezpiecza przed odjeciem punktow kiedy konto nie ma dosc punktow (klonowaniem)
            if ($removePointsQuery->execute() && $removePointsQuery->rowCount() > 0) {
                Website::getDBHandle()->query(
                    'UPDATE `accounts`' .
                    ' SET `premium_points` = `premium_points` + ' . $playerTrade->getPriceSeller() .
                    ' WHERE `id` = ' . $playerTrade->getAccountSellerId()
                );
                $player->setAccountID(Visitor::getAccount()->getId());
                $player->save();
                $playerTrade->setAccountBuyerId(Visitor::getAccount()->getId());
                $playerTrade->setStatus(PlayerTrade::STATUS_SOLD);
                $playerTrade->save();

                echo trade_messageSuccess(
                    'You bought player <b>' . htmlspecialchars($player->getName()) . '</b> for <b>' .
                    $playerTrade->getPriceBuyer() . '</b> premium points.<br />' .
                    'Player was transferred to your account.'
                );

            } else {
                echo trade_messageError('Could not remove premium points from your account. Try again or contact with administrator.');
            }

            return;
        } else {
            echo trade_messageError('Invalid CSRF code. Try again.');
        }
    }

    echo '<h1>Buy player</h1>';

    echo trade_messageInfo('Do you really want to buy player <b>' . htmlspecialchars($player->getName()) . '</b> for <b>' . $playerTrade->getPriceBuyer() . '</b> premium points?');

    echo '<form action="' . trade_getUrl('offer_buy', ['secretCode' => $playerTrade->getSecretCode()]) . '" method="post">
        <input type="hidden" name="csrf" value="' . $csrf . '">
        <div class="form-group row">
            <div class="col-sm-9 offset-3">
                <button type="submit" class="btn btn-primary">Buy now</button>
            </div>
        </div>
    </form>
    ';
} elseif ($action === 'offer_cancel') {
    if (!Visitor::isLogged()) {
        trade_loginRequiredError();
        return;
    }

    $secretCode = (string) $_REQUEST['secretCode'];
    $playerTradeId = PlayerTrade::getIdFromSecretCode($secretCode);
    $playerTrade = new PlayerTrade($playerTradeId);

    if (!$playerTrade->isLoaded()) {
        echo trade_messageError('Offer with this ID does not exist.');
        return;
    }

    if (!$playerTrade->isValidSecretCode($secretCode)) {
        echo trade_messageError('Invalid code to offer.');
        return;
    }

    if (!$playerTrade->isActive()) {
        echo trade_messageError('This offer is not active. It was sold or canceled.');
        return;
    }

    $player = new Player($playerTrade->getPlayerId());
    if (!$player->isLoaded()) {
        echo trade_messageError('Player from that offer does not exist.');
        return;
    }

    echo '<div class="row"><div class="col-12"><a href="' . trade_getUrl('') . '" class="btn btn-primary m-1">Back</a></div></div>';

    $csrf = md5(Visitor::getAccount()->getId() . Visitor::getAccount()->getPassword() . Visitor::getIP());

    if (Visitor::getAccount()->getId() != $playerTrade->getAccountSellerId()) {
        echo trade_messageError('This is not your player trade offer. You cannot cancel it.');
        return;
    }

    if (isset($_POST['csrf'])) {
        if ($_POST['csrf'] === $csrf) {
            $playerTrade->setStatus(PlayerTrade::STATUS_CANCELED);
            $playerTrade->save();

            echo trade_messageSuccess(
                'Your player <b>' . htmlspecialchars($player->getName()) . '</b> sell offer for <b>' .
                $playerTrade->getPriceSeller() . '</b> premium points has been canceled.<br />'
            );

            return;
        } else {
            echo trade_messageError('Invalid CSRF code. Try again.');
        }
    }

    echo '<h1>Cancel trade offer</h1>';

    echo trade_messageInfo('Do you really want to cancel player <b>' . htmlspecialchars($player->getName()) . '</b> sell offer?');

    echo '<form action="' . trade_getUrl('offer_cancel', ['secretCode' => $playerTrade->getSecretCode()]) . '" method="post">
        <input type="hidden" name="csrf" value="' . $csrf . '">
        <div class="form-group row">
            <div class="col-sm-9 offset-3">
                <button type="submit" class="btn btn-warning">Cancel offer</button>
            </div>
        </div>
    </form>
    ';
} elseif ($action === 'offer_create') {
    if (!Visitor::isLogged()) {
        trade_loginRequiredError();
        return;
    }

    $configCreateRequireRecoveryKey = trade_getConfig('trade_player_create_require_recovery_key');
    $configMinimumPrice = trade_getConfig('trade_player_minimum_price');
    $configMinimumLevel = trade_getConfig('trade_player_minimum_level');

    $configPublicCommissionFixed = trade_getConfig('trade_player_public_commission_fixed');
    $configPublicCommissionPercent = trade_getConfig('trade_player_public_commission_percent');
    $configPrivateCommissionFixed = trade_getConfig('trade_player_private_commission_fixed');
    $configPrivateCommissionPercent = trade_getConfig('trade_player_private_commission_percent');

    echo '<div class="row"><div class="col-12"><a href="' . trade_getUrl('') . '" class="btn btn-primary m-1">Back</a></div></div>';

    $csrf = md5(Visitor::getAccount()->getId() . Visitor::getAccount()->getPassword() . Visitor::getIP());

    $type = PlayerTrade::TYPE_PUBLIC;
    $price = 100;
    $playerId = 0;

    if ($configCreateRequireRecoveryKey && empty(Visitor::getAccount()->getKey())) {
        echo trade_messageWarning('Only accounts with Recovery Key may create trade offers.');
        return;
    }

    if ($configCreateRequireRecoveryKey && isset($_POST['recovery_key'])) {
        $recoveryKey = (string) $_POST['recovery_key'];
    }

    if (isset($_POST['csrf']) && isset($_POST['player_id']) && isset($_POST['type']) && isset($_POST['price'])) {
        $type = intval($_POST['type']);
        $price = intval($_POST['price']);
        $playerId = intval($_POST['player_id']);
        if ($_POST['csrf'] === $csrf) {
            $player = new Player($_POST['player_id']);
            if ($configCreateRequireRecoveryKey) {
                if ($recoveryKey !== Visitor::getAccount()->getKey()) {
                    echo trade_messageError('Invalid Recovery Key to account.');
                    return;
                }
            }
            if (!$player->isLoaded()) {
                echo trade_messageError('Player does not exist.');
                return;
            }
            if ($player->getAccountId() != Visitor::getAccount()->getId()) {
                echo trade_messageError('Player is not on your account.');
                return;
            }
            if ($player->getLevel() < $configMinimumLevel) {
                echo trade_messageError('Minimum player level is ' . $configMinimumLevel . '.');
                return;
            }
            if ($player->getGroup() != 1) {
                echo trade_messageError('Support team members cannot sell their characters!');
                return;
            }
            if (!in_array($type, [PlayerTrade::TYPE_PUBLIC, PlayerTrade::TYPE_PRIVATE])) {
                echo trade_messageError('Invalid offer type.');
                return;
            }

            $activePlayerTrades = new DatabaseList('PlayerTrade');
            $filterPlayer = new SQL_Filter(new SQL_Field('player_id'), SQL_Filter::EQUAL, $player->getId());
            $filterActiveOffer = new SQL_Filter(new SQL_Field('status'), SQL_Filter::EQUAL, PlayerTrade::STATUS_ACTIVE);
            $filter = new SQL_Filter($filterPlayer, SQL_Filter::CRITERIUM_AND, $filterActiveOffer);
            $activePlayerTrades->setFilter($filter);

            if ($activePlayerTrades->count() == 0) {
                if ($price >= $configMinimumPrice) {
                    $buyerPrice = $price;
                    if ($type == PlayerTrade::TYPE_PUBLIC) {
                        $buyerPrice = $price * (1 + $configPublicCommissionPercent / 100) + $configPublicCommissionFixed;
                    } elseif ($type == PlayerTrade::TYPE_PRIVATE) {
                        $buyerPrice = $price * (1 + $configPrivateCommissionPercent / 100) + $configPrivateCommissionFixed;
                    }

                    $playerTrade = new PlayerTrade();
                    $playerTrade->setAccountSellerId(Visitor::getAccount()->getId());
                    $playerTrade->setPlayerId($player->getId());
                    $playerTrade->setType($type);
                    $playerTrade->setPriceSeller($price);
                    $playerTrade->setPriceBuyer($buyerPrice);
                    $playerTrade->setStatus(PlayerTrade::STATUS_ACTIVE);
                    $playerTrade->setCreateDate(time());
                    $playerTrade->save();

                    echo trade_messageSuccess(
                        'You created player trade offer.<br />' .
                        'Player name: <b>' . htmlspecialchars($player->getName()) . '</b><br />' .
                        'You will receive: <b>' . $playerTrade->getPriceSeller() . ' premium points</b><br />' .
                        'Buyer will pay: <b>' . $playerTrade->getPriceBuyer() . ' premium points</b><br />' .
                        'Link to offer: <a href="' .
                        trade_getUrl('offer_view', ['secretCode' => $playerTrade->getSecretCode()]) . '">' .
                        trade_getUrl('offer_view', ['secretCode' => $playerTrade->getSecretCode()]) . '</a><br />' .
                        '<br />' .
                        'You can play on that player until someone buy it.'
                    );

                    return;
                } else {
                    echo trade_messageError('Minimum price for offer is ' . $configMinimumPrice . '.');
                }
            } else {
                echo trade_messageError('There is another trade offer for this player. You must cancel it first to create new offer.');
            }
        } else {
            echo trade_messageError('Invalid CSRF code. Try again.');
        }
    }

    echo '<h1>Create trade player offer</h1>';

    echo trade_messageInfo('Minimum level of traded player is ' . $configMinimumLevel . '.<br />' .
                              'Minimum price of trade offer is ' . $configMinimumPrice . ' premium points.<br />');

    echo '<form action="' . trade_getUrl('offer_create') . '" method="post">
        <input type="hidden" name="csrf" value="' . $csrf . '">
        <div class="form-group row">
            <div class="col-sm-3">Type</div>
            <div class="col-sm-9">
                <label><input type="radio" name="type" value="' . PlayerTrade::TYPE_PUBLIC . '" ' . (($type == PlayerTrade::TYPE_PUBLIC) ? 'checked' : '') . '/>
                 Public - offer will be on Trade offers list, anyone can buy it</label><br />
                <label><input type="radio" name="type" value="' . PlayerTrade::TYPE_PRIVATE . '" ' . (($type == PlayerTrade::TYPE_PRIVATE) ? 'checked' : '') . '/>
                 Private - only players with secret link to offer can buy it</label>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3">Price</div>
            <div class="col-sm-9">
                <input type="text" name="price" value="' . $price . '" class="form-control" /> Premium Points<br />
                <small>This is value you will receive for player. Buyer will have to pay a little more, because of trade commission.</small>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-3"></div>
            <div class="col-sm-9">';
    echo '<select class="form-control" id="playerInput" name="player_id" required>';
    foreach (Visitor::getAccount()->getPlayers() as $player) {
        if ($player->getLevel() >= $configMinimumLevel) {
            echo '<option value="' . $player->getId() . '" ' . (($player->getId() == $playerId) ? 'selected' : '') . '>' . htmlspecialchars($player->getName()) . '</option>';
        }
    }
    echo '</select>
            </div>
        </div>';

    if ($configCreateRequireRecoveryKey) {
        echo '<div class="form-group row">
            <div class="col-sm-3">Account Recovery Key</div>
            <div class="col-sm-9">
                <input type="text" name="recovery_key" value="" autocomplete="off" class="form-control" />
                <small>Type your Account Recovery Key, to confirm, that you are owner of account.</small>
            </div>
        </div>
        ';
    }

    echo '<div class="form-group row">
            <div class="col-sm-9 offset-3">
                <button type="submit" class="btn btn-primary">Create offer</button>
            </div>
        </div>
    </form>
    ';
} elseif ($action === 'offer_view') {
    $secretCode = (string) $_REQUEST['secretCode'];

    $playerTradeId = PlayerTrade::getIdFromSecretCode($secretCode);
    $playerTrade = new PlayerTrade($playerTradeId);

    echo '<div class="row"><div class="col-12"><a href="' . trade_getUrl('') . '" class="btn btn-primary m-1">Back</a></div></div>';

    if ($playerTrade->isLoaded()) {
        if ($playerTrade->isValidSecretCode($secretCode)) {
            $player = new Player($playerTrade->getPlayerId());
            if ($player->isLoaded()) {
                echo '<h1>Player trade offer</h1>';
                if ($playerTrade->isPrivate()) {
                    echo trade_messageInfo('This is private trade offer. Only users who has link to that site can buy this player. Link to share:<br />' .
                                              '<input type="text" value="' . htmlspecialchars($_SERVER['HTTP_HOST'] . '/' . trade_getUrl('offer_view', ['secretCode' => $playerTrade->getSecretCode()])) . '" class="form-control" />');
                }

                echo '<table class="table table-striped">';
                echo '<tr><td>Name</td><td><a href="?subtopic=characters&name=' . urlencode($player->getName()) . '">' . htmlspecialchars($player->getName()) . '</a></td></tr>';
                echo '<tr><td>Status</td><td>' . $playerTrade->getStatusName() . '</td></tr>';
                echo '<tr>';
                echo '<td>PRICE</td><td>' . $playerTrade->getPriceBuyer() . ' premium points<br />';
                echo '<small>(seller will receive ' . $playerTrade->getPriceSeller() . ' premium points)</small></td>';
                echo '</tr>';
                echo '<tr><td>Level</td><td>' . $player->getLevel() . '</td></tr>';
                echo '<tr><td>Magic Level</td><td>' . $player->getMagLevel() . '</td></tr>';
                echo '<tr><td>Vocation</td><td>' . htmlspecialchars(Website::getVocationName($player->getVocation(), $player->getPromotion())) . '</td></tr>';
                echo '</table>';

                echo '<table class="table table-striped">';
                echo '<tr><td>Skill Fist</td><td>' . $player->getSkill(Highscores::SKILL_FIST) . '</td></tr>';
                echo '<tr><td>Skill Club</td><td>' . $player->getSkill(Highscores::SKILL_CLUB) . '</td></tr>';
                echo '<tr><td>Skill Sword</td><td>' . $player->getSkill(Highscores::SKILL_SWORD) . '</td></tr>';
                echo '<tr><td>Skill Axe</td><td>' . $player->getSkill(Highscores::SKILL_AXE) . '</td></tr>';
                echo '<tr><td>Skill Distance</td><td>' . $player->getSkill(Highscores::SKILL_DISTANCE) . '</td></tr>';
                echo '<tr><td>Skill Shielding</td><td>' . $player->getSkill(Highscores::SKILL_SHIELD) . '</td></tr>';
                echo '<tr><td>Skill Fishing</td><td>' . $player->getSkill(Highscores::SKILL_FISHING) . '</td></tr>';
                echo '</table>';

                echo '<br />';
                echo trade_messageInfo('Player items, depot items and house will be transferred.<br />' .
                                       'You should NOT consider them as part of offer, ' .
                                       'as seller may remove them,<br />before you finalize payment!');
                echo '<div class="row"><div class="col-12">';
                if ($playerTrade->isActive()) {
                    echo '<a href="' . trade_getUrl('offer_buy', ['secretCode' => $playerTrade->getSecretCode()]) . '" class="btn btn-success m-2 float-left"">Buy</a>';
                }
                if (Visitor::isLogged() && $playerTrade->isActive() && $playerTrade->getAccountSellerId() == Visitor::getAccount()->getId()) {
                    echo '<a href="' . trade_getUrl('offer_cancel', ['secretCode' => $playerTrade->getSecretCode()]) . '" class="btn btn-warning m-2 float-left">Cancel Offer</a>';
                }
                echo '</div></div>';
            } else {
                echo trade_messageError('Player from that offer does not exist.');
            }
        } else {
            echo trade_messageError('Invalid code to offer.');
        }
    } else {
        echo trade_messageError('Offer with this ID does not exist.');
    }
}
