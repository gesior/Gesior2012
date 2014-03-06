<?php
if(!defined('INITIALIZED'))
	exit;

$main_content .= '<div style="text-align: center; font-weight: bold;">Top 30 fraggers on ' . htmlspecialchars($config['server']['serverName']) . '</div>
<table border="0" cellspacing="1" cellpadding="4" width="100%">
	<tr bgcolor="' . $config['site']['vdarkborder'] . '">
		<td class="white" style="text-align: center; font-weight: bold;">Name</td>
		<td class="white" style="text-align: center; font-weight: bold;">Frags</td>
	</tr>';

$i = 0;
foreach($SQL->query('SELECT COUNT(1) frags, `players`.`name` FROM `players`, `player_deaths` WHERE `is_player` = 1 AND `player_deaths`.`player_id` = `players`.`id` GROUP BY `killed_by` ORDER BY ' . $SQL->fieldName('frags') . ' DESC, ' . $SQL->fieldName('name') . ' ASC LIMIT 30;') as $player)
{
	$i++;
	$main_content .= '<tr bgcolor="' . (is_int($i / 2) ? $config['site']['lightborder'] : $config['site']['darkborder']) . '">
		<td><a href="?subtopic=characters&name=' . urlencode($player['name']) . '">' . htmlspecialchars($player['name']) . '</a></td>
		<td style="text-align: center;">' . $player['frags'] . '</td>
	</tr>';
}

$main_content .= '</table>';