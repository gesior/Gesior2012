<?php
// if we set ONLY_PAGE, then it will not login / connect to MySQL until we use SQL query in our script
define('ONLY_PAGE', true);
$_GET['subtopic'] = 'guild_image';
$_REQUEST['subtopic'] = 'guild_image';
include('index.php');