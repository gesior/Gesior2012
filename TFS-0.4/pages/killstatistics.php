<?php
if(!defined('INITIALIZED'))
	exit;

$players_deaths = $SQL->query('SELECT ' . $SQL->tableName('player_deaths') . '.' . $SQL->fieldName('id') . ', ' . $SQL->tableName('player_deaths') . '.' . $SQL->fieldName('date') . ', ' . $SQL->tableName('player_deaths') . '.' . $SQL->fieldName('level') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ', ' . $SQL->tableName('players') . '.' . $SQL->fieldName('world_id') . ' FROM ' . $SQL->tableName('player_deaths') . ' LEFT JOIN ' . $SQL->tableName('players') . ' ON ' . $SQL->tableName('player_deaths') . '.' . $SQL->fieldName('player_id') . ' = ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' ORDER BY ' . $SQL->fieldName('date') . ' DESC LIMIT '.$config['site']['last_deaths_limit']);
$players_deaths_count = 0;
if(!empty($players_deaths))
{
	foreach($players_deaths as $death)
	{
		$bgcolor = (($players_deaths_count++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);

		$players_rows .= '<TR BGCOLOR="'.$bgcolor.'"><TD WIDTH="30"><center>'.$players_deaths_count.'.</center></TD><TD WIDTH="125"><small>'.date("j.m.Y, G:i:s",$death['date']).'</small></TD><TD><a href="?subtopic=characters&name='.urlencode($death['name']).'"><b>'.htmlspecialchars($death['name']).'</b></a> ';

		$killers = $SQL->query('SELECT ' . $SQL->tableName('environment_killers') . '.' . $SQL->fieldName('name') . ' AS monster_name, ' . $SQL->tableName('players') . '.' . $SQL->fieldName('name') . ' AS player_name, ' . $SQL->tableName('players') . '.' . $SQL->tableName('deleted') . ' AS player_exists FROM ' . $SQL->tableName('killers') . ' LEFT JOIN ' . $SQL->tableName('environment_killers') . ' ON ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('environment_killers') . '.' . $SQL->fieldName('kill_id') . ' LEFT JOIN ' . $SQL->tableName('player_killers') . ' ON ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('player_killers') . '.' . $SQL->fieldName('kill_id') . ' LEFT JOIN ' . $SQL->tableName('players') . ' ON ' . $SQL->tableName('players') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('player_killers') . '.' . $SQL->fieldName('player_id') . ' WHERE ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('death_id') . ' = ' . $SQL->quote($death['id']) . ' ORDER BY ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('final_hit') . ' DESC, ' . $SQL->tableName('killers') . '.' . $SQL->fieldName('id') . ' ASC')->fetchAll();

		$i = 0;
		$count = count($killers);
		foreach($killers as $killer)
		{
			$i++;
			if($i == 1)
			{
				if($count <= 4)
					$players_rows .= "killed at level <b>".$death['level']."</b> by ";
				elseif($count > 4 and $count < 10)
					$players_rows .= "slain at level <b>".$death['level']."</b> by ";
				elseif($count > 9 and $count < 15)
					$players_rows .= "crushed at level <b>".$death['level']."</b> by ";
				elseif($count > 14 and $count < 20)
					$players_rows .= "eliminated at level <b>".$death['level']."</b> by ";
				elseif($count > 19)
					$players_rows .= "annihilated at level <b>".$death['level']."</b> by ";
			}
			elseif($i == $count)
				$players_rows .= " and ";
			else
				$players_rows .= ", ";

			if($killer['player_name'] != "")
			{
				if($killer['monster_name'] != "")
					$players_rows .= htmlspecialchars($killer['monster_name'])." summoned by ";

				if($killer['player_exists'] == 0)
					$players_rows .= "<a href=\"index.php?subtopic=characters&name=".urlencode($killer['player_name'])."\">";

				$players_rows .= htmlspecialchars($killer['player_name']);
				if($killer['player_exists'] == 0)
					$players_rows .= "</a>";
			}
			else
				$players_rows .= htmlspecialchars($killer['monster_name']);
		}

		$players_rows .= '</TD><TD>'.htmlspecialchars($config['site']['worlds'][(int)$death['world_id']]).'</TD></TR>';
	}
}

if($players_deaths_count == 0)
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Last Deaths</B></TD></TR><TR BGCOLOR='.$config['site']['darkborder'].'><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>No one died on '.htmlspecialchars($config['server']['serverName']).'.</TD></TR></TABLE></TD></TR></TABLE><BR>';
else
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Last Deaths</B></TD></TR></TABLE><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%>'.$players_rows.'</TABLE>';