<?php
if(!defined('INITIALIZED'))
	exit;

if($group_id_of_acc_logged >= $config['site']['access_admin_panel'])
{
	$main_content .= 'I recommend you to add items and containers by this script: <a href="http://otland.net/f479/gesior2012-items-shop-installation-administration-170654/" target="_blank">http://otland.net/f479/gesior2012-items-shop-installation-administration-170654/</a><br />';
	$main_content .= 'Script to add points etc. to shop on www is not available. I also recommend to <b>not download from forum/use old script</b> as it is bugged!';
}
else
{
	$main_content .= 'Sorry, you have not the rights to access this page.';
}