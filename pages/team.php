<?php
if(!defined('INITIALIZED'))
	exit;

$list = $SQL->query('SELECT ' . $SQL->fieldName('name') . ', ' . $SQL->fieldName('online') . ', ' . $SQL->fieldName('group_id') . ', ' . $SQL->fieldName('world_id') . ' FROM ' . $SQL->tableName('players') . ' WHERE ' . $SQL->fieldName('group_id') . ' IN (' . implode(',', $config['site']['groups_support']) . ') ORDER BY ' . $SQL->fieldName('group_id') . ' DESC');

$main_content .= '<center><h2>Support in game</h2></center>';
$main_content .= "<table border=0 cellspacing=1 cellpadding=4 width=100%>
	<tr bgcolor=\"".$config['site']['vdarkborder']."\">
	<td width=\"20%\"><font class=white><b>Group</b></font></td>
	<td width=\"45%\"><font class=white><b>Name</b></font></td>
	<td width=\"15%\"><font class=white><b>Status</b></font></td>
	<td width=\"20%\"><font class=white><b>World</b></font></td>";
foreach($list as $i => $supporter)
{
	if($supporter['online'] == 0)
		$player_list_status = '<font color="red">Offline</font>';
	else
		$player_list_status = '<font color="green">Online</font>';
	$bgcolor = (($i++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
	$main_content .= '<tr bgcolor="'.$bgcolor.'"><td>' . htmlspecialchars(Website::getGroupName($supporter['group_id'])) . '</td><td><a href="?subtopic=characters&name='.urlencode($supporter['name']).'">'.htmlspecialchars($supporter['name']).'</a></td><td>'.$player_list_status.'</td><td>'.htmlspecialchars($config['site']['worlds'][$supporter['world_id']]).'</td></tr>';
}

$main_content .= "</table>";