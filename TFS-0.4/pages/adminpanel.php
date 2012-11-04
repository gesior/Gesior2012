<?php
if(!defined('INITIALIZED'))
	exit;

if($group_id_of_acc_logged >= $config['site']['access_admin_panel'])
{
	$main_content .= 'Admin panel is not available in this version of Gesior acc. maker as there is no option to show :(';
}
else
{
	$main_content .= 'You don\'t have admin access.';
}