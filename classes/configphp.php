<?php
if(!defined('INITIALIZED'))
	exit;

class ConfigPHP
{
	private $config;
	private $loadedFromPath = '';

	public function __construct($path = false)
	{
		if($path)
			$this->loadFromFile($path);
	}

	public function loadFromFile($path)
	{
		if(Website::fileExists($path))
		{
			$content = Website::getFileContents($path);
			$this->loadedFromPath = $path;
			$lines = explode("\n", $content);
			unset($lines[0]); // remove <?php
			unset($lines[count($lines)]); // remove ? >
			$this->loadFromString(implode("\n", $lines));
		}
		else
			throw new RuntimeException('ERROR: <b>#C-4</b> : Class::ConfigPHP - PHP config file doesn\'t exist. Path: <b>' . $path . '</b>');
	}

	public function fileExists($path)
	{
		return Website::fileExists($path);
	}

	public function loadFromString($string)
	{
		$ret = @eval('$_web_config = array();' . chr(0x0A) . $string . chr(0x0A) . '');
		if($ret === false)
		{
			$error = error_get_last();
			throw new RuntimeException( ' - cannot load PHP config from string');
		}
		$this->config = $_web_config;
		unset($_web_config);
	}

	private function parsePhpVariableToText($value)
	{
		if(is_bool($value))
			return ($value) ? 'true' : 'false';
		elseif(is_numeric($value))
			return $value;
		else
			return '"' . str_replace('"', '\"' , $value) . '"';
	}

	public function arrayToPhpString(array $a, $d)
	{
		$s = '';
		if(is_array($a) && count($a) > 0)
			foreach($a as $k => $v)
			{
				if(is_array($v))
					$s .= self::arrayToPhpString($v, $d . '["' . $k . '"]');
				else
					$s .= $d . '["' . $k . '"] = ' . self::parsePhpVariableToText($v) . ';' . chr(0x0A);
			}
		return $s;
	}

	public function getConfigAsString()
	{
		return self::arrayToPhpString($this->config, '$_web_config');
	}

	public function saveToFile($path = false)
	{
		if($path)
			$savePath = $path;
		else
			$savePath = $this->loadedFromPath;
		Website::putFileContents($savePath, '<?php' . chr(0x0A) . $this->getConfigAsString() . '?>');
	}

	public function getValue($key)
	{
		if(isset($this->config[ $key ]))
			return $this->config[ $key ];
		else
            throw new RuntimeException('ERROR: <b>#C-5</b> : Class::ConfigPHP - Key <b>' . $key . '</b> doesn\'t exist.');
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
