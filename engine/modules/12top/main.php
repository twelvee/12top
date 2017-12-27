<?php
/*
=====================================================
 Powered by Twelvee. 
-----------------------------------------------------
 VK: /id150454169
=====================================================
*/

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}

if(isset($widget)){
	require_once(ENGINE_DIR."/modules/12top/user/user.php");
	$user = new UserView($db, $widget, $config);
	echo $user->result();
}
?>