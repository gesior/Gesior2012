<?php
if(!defined('INITIALIZED'))
	exit;

/*
Script for OTSes that use MySQL (globalevent, type: think, interval: 60 or 60000 [tfs 0.4]):
function onThink()
	if (tonumber(os.date("%d")) ~= getGlobalStorageValue(23456)) then
		setGlobalStorageValue(23456, (tonumber(os.date("%d"))))
		db.executeQuery("UPDATE `players` SET `onlinetime7`=`onlinetime6`, `onlinetime6`=`onlinetime5`, `onlinetime5`=`onlinetime4`, `onlinetime4`=`onlinetime3`, `onlinetime3`=`onlinetime2`, `onlinetime2`=`onlinetime1`, `onlinetime1`=`onlinetimetoday`, `onlinetimetoday`=0;")
		db.executeQuery("UPDATE `players` SET `exphist7`=`exphist6`, `exphist6`=`exphist5`, `exphist5`=`exphist4`, `exphist4`=`exphist3`, `exphist3`=`exphist2`, `exphist2`=`exphist1`, `exphist1`=`experience`-`exphist_lastexp`, `exphist_lastexp`=`experience`;")
	end
	db.executeQuery("UPDATE `players` SET `onlinetimetoday`=`onlinetimetoday`+60, `onlinetimeall`=`onlinetimeall`+60 WHERE `online` = 1;")
end

MySQL schema:
ALTER TABLE `players` ADD `exphist_lastexp` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `exphist1` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `exphist2` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `exphist3` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `exphist4` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `exphist5` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `exphist6` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `exphist7` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetimetoday` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetime1` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetime2` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetime3` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetime4` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetime5` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetime6` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetime7` BIGINT( 20 ) NOT NULL DEFAULT '0',
ADD `onlinetimeall` BIGINT( 20 ) NOT NULL DEFAULT '0';
*/
$limit = 50;
$type = $_REQUEST['type'];
function coloured_value($valuein)
{
	$value2 = $valuein;
	while(strlen($value2) > 3)
	{
		$value .= '.'.substr($value2, -3, 3);
		$value2 = substr($value2, 0, strlen($value2)-3);
	}
	$value = $value2.$value;
	if($valuein > 0)
		return '<font color="green">+'.$value.'</font>';
	elseif($valuein < 0)
		return '<font color="red">'.$value.'</font>';
	else
		return '<font color="black">'.$value.'</font>';
}
if(empty($type))
	$players = $SQL->query('SELECT * FROM ' . $SQL->tableName('players') . ' WHERE ' . $SQL->fieldName('group_id') . ' < 2 ORDER BY ' . $SQL->fieldName('experience') . '-' . $SQL->fieldName('exphist_lastexp') . ' DESC LIMIT ' . $limit)->fetchAll();
elseif($type == "sum")
	$players = $SQL->query('SELECT * FROM ' . $SQL->tableName('players') . ' WHERE ' . $SQL->fieldName('group_id') . ' < 2 ORDER BY ' . $SQL->fieldName('exphist1') . '+' . $SQL->fieldName('exphist2') . '+' . $SQL->fieldName('exphist3') . '+' . $SQL->fieldName('exphist4') . '+' . $SQL->fieldName('exphist5') . '+' . $SQL->fieldName('exphist6') . '+' . $SQL->fieldName('exphist7') . '+' . $SQL->fieldName('experience') . '-' . $SQL->fieldName('exphist_lastexp') . ' DESC LIMIT ' . $limit)->fetchAll();
elseif($type >= 1 && $type <= 7)
	$players = $SQL->query('SELECT * FROM ' . $SQL->tableName('players') . ' WHERE ' . $SQL->fieldName('group_id') . ' < 2 ORDER BY ' . $SQL->fieldName('exphist' . (int) $type) . ' DESC LIMIT '.$limit)->fetchAll();
$main_content .= '<CENTER><H2>Ranking of powergamers</H2></CENTER><BR><TABLE BORDER="0" CELLPADDING="4" CELLSPACING="1" WIDTH="100%"><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD style="color:white"><B>Rank</B></TD><TD style="color:white"><B>Name</B></TD>';
if($type == "sum")
	$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=powergamers&type=sum">7 Days sum</a></center></B></TD>';
else
	$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=powergamers&type=sum">7 Days sum</a></center></B></TD>';
for($i = 7; $i >= 2; $i--)
{
	if($type == $i)
		$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=powergamers&type='.$i.'">'.$i.' Days Ago</a></center></B></TD>';
	else
		$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=powergamers&type='.$i.'">'.$i.' Days Ago</a></center></B></TD>';
}
if($type == 1)
	$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=powergamers&type=1">1 Day Ago</a></center></B></TD>';
else
	$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=powergamers&type=1">1 Day Ago</a></center></B></TD>';
if(empty($type))
	$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=powergamers">Today</a></center></B></TD>';
else
	$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=powergamers">Today</a></center></B></TD>';
$main_content .= '</TR>';
foreach($players as $player)
{
	if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
	$main_content .= '<tr bgcolor="'.$bgcolor.'"><td align="center">'.$number_of_rows.'. </td>';
	if(Player::isPlayerOnline($player['id']))
		$main_content .= '<td><a href="?subtopic=characters&name='.urlencode($player['name']).'"><b><font color="green">'.htmlspecialchars($player['name']).'</font></b></a>';
	else
		$main_content .= '<td><a href="?subtopic=characters&name='.urlencode($player['name']).'"><b><font color="red">'.htmlspecialchars($player['name']).'</font></b></a>';
	$main_content .= '<br />'.$player['level'].' '.htmlspecialchars(Website::getVocationName($player['vocation'])).'</td><td align="right">'.coloured_value($player['exphist1'] + $player['exphist2'] + $player['exphist3'] + $player['exphist4'] + $player['exphist5'] + $player['exphist6'] + $player['exphist7'] + $player['experience'] - $player['exphist_lastexp']).'</td>';
	$main_content .= '<td align="right">'.coloured_value($player['exphist7']).'</td><td align="right">'.coloured_value($player['exphist6']).'</td><td align="right">'.coloured_value($player['exphist5']).'</td><td align="right">'.coloured_value($player['exphist4']).'</td><td align="right">'.coloured_value($player['exphist3']).'</td><td align="right">'.coloured_value($player['exphist2']).'</td><td align="right">'.coloured_value($player['exphist1']).'</td><td align="right">'.coloured_value($player['experience']-$player['exphist_lastexp']).'</td></tr>';
}
$main_content .= '</TABLE>';