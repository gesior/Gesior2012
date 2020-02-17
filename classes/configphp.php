<?php
if(!defined('INITIALIZED'))
	exit;

class ConfigPHP
{
	private $config;

	public function getValue($key)
	{
		if(isset($this->config[ $key ]))
			return $this->config[ $key ];
		else
			throw new RuntimeException('#C-5 Config key <b>' . $key . '</b> doesn\'t exist.');
	}

	public function setValue($key, $value)
	{
		$this->config[ $key ] = $value;
	}

	public function removeKey($key)
	{
		if(isset($this->config[ $key ]))
			unset($this->config[ $key ]);
	}

	public function isSetKey($key)
	{
		return isset($this->config[ $key ]);
	}

	public function getConfig()
	{
		return $this->config;
	}

	public function setConfig($value)
	{
		$this->config = $value;
	}
}
