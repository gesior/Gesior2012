<?php
if(!defined('INITIALIZED'))
	exit;

$layout_header = '<script type=\'text/javascript\'>
function GetXmlHttpObject()
{
var xmlHttp=null;
try
  {
  xmlHttp=new XMLHttpRequest();
  }
catch (e)
  {
  try
    {
    xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
  catch (e)
    {
    xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
return xmlHttp;
}

function MouseOverBigButton(source)
{
  source.firstChild.style.visibility = "visible";
}
function MouseOutBigButton(source)
{
  source.firstChild.style.visibility = "hidden";
}
function BigButtonAction(path)
{
  window.location = path;
}
var';
if($logged) { $layout_header .= "loginStatus=1; loginStatus='true';"; } else { $layout_header .= "loginStatus=0; loginStatus='false';"; };
$layout_header .= "var activeSubmenuItem='".$subtopic."';  var IMAGES=0; IMAGES='".$config['server']['url']."/".$layout_name."/images'; var LINK_ACCOUNT=0; LINK_ACCOUNT='".$config['server']['url']."';</script>";
include($layout_name."/layout.php");