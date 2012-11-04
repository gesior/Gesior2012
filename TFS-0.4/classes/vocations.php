<?php
if(!defined('INITIALIZED'))
	exit;

class Vocations implements Iterator, Countable
{
	private $vocations = array();
	private $XML;
	public $iterator = 0;

	public function __construct($file)
	{
		$XML = new DOMDocument();
		if(!$XML->load($file))
			new Error_Critic('', 'Vocations::__construct - cannot load file <b>' . htmlspecialchars($file) . '</b>');

		$this->XML = $XML;
		$_tmp_vocations = array();

		foreach($XML->getElementsByTagName('vocation') as $vocation)
		{
			if($vocation->hasAttribute('id') && $vocation->hasAttribute('name'))
			{
				$vocationData = array();
				$vocationData['id'] = $vocation->getAttribute('id');
				$vocationData['name'] = $vocation->getAttribute('name');
				if($vocation->hasAttribute('fromvoc'))
					$vocationData['fromvoc'] = $vocation->getAttribute('fromvoc');
				else
					$vocationData['fromvoc'] = $vocationData['id'];
				if($vocation->hasAttribute('manamultiplier'))
					$vocationData['manamultiplier'] = $vocation->getAttribute('manamultiplier');
				else
					$vocationData['manamultiplier'] = 1;

				if($vocation->hasAttribute('gainhp'))
					$vocationData['gainhp'] = $vocation->getAttribute('gainhp');
				else
					$vocationData['gainhp'] = 0;
				if($vocation->hasAttribute('gainmana'))
					$vocationData['gainmana'] = $vocation->getAttribute('gainmana');
				else
					$vocationData['gainmana'] = 0;
				if($vocation->hasAttribute('gaincap'))
					$vocationData['gaincap'] = $vocation->getAttribute('gaincap');
				else
					$vocationData['gaincap'] = 0;

				if($vocation->hasAttribute('gainhpticks'))
					$vocationData['gainhpticks'] = $vocation->getAttribute('gainhpticks');
				else
					$vocationData['gainhpticks'] = 1;
				if($vocation->hasAttribute('gainhpamount'))
					$vocationData['gainhpamount'] = $vocation->getAttribute('gainhpamount');
				else
					$vocationData['gainhpamount'] = 0;

				if($vocation->hasAttribute('gainmanaticks'))
					$vocationData['gainmanaticks'] = $vocation->getAttribute('gainmanaticks');
				else
					$vocationData['gainmanaticks'] = 1;
				if($vocation->hasAttribute('gainmanaamount'))
					$vocationData['gainmanaamount'] = $vocation->getAttribute('gainmanaamount');
				else
					$vocationData['gainmanaamount'] = 0;

				if($vocation->hasAttribute('gainsoulticks'))
					$vocationData['gainsoulticks'] = $vocation->getAttribute('gainsoulticks');
				else
					$vocationData['gainsoulticks'] = 1;

				if($vocation->hasAttribute('attackspeed'))
					$vocationData['attackspeed'] = $vocation->getAttribute('attackspeed');
				else
					$vocationData['attackspeed'] = 2000;

				$_tmp_vocations[$vocation->getAttribute('id')] = $vocationData;
			}
			else
				new Error_Critic('#C', 'Cannot load vocation. <b>id</b> or/and <b>name</b> parameter is missing');
		}
		/*
		 * Set promotion level and base vocation id
		*/
		foreach($_tmp_vocations as $_tmp_vocation)
		{
			$_tmp_vocation['promotion'] = 0;
			$_tmp_vocation['base_id'] = $_tmp_vocation['id'];
			$promotion_voc = $_tmp_vocation;
			while($promotion_voc['fromvoc'] != $promotion_voc['id'])
			{
				$promotion_voc = $_tmp_vocations[$promotion_voc['fromvoc']];
				$_tmp_vocation['base_id'] = $promotion_voc['id'];
				$_tmp_vocation['promotion']++;
			}
			$this->vocations[$_tmp_vocation['id']] = new Vocation($_tmp_vocation);
		}
	}
	/*
	 * Get vocation
	*/
	public function getVocation($base_id, $promotion = 0)
	{
		foreach($this->vocations as $vocation)
			if($vocation->getBaseId() == $base_id && $vocation->getPromotion() == $promotion)
				return $vocation;
		return false;
	}
	/*
	 * Get vocation name without getting vocation
	*/
	public function getVocationName($base_id, $promotion = 0)
	{
		if($vocs = self::getVocation($base_id, $promotion))
			return $vocs->getName();
		return false;
	}


    public function current()
    {
        return $this->vocations[$this->iterator];
    }

    public function rewind()
    {
        $this->iterator = 0;
    }

    public function next()
    {
        ++$this->iterator;
    }

    public function key()
    {
        return $this->iterator;
    }

    public function valid()
    {
        return isset($this->vocations[$this->iterator]);
    }

    public function count()
    {
        return count($this->vocations);
    }

}