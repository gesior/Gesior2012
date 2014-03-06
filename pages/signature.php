<?php
if(!defined('INITIALIZED'))
	exit;

$player = new Player();
$player->find($_GET['name']);
if($player->isLoaded())
{
	if(!file_exists('cache/signatures/' . $player->getID()) || filemtime('cache/signatures/' . $player->getID()) === false || filemtime('cache/signatures/' . $player->getID()) + 30 < time())
	{
		$image = imagecreatefrompng('./images/signatures/signature.png');
		$color= imagecolorallocate($image , 255, 255, 255);
		imagettftext($image , 12, 0, 20, 32, $color, './images/signatures/font.ttf' , 'Name:');
		imagettftext($image , 12, 0, 70, 32, $color, './images/signatures/font.ttf' , $player->getName());

		imagettftext($image , 12, 0, 20, 52, $color, './images/signatures/font.ttf' , 'Level:');
		imagettftext($image , 12, 0, 70, 52, $color, './images/signatures/font.ttf' , $player->getLevel() . ' ' . Website::getVocationName($player->getVocation()));

		if($player->getRank())
		{
			imagettftext($image , 12, 0, 20, 75, $color, './images/signatures/font.ttf' , 'Guild:');
			imagettftext($image , 12, 0, 70, 75, $color, './images/signatures/font.ttf' , $player->getRank()->getName() . ' of the ' . $player->getRank()->getGuild()->getName());
		}
		imagettftext($image , 12, 0, 20, 95, $color, './images/signatures/font.ttf' , 'Last Login:');
		imagettftext($image , 12, 0, 100, 95, $color, './images/signatures/font.ttf' , (($player->getLastLogin() > 0) ? date("j F Y, g:i a", $player->getLastLogin()) : 'Never logged in.'));
		imagepng($image, 'cache/signatures/' . $player->getID());
		imagedestroy($image);
	}
	header("Content-type: image/png");
	echo file_get_contents('cache/signatures/' . $player->getID());
}
exit;