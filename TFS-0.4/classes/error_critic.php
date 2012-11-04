<?php
if(!defined('INITIALIZED'))
	exit;

class Error_Critic
{
	public function __construct($id = '', $text = '', $errors = array())
	{
		echo '<h3>Error occured!</h3>';
		echo 'Error ID: <b>' . $id . '</b><br />';
		echo 'More info: <b>' . $text . '</b><br />';
		if(count($errors) > 0)
		{
			echo 'Errors list:<br />';
			foreach($errors as $error)
				echo '<li>' . $error->getId() . ' - ' . $error->getText() . '</li>';
		}
		function showErrorBacktrace($a, $b)
		{
			print "<br />File: <b>";
			if(isset($a['file']))
			{
				print dirname($a['file']) . "/" . basename($a['file']);
			}
			else
			{
				print 'Unknown';
			}
			print "</b> &nbsp; Line: <font color=\"red\">";
			print ((isset($a['line'])) ? $a['line'] : 'Unknown');
			print "</font>";
			
		}
		$tmp = debug_backtrace();
		array_walk( $tmp, "showErrorBacktrace" );
		exit;
	}
}