<?php
if(!defined('INITIALIZED'))
	exit;

$main_content .= '<table style="width:90%;margin-left:auto;margin-right:auto"><tr bgcolor="' . $config['site']['vdarkborder'] . '"><td colspan="2" style="color:white;font-weight:bold">Server Informations</td></tr>';
$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;width:150px">PVP protection</td><td>to ' . $config['server']['protectionLevel'] . ' level</td></tr>';
$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Exp rate</td><td>';
if($config['server']['experienceStages'])
{
	$stages = new DOMDocument();
	if($stages->load($config['site']['serverPath'] . 'data/XML/stages.xml'))
	{
		$stagesOfFirstWorldInStages = $stages->getElementsByTagName('world')->item(0);
		$worldMultiplier = $stagesOfFirstWorldInStages->getAttribute('multiplier');
		foreach($stagesOfFirstWorldInStages->getElementsByTagName('stage') as $stage)
		{
			$main_content .= $stage->getAttribute('minlevel');
			if($stage->hasAttribute('maxlevel'))
			{
				$main_content .= ' - ' . $stage->getAttribute('maxlevel') . ' level';
			}
			else
			{
				$main_content .= '+ level';
			}
			$main_content .= ', ' . ($stage->getAttribute('multiplier') * $worldMultiplier) . 'x<br />';
		}
		$main_content .= 'Edit this text.1';
	}
	else
	{
		$main_content .= 'Cannot load experience stages.';
	}
}
else
{
	$main_content .= $config['server']['rateExperience'] . 'x';
}
$main_content .= '</td></tr>';
$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Exp from players</td><td>' . $config['server']['rateExperienceFromPlayers'] . 'x</td></tr>';
$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Skill rate</td><td>' . $config['server']['rateSkill'] . 'x</td></tr>';
$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Magic rate</td><td>' . $config['server']['rateMagic'] . 'x</td></tr>';
$bgcolor = (($number_of_rows++ % 2 == 1) ?  $config['site']['darkborder'] : $config['site']['lightborder']);
$main_content .= '<tr bgcolor="' . $bgcolor . '"><td style="font-weight:bold;">Loot rate</td><td>' . $config['server']['rateLoot'] . 'x</td></tr></table>';