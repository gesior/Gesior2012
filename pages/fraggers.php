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
foreach($SQL->query('SELECT ' . $SQL->tableName('p') . '.' . $SQL->fieldName('name') . ' AS ' . $SQL->fieldName('name') . ', COUNT(' . $SQL->tableName('p') . '.' . $SQL->fieldName('name') . ') as ' . $SQL->fieldName('frags') . ' FROM ' . $SQL->tableName('killers') . ' k LEFT JOIN ' . $SQL->tableName('player_killers') . ' pk ON ' . $SQL->tableName('k') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('pk') . '.' . $SQL->fieldName('kill_id') . ' LEFT JOIN ' . $SQL->tableName('players') . ' p ON ' . $SQL->tableName('pk') . '.' . $SQL->fieldName('player_id') . ' = ' . $SQL->tableName('p') . '.' . $SQL->fieldName('id') . ' WHERE ' . $SQL->tableName('k') . '.' . $SQL->fieldName('unjustified') . ' = 1 AND ' . $SQL->tableName('k') . '.' . $SQL->fieldName('final_hit') . ' = 1 GROUP BY ' . $SQL->fieldName('name') . ' ORDER BY ' . $SQL->fieldName('frags') . ' DESC, ' . $SQL->fieldName('name') . ' ASC LIMIT 30;') as $player)
{
	$i++;
	$main_content .= '<tr bgcolor="' . (is_int($i / 2) ? $config['site']['lightborder'] : $config['site']['darkborder']) . '">
		<td><a href="?subtopic=characters&name=' . urlencode($player['name']) . '">' . htmlspecialchars($player['name']) . '</a></td>
		<td style="text-align: center;">' . $player['frags'] . '</td>
	</tr>';
}

$main_content .= '</table>';