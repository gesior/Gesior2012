<?php
if(!defined('INITIALIZED'))
	exit;

$playerNamelocks = new DatabaseList('PlayerNamelocks');
$playerNamelocks->addOrder(new SQL_Order(new SQL_Field('date'), SQL_Order::DESC));

$main_content .= '<center><h2>Namelocks list</h2></center>';
$main_content .= "<table border=0 cellspacing=1 cellpadding=4 width=100%>
	<tr bgcolor=\"".$config['site']['vdarkborder']."\">
	<td><font class=white><b>Current Name</b></font></td>
	<td><font class=white><b>Old Names</b></font></td>";
if(count($playerNamelocks) > 0)
{
	$playersNamelocksInfo = array();
	foreach($playerNamelocks as $namelock)
	{
		if(!isset($playersNamelocksInfo[$namelock->getID()]))
		{
			$playersNamelocksInfo[$namelock->getID()] = array();
			$playersNamelocksInfo[$namelock->getID()]['name'] = $namelock->getNewName();
			$playersNamelocksInfo[$namelock->getID()]['oldNames'] = array();
			$playersNamelocksInfo[$namelock->getID()]['oldNames'][] = $namelock;
		}
		else
			$playersNamelocksInfo[$namelock->getID()]['oldNames'][] = $namelock;
	}
	$old_names_text = array();
	foreach($playersNamelocksInfo as $playerInfo)
	{
		$old_names_text = array();
		foreach($playerInfo['oldNames'] as $oldName)
		{
			$old_names_text[] = 'until ' . date("j F Y, g:i a", $oldName->getDate()) . ' known as <b>' . htmlspecialchars($oldName->getName()) . '</b>';
		}
		$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
		$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="vertical-align:top"><a href="?subtopic=characters&name=' . urlencode($playerInfo['name']) . '">' . htmlspecialchars($playerInfo['name']) . '</a></td><td>' . implode('<br />', $old_names_text) . '</td></tr>';
	}
}
else
{
	$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
	$main_content .= '<tr bgcolor="' . $bgcolor . '"><td colspan="2">No one changed name on ' . $config['server']['serverName'] . '.</td></tr>';
}

$main_content .= "</table>";