<?php
if(!defined('INITIALIZED'))
	exit;

$list = 'experience';
if(isset($_REQUEST['list']))
	$list = $_REQUEST['list'];

$page = 0;
if(isset($_REQUEST['page']))
	$page = min(50, $_REQUEST['page']);

$vocation = '';
if(isset($_REQUEST['vocation']))
	$vocation = $_REQUEST['vocation'];

switch($list)
{
	case "fist":
		$id=Highscores::SKILL_FIST;
		$list_name='Fist Fighting';
		break;
	case "club":
		$id=Highscores::SKILL_CLUB;
		$list_name='Club Fighting';
		break;
	case "sword":
		$id=Highscores::SKILL_SWORD;
		$list_name='Sword Fighting';
		break;
	case "axe":
		$id=Highscores::SKILL_AXE;
		$list_name='Axe Fighting';
		break;
	case "distance":
		$id=Highscores::SKILL_DISTANCE;
		$list_name='Distance Fighting';
		break;
	case "shield":
		$id=Highscores::SKILL_SHIELD;
		$list_name='Shielding';
		break;
	case "fishing":
		$id=Highscores::SKILL_FISHING;
		$list_name='Fishing';
		break;
	case "magic":
		$id=Highscores::SKILL__MAGLEVEL;
		$list_name='Magic';
		break;
	default:
		$id=Highscores::SKILL__LEVEL;
		$list_name='Experience';
		break;
}
$world_name = $config['server']['serverName'];

$offset = $page * 100;
$skills = new Highscores($id, 100, $page, $vocation);
$main_content .= '<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><TR><TD><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD><TD><CENTER><H2>Ranking for '.htmlspecialchars($list_name).' on '.htmlspecialchars($world_name).'</H2></CENTER><BR>';

$main_content .= '<br><TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%></TABLE><TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=whites><B>#</B></TD><TD CLASS=whites><b>Outfit</b></TD><TD WIDTH=75% CLASS=whites><B>Name</B></TD><TD WIDTH=15% CLASS=whites><b><center>Level</center></B></TD>';
if($list == "experience")
	$main_content .= '<TD CLASS=whites><b><center>Experience</center></B></TD>';
//$main_content .= '</TR><TR>';
$main_content .= '</TR>';
$number_of_rows = 0;
foreach($skills as $skill)
{
	if($list == "magic")
		$value = $skill->getMagLevel();
	elseif($list == "experience")
		$value = $skill->getLevel();
	else
		$value = $skill->getScore();
	$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
	$main_content .= '<tr bgcolor="'.$bgcolor.'"><td style="text-align:right">'.($offset + $number_of_rows).'.</td><TD><img src="' . $config['site']['outfit_images_url'] . '?id=' . $skill->getLookType() . '&addons=' . $skill->getLookAddons() . '&head=' . $skill->getLookHead() . '&body=' . $skill->getLookBody() . '&legs=' . $skill->getLookLegs() . '&feet=' . $skill->getLookFeet() . '" alt="" /></TD><td><a href="?subtopic=characters&name='.urlencode($skill->getName()).'">'.($skill->getOnline()>0 ? "<font color=\"green\">".htmlspecialchars($skill->getName())."</font>" : "<font color=\"red\">".htmlspecialchars($skill->getName())."</font>").'</a><img src="' . $config['site']['flag_images_url'] . $skill->getFlag() . $config['site']['flag_images_extension'] . '" title="Country: ' . $skill->getFlag() . '" alt="' . $skill->getFlag() . '" /><br><small>'.$skill->getLevel().' '.htmlspecialchars(Website::getVocationName($skill->getVocation())).'</small></td><td><center>'.$value.'</center></td>';
	if($list == "experience")
		$main_content .= '<td><center>'.$skill->getExperience().'</center></td>';
	$main_content .= '</tr>';
}
$main_content .= '</TABLE><TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%>';
if($page > 0)
	$main_content .= '<TR><TD WIDTH=100% ALIGN=right VALIGN=bottom><A HREF="?subtopic=highscores&list='.urlencode($list).'&page='.($page - 1).'&vocation=' . urlencode($vocation) . '" CLASS="size_xxs">Previous Page</A></TD></TR>';
if($page < 50)
	$main_content .= '<TR><TD WIDTH=100% ALIGN=right VALIGN=bottom><A HREF="?subtopic=highscores&list='.urlencode($list).'&page='.($page + 1).'&vocation=' . urlencode($vocation) . '" CLASS="size_xxs">Next Page</A></TD></TR>';
$main_content .= '</TABLE></TD><TD WIDTH=5%><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=1 HEIGHT=1 BORDER=0></TD><TD WIDTH=15% VALIGN=top ALIGN=right><TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=whites><B>Choose a skill</B></TD></TR><TR BGCOLOR="'.$config['site']['lightborder'].'"><TD><A HREF="?subtopic=highscores&list=experience" CLASS="size_xs">Experience</A><BR><A HREF="?subtopic=highscores&list=magic" CLASS="size_xs">Magic</A><BR><A HREF="?subtopic=highscores&list=shield" CLASS="size_xs">Shielding</A><BR><A HREF="?subtopic=highscores&list=distance" CLASS="size_xs">Distance</A><BR><A HREF="?subtopic=highscores&list=club" CLASS="size_xs">Club</A><BR><A HREF="?subtopic=highscores&list=sword" CLASS="size_xs">Sword</A><BR><A HREF="?subtopic=highscores&list=axe" CLASS="size_xs">Axe</A><BR><A HREF="?subtopic=highscores&list=fist" CLASS="size_xs">Fist</A><BR><A HREF="?subtopic=highscores&list=fishing" CLASS="size_xs">Fishing</A><BR></TD></TR></TABLE></TD><TD><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD></TR></TABLE>';