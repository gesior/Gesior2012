<?php
if(!isset($GLOBALS['initialized']))
	exit;

class Highscore extends Player
{
	public function getScore()
	{
		return $this->data['value'];
	}

	public function getFlag()
	{
		return $this->data['flag'];
	}
}