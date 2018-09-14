<?php
if(!defined('INITIALIZED'))
	exit;

echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
$account = strtoupper(trim($_REQUEST['account']));
if(empty($account))
{
	echo '<font color="red">Please enter an account number.</font>';
	exit;
}
if(strlen($account) < 8)
{
	if(!check_account_name($account))
	{
		echo '<font color="red">Invalid account name format. Use only digits 0-9. Zero ( 0 ) cannot be first digit.</font>';
		exit;
	}
	$account_db = new Account();
	$account_db->find($account);
	if($account_db->isLoaded())
		echo '<font color="red">Account with this name already exist.</font>';
	else
		echo '<font color="green">Good account name ( '.htmlspecialchars($account).' ). You can create account.</font>';
}
else
	echo '<font color="red">Account name is too long (max. 7 digits).</font>';
exit;