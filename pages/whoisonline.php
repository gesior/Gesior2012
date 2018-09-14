<?php
if(!defined('INITIALIZED'))
	exit;

$orderby = 'charname';
if(isset($_REQUEST['order']))
{
	if($_REQUEST['order']== 'level')
		$orderby = 'level';
	elseif($_REQUEST['order'] == 'vocation')
		$orderby = 'vocation';
}
$players_online_data = $SQL->query('SELECT ' . $SQL->tableName('users') . '.' . $SQL->fieldName('flag') . ', ' .
    $SQL->tableName('players') . '.' . $SQL->fieldName('charname') . ', ' .
    $SQL->tableName('players') . '.' . $SQL->fieldName('vocation') . ', ' .
    $SQL->tableName('players') . '.' . $SQL->fieldName('level') .
    ' FROM ' . $SQL->tableName('users') . ', ' . $SQL->tableName('players') . ' WHERE ' .
   $SQL->tableName('players') . '.' . $SQL->fieldName('online') . ' = 1 AND ' .
    $SQL->tableName('users') . '.' . $SQL->fieldName('id') . ' = ' . $SQL->tableName('players') . '.' . $SQL->fieldName('account_id') .
    ' ORDER BY ' . $SQL->fieldName($orderby))->fetchAll();

$number_of_players_online = 0;
$vocations_online_count = array(0,0,0,0,0); // change it if you got more then 5 vocations
$players_rows = '';
foreach($players_online_data as $player)
{
	$vocations_online_count[$player['vocation']] += 1;
	$bgcolor = (($number_of_players_online++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
	$skull = '';

	$players_rows .= '<TR BGCOLOR='.$bgcolor.'><TD WIDTH=70%><A HREF="?subtopic=characters&name='.urlencode($player['charname']).'">'.htmlspecialchars($player['charname']).$skull.'<img src="' . $config['site']['flag_images_url'] . $player['flag'] . $config['site']['flag_images_extension'] . '" title="Country: ' . $player['flag'] . '" alt="' . $player['flag'] . '" /></A></TD><TD WIDTH=10%>'.$player['level'].'</TD><TD WIDTH=20%>'.htmlspecialchars($player['vocation']).'</TD></TR>';
}		
if($config['site']['private-servlist.com_server_id'] > 0)
{
	$main_content.= '<TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD WIDTH=10% CLASS=white><center><B>Players Online Chart</B></TD></TR></TABLE><table align="center"><tr><td><img src="http://private-servlist.com/server-chart/' . $config['site']['private-servlist.com_server_id'] . '.png" width="500px" /></td></tr></table>';
}
if($number_of_players_online == 0)
{
	//server status - server empty
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Server Status</B></TD></TR><TR BGCOLOR='.$config['site']['darkborder'].'><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>Currently no one is playing on <b>'.htmlspecialchars($config['site']['serverName']).'</b>.</TD></TR></TABLE></TD></TR></TABLE><BR>';
}
else
{
	//server status - someone is online
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><B>Server Status</B></TD></TR><TR BGCOLOR='.$config['site']['darkborder'].'><TD><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=1><TR><TD>Currently '.$number_of_players_online.' players are online - <b>'.$config['status']['serverStatus_players'] .' ' . (($config['status']['serverStatus_players']  > 1) ? 'are' : 'is') . ' active</b> and <b>'.($number_of_players_online-$config['status']['serverStatus_players']).' ' . (($number_of_players_online-$config['status']['serverStatus_players']  > 1) ? 'are' : 'is') . ' AFK</b> on <b>'.htmlspecialchars($config['site']['serverName']).'</b>.</TD></TR></TABLE></TD></TR></TABLE><BR>';



	//list of players
	$main_content .= '<TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD><A HREF="?subtopic=whoisonline&order=name" CLASS=white>Name</A></TD><TD><A HREF="?subtopic=whoisonline&order=level" CLASS=white>Level</A></TD><TD><A HREF="?subtopic=whoisonline&order=vocation" CLASS=white>Vocation</TD></TR>'.$players_rows.'</TABLE>';
	//search bar
	$main_content .= '<BR><FORM ACTION="?subtopic=characters" METHOD=post>  <TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>Search Character</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'"><TABLE BORDER=0 CELLPADDING=1><TR><TD>Name:</TD><TD><INPUT NAME="name" VALUE="" SIZE="29" MAXLENGTH="29"></TD><TD><INPUT TYPE="image" NAME="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif" BORDER=0 WIDTH=120 HEIGHT=18></TD></TR></TABLE></TD></TR></TABLE></FORM>';
}