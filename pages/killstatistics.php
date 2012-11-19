<?php
if(!defined('INITIALIZED'))
	exit;

$player_deaths = $SQL->query('SELECT ' . $SQL->fieldName('player_id') . ', ' . $SQL->fieldName('time') . ', ' . $SQL->fieldName('level') . ', ' . $SQL->fieldName('killed_by') . ', ' . $SQL->fieldName('is_player') . ' FROM ' . $SQL->tableName('player_deaths') . ' ORDER BY ' . $SQL->fieldName('time') . ' DESC LIMIT '.$config['site']['last_deaths_limit'])->fetchAll();
$players_deaths_count = 0;
if(!empty($players_deaths))
{
	foreach($players_deaths as $death)
	{
		$bgcolor = (($players_deaths_count++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
		$players_rows .= "<tr bgcolor=\"".$bgcolor."\"><td width=\"20%\" align=\"center\">".date("j M Y, H:i", $death['time'])."</td><td>";
		$players_rows .= "<td>killed at level " . $death['level'] . " by ";
		if($death['is_player'] == 0)
		{
			$players_rows .= htmlspecialchars($death['killed_by']);
		}
		else
		{
			$players_rows .= '<a href="?subtopic=characters&name=' . urlencode($death['killed_by']) . '">' . htmlspecialchars($death['killed_by']) . '</a>';
		}
		$players_rows .= "</td></tr>";
	}
}

if($players_deaths_count == 0)
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Last Deaths</B></TD></TR><TR BGCOLOR='.$config['site']['darkborder'].'><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>No one died on '.htmlspecialchars($config['server']['serverName']).'.</TD></TR></TABLE></TD></TR></TABLE><BR>';
else
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Last Deaths</B></TD></TR></TABLE><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>'.$players_rows.'</TABLE>';