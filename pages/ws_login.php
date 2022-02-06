<?php

header('Content-Type: application/json');

if (!defined('INITIALIZED')) {
    exit;
}

# error function
function sendError($msg)
{
    $ret = [];
    $ret["errorCode"] = 3;
    $ret["errorMessage"] = $msg;
    echo json_encode($ret);
}

# event schedule function
function parseEvent($table1, $date, $table2, $bool)
{
    if ($table1) {
        if ($date) {
            if ($table2) {
                $date = $table1->getAttribute('startdate');
                return date_create("{$date}")->format('U');
            } else {
                $date = $table1->getAttribute('enddate');
                return date_create("{$date}")->format('U');
            }
        } else {
            foreach ($table1 as $attr) {
                if ($attr) {
                    if ($bool) {
                        if (intval($attr->getAttribute($table2)) > 0) {
                            return true;
                        }
                        return false;
                    }
                    return $attr->getAttribute($table2);
                }
            }
        }
    }
    return;
}

$request = file_get_contents('php://input');
$result = json_decode($request);
$action = isset($result->type) ? $result->type : '';

switch ($action) {
    case 'cacheinfo':
        $playersonline = $SQL->query("select count(1) from `players_online`")->fetchAll();
        echo json_encode(
            [
                'playersonline' => (intval($playersonline[0][0])),
                'twitchstreams' => 0,
                'twitchviewer' => 0,
                'gamingyoutubestreams' => 0,
                'gamingyoutubeviewer' => 0
            ]
        );
        exit;
    case 'eventschedule':
        $eventlist = array();
        $lastupdatetimestamp = time();
        $file_path = Website::getWebsiteConfig()->getValue('serverPath') . 'data/XML/events.xml';
        if (!Website::fileExists($file_path)) {
            echo json_encode([]);
            exit;
        }
        $xml = new DOMDocument;
        $xml->load($file_path);
        $tableevent = $xml->getElementsByTagName('event');
        foreach ($tableevent as $event) {
            if ($event) {
                $tmplist = array();
                $tmplist['colorlight'] = parseEvent($event->getElementsByTagName('colors'), false, 'colorlight', false);
                $tmplist['colordark'] = parseEvent($event->getElementsByTagName('colors'), false, 'colordark', false);
                $tmplist['description'] = parseEvent(
                    $event->getElementsByTagName('description'),
                    false,
                    'description',
                    false
                );
                $tmplist['displaypriority'] = (intval(
                    parseEvent($event->getElementsByTagName('details'), false, 'displaypriority', false)
                ));
                $tmplist['enddate'] = (intval(parseEvent($event, true, false, false)));
                $tmplist['isseasonal'] = parseEvent($event->getElementsByTagName('details'), false, 'isseasonal', true);
                $tmplist['name'] = $event->getAttribute('name');
                $tmplist['startdate'] = (intval(parseEvent($event, true, true, false)));
                $tmplist['specialevent'] = (intval(
                    parseEvent($event->getElementsByTagName('details'), false, 'specialevent', false)
                ));
                $eventlist[] = $tmplist;
            }
        }
        echo json_encode(compact('eventlist', 'lastupdatetimestamp'));
        exit;
    case 'boostedcreature':
        $boostDB = $SQL->query("select * from " . $SQL->tableName('boosted_creature'))->fetchAll();
        foreach ($boostDB as $Tableboost) {
            echo json_encode(
                [
                    'boostedcreature' => true,
                    'raceid' => intval($Tableboost['raceid'])
                ]
            );
        }
        exit;
    case 'login':

        $characters = [];
        $account = null;

        // common columns
        $columns = 'name, level, sex, vocation, looktype, lookhead, lookbody, looklegs, lookfeet, lookaddons, lastlogin';

        $account = new Account();
        $account->loadByEmail($result->email);
        $current_password = Website::encryptPassword($result->password);
        if (!$account->isLoaded() || !$account->isValidPassword($result->password)) {
            sendError('Email or password is not correct. ' . (int)$account->isLoaded() . $result->email);
            exit;
        }

        $port = Website::getServerConfig()->getValue('gameProtocolPort');

        $response = [
            'playdata' => [
                'worlds' => [
                    [
                        'id' => 0,
                        'name' => Website::getServerConfig()->getValue('serverName'),
                        'externaladdress' => Website::getServerConfig()->getValue('ip'),
                        'externalport' => $port,
                        'externaladdressprotected' => Website::getServerConfig()->getValue('ip'),
                        'externalportprotected' => $port,
                        'externaladdressunprotected' => Website::getServerConfig()->getValue('ip'),
                        'externalportunprotected' => $port,
                        'previewstate' => 0,
                        'location' => 'EUR',
                        'anticheatprotection' => false,
                        'pvptype' => array_search(
                            Website::getServerConfig()->getValue('worldType'),
                            ['pvp', 'no-pvp', 'pvp-enforced']
                        ),
                        'istournamentworld' => false,
                        'restrictedstore' => false,
                        'currenttournamentphase' => 2
                    ]
                ],
                'characters' => [],
            ],
            'session' => [
                'sessionkey' => "$result->email\n$result->password",
                'lastlogintime' => $account->getLastLogin(),
                'ispremium' => Website::getServerConfig()->getValue('freePremium') || $account->isPremium(),
                'premiumuntil' => $account->getPremiumEndsAt(),
                'status' => 'active', // active, frozen or suspended
                'returnernotification' => false,
                'showrewardnews' => true,
                'isreturner' => true,
                'fpstracking' => false,
                'optiontracking' => false,
                'tournamentticketpurchasestate' => 0,
                'emailcoderequest' => false
            ]
        ];

        foreach ($account->getPlayers() as $player) {
            $response['playdata']['characters'][] = [
                'worldid' => 0,
                'name' => $player->getName(),
                'ismale' => intval($player->getSex()) === 1,
                'tutorial' => false,
                'level' => intval($player->getLevel()),
                'vocation' => Website::getVocationName($player->getVocation()),
                'outfitid' => intval($player->getLookType()),
                'headcolor' => intval($player->getLookHead()),
                'torsocolor' => intval($player->getLookBody()),
                'legscolor' => intval($player->getLookLegs()),
                'detailcolor' => intval($player->getLookFeet()),
                'addonsflags' => intval($player->getLookBody()),
                'ishidden' => 0,
                'istournamentparticipant' => false,
                'ismaincharacter' => true,
                'dailyrewardstate' => 0,
                'remainingdailytournamentplaytime' => 0
            ];
        }

        echo(json_encode($response));
        exit;

    default:
        sendError("Unrecognized event {$action}.");
        exit;
}
