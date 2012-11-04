<?php
if(!defined('INITIALIZED'))
	exit;

class ServerStatus
{
	public $isOnline = false;

	public $ip;
	public $port;
	public $waitAnswerTime = 5;
	public $packet = '';
	public $errorNumber = 0;
	public $errorMessage = '';

	public $answerXML;

	public $playersCount = 0;
	public $playersMaxCount = 0;
	public $playersPeakCount = 0;

	public $mapName = '';
	public $mapAuthor = '';
	public $mapWidth = 0;
	public $mapHeight = 0;

	public $npcs = 0;
	public $monsters = 0;
	public $uptime = 0;
	public $motd = '';
	public $location = '';
	public $url = '';
	public $client = '';
	public $server = '';
	public $serverName = '';
	public $serverIP = '';

	public $ownerName = '';
	public $ownerMail = '';

	public function __construct($ip = null, $port = null, $waitTime = null, $packet = null)
	{
		if($ip !== null)
			$this->ip = $ip;
		if($port !== null)
			$this->port = $port;
		if($waitTime !== null)
			$this->waitAnswerTime = $waitTime;
		if($packet !== null)
			$this->packet = $packet;
		else
			$this->packet = chr(6).chr(0).chr(255).chr(255).'info';
	}

	public function getErrorNumber($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->errorNumber;
	}

	public function getErrorMessage($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->errorMessage;
	}

	public function loadStatus($forceReload = false)
	{
		if(!isset($this->answerXML) || $forceReload)
		{
			$this->isOnline = false;
			$sock = @fsockopen($this->ip, $this->port, $this->errorNumber, $this->errorMessage, $this->waitAnswerTime);
			if($sock)
			{
				fwrite($sock, $this->packet); 
				$answer = ''; 
				while (!feof($sock))
					$answer .= fgets($sock, 1024);
				fclose($sock);
				$this->answerXML = new DOMDocument();
				if(empty($answer) || !$this->answerXML->loadXML($answer))
					return;

				$this->isOnline = true;
				$elements = $this->answerXML->getElementsByTagName('players');
				if($elements->length == 1)
				{
					$element = $elements->item(0);
					if($element->hasAttribute('online'))
						$this->playersCount = $element->getAttribute('online');
					if($element->hasAttribute('max'))
						$this->playersMaxCount = $element->getAttribute('max');
					if($element->hasAttribute('peak'))
						$this->playersPeakCount = $element->getAttribute('peak');
				}

				$elements = $this->answerXML->getElementsByTagName('map');
				if($elements->length == 1)
				{
					$element = $elements->item(0);
					if($element->hasAttribute('name'))
						$this->mapName = $element->getAttribute('name');
					if($element->hasAttribute('author'))
						$this->mapAuthor = $element->getAttribute('author');
					if($element->hasAttribute('width'))
						$this->mapWidth = $element->getAttribute('width');
					if($element->hasAttribute('height'))
						$this->mapHeight = $element->getAttribute('height');
				}

				$elements = $this->answerXML->getElementsByTagName('npcs');
				if($elements->length == 1)
				{
					$element = $elements->item(0);
					if($element->hasAttribute('total'))
						$this->npcs = $element->getAttribute('total');
				}

				$elements = $this->answerXML->getElementsByTagName('monsters');
				if($elements->length == 1)
				{
					$element = $elements->item(0);
					if($element->hasAttribute('total'))
						$this->monsters = $element->getAttribute('total');
				}

				$elements = $this->answerXML->getElementsByTagName('serverinfo');
				if($elements->length == 1)
				{
					$element = $elements->item(0);
					if($element->hasAttribute('uptime'))
						$this->uptime = $element->getAttribute('uptime');
					if($element->hasAttribute('location'))
						$this->location = $element->getAttribute('location');
					if($element->hasAttribute('url'))
						$this->url = $element->getAttribute('url');
					if($element->hasAttribute('client'))
						$this->client = $element->getAttribute('client');
					if($element->hasAttribute('server'))
						$this->server = $element->getAttribute('server');
					if($element->hasAttribute('serverName'))
						$this->serverName = $element->getAttribute('serverName');
					if($element->hasAttribute('ip'))
						$this->serverIP = $element->getAttribute('ip');
				}

				$elements = $this->answerXML->getElementsByTagName('motd');
				if($elements->length == 1)
				{
					$this->motd = $elements->item(0)->nodeValue;
				}
			}
		}
	}

	public function isOnline($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->isOnline;
	}

	public function getPlayersCount($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->playersCount;
	}

	public function getPlayersMaxCount($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->playersMaxCount;
	}

	public function getPlayersPeakCount($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->playersPeakCount;
	}

	public function getMapName($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->mapName;
	}

	public function getMapAuthor($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->mapAuthor;
	}

	public function getMapWidth($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->mapWidth;
	}

	public function getMapHeight($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->mapHeight;
	}

	public function getUptime($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->uptime;
	}

	public function getMonsters($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->monsters;
	}

	public function getNPCs($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->npcs;
	}

	public function getMOTD($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->motd;
	}

	public function getLocation($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->location;
	}

	public function getURL($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->url;
	}

	public function getClient($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->client;
	}

	public function getServer($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->server;
	}

	public function getServerName($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->serverName;
	}

	public function getServerIP($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->serverIP;
	}

	public function getOwnerName($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->ownerName;
	}

	public function getOwnerMail($forceReload = false)
	{
		$this->loadStatus($forceReload);
		return $this->ownerMail;
	}
}