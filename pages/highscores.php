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
if(count($config['site']['worlds']) > 1)
{
	foreach($config['site']['worlds'] as $idd => $world_n)
	{
		if($idd == (int) $_REQUEST['world'])
		{
			$world_id = $idd;
			$world_name = $world_n;
		}
	}
}
if(!isset($world_id))
{
	$world_id = 0;
	$world_name = $config['server']['serverName'];
}
if(count($config['site']['worlds']) > 1)
{
	$main_content .= '<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 WIDTH=100%><TR><TD></TD><TD>
	<FORM ACTION="" METHOD=get><INPUT TYPE="hidden" NAME="subtopic" VALUE="highscores"><TABLE WIDTH=100% BORDER=0 CELLSPACING=1 CELLPADDING=4><TR><TD BGCOLOR="'.$config['site']['vdarkborder'].'" CLASS=white><B>World Selection</B></TD></TR><TR><TD BGCOLOR="'.$config['site']['darkborder'].'">
	<TABLE BORDER=0 CELLPADDING=1><TR><TD>Best players on world:</TD><TD><SELECT SIZE="1" NAME="world">';
	foreach($config['site']['worlds'] as $wid => $world_n)
	{
		if($wid == $world_id)
			$main_content .= '<OPTION VALUE="'.htmlspecialchars($wid).'" selected="selected">'.htmlspecialchars($world_n).'</OPTION>';
		else
			$main_content .= '<OPTION VALUE="'.htmlspecialchars($wid).'">'.htmlspecialchars($world_n).'</OPTION>';
	}
	$main_content .= '</SELECT> </TD><TD><INPUT TYPE="image" NAME="Submit" ALT="Submit" SRC="'.$layout_name.'/images/buttons/sbutton_submit.gif">
		</TD></TR></TABLE></TABLE></FORM></TABLE>';
}
$offset = $page * 100;
$skills = new Highscores($id, 100, $page, $world_id, $vocation);
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
	$main_content .= '<tr bgcolor="'.$bgcolor.'"><td style="text-align:right">'.($offset + $number_of_rows).'.</td><TD><img src="' . $config['site']['outfit_images_url'] . '?id=' . $skill->getLookType() . '&addons=' . $skill->getLookAddons() . '&head=' . $skill->getLookHead() . '&body=' . $skill->getLookBody() . '&legs=' . $skill->getLookLegs() . '&feet=' . $skill->getLookFeet() . '" alt="" /></TD><td><a href="?subtopic=characters&name='.urlencode($skill->getName()).'">'.($skill->getOnline()>0 ? "<font color=\"green\">".htmlspecialchars($skill->getName())."</font>" : "<font color=\"red\">".htmlspecialchars($skill->getName())."</font>").'</a><img src="' . $config['site']['flag_images_url'] . $skill->getFlag() . $config['site']['flag_images_extension'] . '" title="Country: ' . $skill->getFlag() . '" alt="' . $skill->getFlag() . '" /><br><small>'.$skill->getLevel().' '.htmlspecialchars(Website::getVocationName($skill->getVocation(), $skill->getPromotion())).'</small></td><td><center>'.$value.'</center></td>';
	if($list == "experience")
		$main_content .= '<td><center>'.$skill->getExperience().'</center></td>';
	$main_content .= '</tr>';
}
$main_content .= '</TABLE><TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1 WIDTH=100%>';
if($page > 0)
	$main_content .= '<TR><TD WIDTH=100% ALIGN=right VALIGN=bottom><A HREF="?subtopic=highscores&list='.urlencode($list).'&page='.($page - 1).'&vocation=' . urlencode($vocation) . '&world=' . urlencode($world_id) . '" CLASS="size_xxs">Previous Page</A></TD></TR>';
if($page < 50)
	$main_content .= '<TR><TD WIDTH=100% ALIGN=right VALIGN=bottom><A HREF="?subtopic=highscores&list='.urlencode($list).'&page='.($page + 1).'&vocation=' . urlencode($vocation) . '&world=' . urlencode($world_id) . '" CLASS="size_xxs">Next Page</A></TD></TR>';
$main_content .= '</TABLE></TD><TD WIDTH=5%><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=1 HEIGHT=1 BORDER=0></TD><TD WIDTH=15% VALIGN=top ALIGN=right><TABLE BORDER=0 CELLPADDING=4 CELLSPACING=1><TR BGCOLOR="'.$config['site']['vdarkborder'].'"><TD CLASS=whites><B>Choose a skill</B></TD></TR><TR BGCOLOR="'.$config['site']['lightborder'].'"><TD><A HREF="?subtopic=highscores&list=experience&world='.$world_id.'" CLASS="size_xs">Experience</A><BR><A HREF="?subtopic=highscores&list=magic&world='.$world_id.'" CLASS="size_xs">Magic</A><BR><A HREF="?subtopic=highscores&list=shield&world='.$world_id.'" CLASS="size_xs">Shielding</A><BR><A HREF="?subtopic=highscores&list=distance&world='.$world_id.'" CLASS="size_xs">Distance</A><BR><A HREF="?subtopic=highscores&list=club&world='.$world_id.'" CLASS="size_xs">Club</A><BR><A HREF="?subtopic=highscores&list=sword&world='.$world_id.'" CLASS="size_xs">Sword</A><BR><A HREF="?subtopic=highscores&list=axe&world='.$world_id.'" CLASS="size_xs">Axe</A><BR><A HREF="?subtopic=highscores&list=fist&world='.$world_id.'" CLASS="size_xs">Fist</A><BR><A HREF="?subtopic=highscores&list=fishing&world='.$world_id.'" CLASS="size_xs">Fishing</A><BR></TD></TR></TABLE></TD><TD><IMG SRC="'.$layout_name.'/images/blank.gif" WIDTH=10 HEIGHT=1 BORDER=0></TD></TR></TABLE>';