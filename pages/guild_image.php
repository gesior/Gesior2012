<?php
if(!defined('INITIALIZED'))
	exit;
$guild_id = (int) $_REQUEST['id'];
$guild = new Guild($guild_id);
$guildLogo = $guild->getGuildLogo();
$guildLogoInfo = explode(';', $guildLogo, 3);
$image = array();
$image['content_mtime'] = $guildLogoInfo[0];
if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime(preg_replace('/;.*$/','',$_SERVER["HTTP_IF_MODIFIED_SINCE"])) == $image['content_mtime'])
{
	header('HTTP/1.0 304 Not Modified');
	/* PHP/webserver by default can return 'no-cache', so we must modify it */
	header('Cache-Control: public');
	header('Pragma: cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $image['content_mtime']) . ' GMT');
}
else
{
	$image['content_type'] = substr($guildLogoInfo[1], 5);
	$image['content'] = base64_decode(substr($guildLogoInfo[2], 7));
	header('Content-Type: ' . $image['content_type']);
	header('Cache-Control: public');
	header('Pragma: cache');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $image['content_mtime']) . ' GMT');
	echo $image['content'];
}