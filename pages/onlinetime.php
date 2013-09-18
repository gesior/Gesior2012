<?php
if(!defined('INITIALIZED'))
	exit;

/*
Script (globalevent, type: think, interval: 60 or 60000 [tfs 0.4]):
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
function hours_and_minutes($value, $color = 1)
{
	$hours = floor($value / 3600);
	$value = $value - $hours * 3600;
	$minutes = floor($value / 60);
	if($color != 1)
		return '<font color="black">'.$hours.'h '.$minutes.'m</font>';
	else
		if($hours >= 12)
			return '<font color="red">'.$hours.'h '.$minutes.'m</font>';
		elseif($hours >= 6)
			return '<font color="black">'.$hours.'h '.$minutes.'m</font>';
		else
			return '<font color="green">'.$hours.'h '.$minutes.'m</font>';
}
if(empty($type))
	$players = $SQL->query('SELECT * FROM ' . $SQL->tableName('players') . ' ORDER BY ' . $SQL->fieldName('onlinetimetoday') . ' DESC LIMIT ' . $limit)->fetchAll();
elseif($type == "sum")
	$players = $SQL->query('SELECT * FROM ' . $SQL->tableName('players') . ' ORDER BY ' . $SQL->fieldName('onlinetime1') . '+' . $SQL->fieldName('onlinetime2') . '+' . $SQL->fieldName('onlinetime3') . '+' . $SQL->fieldName('onlinetime4') . '+' . $SQL->fieldName('onlinetime5') . '+' . $SQL->fieldName('onlinetime6') . '+' . $SQL->fieldName('onlinetime7') . '+' . $SQL->fieldName('onlinetimetoday') . ' DESC LIMIT ' . $limit)->fetchAll();
elseif($type >= 1 && $type <= 7)
	$players = $SQL->query('SELECT * FROM ' . $SQL->tableName('players') . ' ORDER BY ' . $SQL->fieldName('onlinetime' . (int) $type) . ' DESC LIMIT ' . $limit)->fetchAll();
$main_content .= '<CENTER><H2>Ranking of no lifers</H2></CENTER><BR><TABLE BORDER="0" CELLPADDING="4" CELLSPACING="1" WIDTH="100%"><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD style="color:white"><B>Rank</B></TD><TD style="color:white"><B>Name</B></TD>';
if($type == "sum")
	$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=onlinetime&type=sum">All online time</a></center></B></TD>';
else
	$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=onlinetime&type=sum">All online time</a></center></B></TD>';
for($i = 7; $i >= 2; $i--)
{
	if($type == $i)
		$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=onlinetime&type='.$i.'">'.$i.' Days Ago</a></center></B></TD>';
	else
		$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=onlinetime&type='.$i.'">'.$i.' Days Ago</a></center></B></TD>';
}
if($type == 1)
	$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=onlinetime&type=1">1 Day Ago</a></center></B></TD>';
else
	$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=onlinetime&type=1">1 Day Ago</a></center></B></TD>';
if(empty($type))
	$main_content .= '<TD bgcolor="red"><b><center><a href="?subtopic=onlinetime">Today</a></center></B></TD>';
else
	$main_content .= '<TD bgcolor="yellow"><b><center><a href="?subtopic=onlinetime">Today</a></center></B></TD>';
$main_content .= '</TR>';
foreach($players as $player)
{
	if(!is_int($number_of_rows / 2)) { $bgcolor = $config['site']['darkborder']; } else { $bgcolor = $config['site']['lightborder']; } $number_of_rows++;
	$main_content .= '<tr bgcolor="'.$bgcolor.'"><td align="center">'.$number_of_rows.'. </td>';
	if(Player::isPlayerOnline($player['id']))
		$main_content .= '<td><a href="?subtopic=characters&name='.urlencode($player['name']).'"><b><font color="green">'.htmlspecialchars($player['name']).'</font></b></a>';
	else
		$main_content .= '<td><a href="?subtopic=characters&name='.urlencode($player['name']).'"><b><font color="red">'.htmlspecialchars($player['name']).'</font></b></a>';
	$main_content .= '<br />'.$player['level'].' '.htmlspecialchars(Website::getVocationName($player['vocation'])).'</td><td align="right">'.hours_and_minutes($player['onlinetime1'] + $player['onlinetime2'] + $player['onlinetime3'] + $player['onlinetime4'] + $player['onlinetime5'] + $player['onlinetime6'] + $player['onlinetime7'] + $player['onlinetimetoday'], 0).'</td>';
	$main_content .= '<td align="right">'.hours_and_minutes($player['onlinetime7']).'</td><td align="right">'.hours_and_minutes($player['onlinetime6']).'</td><td align="right">'.hours_and_minutes($player['onlinetime5']).'</td><td align="right">'.hours_and_minutes($player['onlinetime4']).'</td><td align="right">'.hours_and_minutes($player['onlinetime3']).'</td><td align="right">'.hours_and_minutes($player['onlinetime2']).'</td><td align="right">'.hours_and_minutes($player['onlinetime1']).'</td><td align="right">'.hours_and_minutes($player['onlinetimetoday']).'</td></tr>';
}
$main_content .= '</TABLE>';