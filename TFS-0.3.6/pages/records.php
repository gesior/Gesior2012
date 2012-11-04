<?php
if(!defined('INITIALIZED'))
	exit;

$main_content .= '<b><center>Players online records on '.$config['server']['serverName'].'</center></b><TABLE BORDER=0 CELLSPACING=1 CELLPADDING=4 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=white><b><center>Players</center></b></TD><TD class="white"><b><center>Date</center></b></TD></TR>';
$records = $SQL->query('SELECT * FROM ' . $SQL->tableName('server_record') . ' ORDER BY ' . $SQL->fieldName('record') . ' DESC LIMIT 50;')->fetchAll();
foreach($records as $i => $record)
{
	$data = date("d/m/Y, G:i:s", $record['timestamp']);
	$bgcolor = (($i % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
	$main_content .= '<TR BGCOLOR='.$bgcolor.'><TD><center>'.$record['record'].'</center></TD><TD><center>'.$data.'</center></TD></TR>';
}
$main_content .= '</TABLE>';