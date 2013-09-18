<?php
if(!defined('INITIALIZED'))
	exit;


$players_deaths = new DatabaseList('PlayerDeath');
$players_deaths->setFilter(new SQL_Filter(new SQL_Field('id', 'players'), SQL_Filter::EQUAL, new SQL_Field('player_id', 'player_deaths')));
$players_deaths->addOrder(new SQL_Order(new SQL_Field('time'), SQL_Order::DESC));
$players_deaths->setLimit(50);
$players_deaths_count = 0;

foreach($players_deaths as $death)
{
	$bgcolor = (($players_deaths_count++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
	$players_rows .= '<TR BGCOLOR="'.$bgcolor.'"><TD WIDTH="30"><center>'.$players_deaths_count.'.</center></TD><TD WIDTH="125"><small>'.date("j.m.Y, G:i:s",$death->getTime()).'</small></TD><TD><a href="index.php?subtopic=characters&name=' . urlencode($death->data['name']) . '">' . htmlspecialchars($death->data['name']) . '</a> at level ' . $death->getLevel() . ' by ' . $death->getKillerString();
	if($death->getMostDamageString() != '' && $death->getKillerString() != $death->getMostDamageString())
		$players_rows .= ' and ' . $death->getMostDamageString();
	$players_rows .= '</TD></TR>';
}
if($players_deaths_count == 0)
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Last Deaths</B></TD></TR><TR BGCOLOR='.$config['site']['darkborder'].'><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>No one died on '.htmlspecialchars($config['server']['serverName']).'.</TD></TR></TABLE></TD></TR></TABLE><BR>';
else
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Last Deaths</B></TD></TR></TABLE><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>'.$players_rows.'</TABLE>';