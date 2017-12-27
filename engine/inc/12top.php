<?php
if( !defined( 'DATALIFEENGINE' ) OR !defined( 'LOGGED_IN' ) ) {
	die( "Hacking attempt!" );
}

require_once(ENGINE_DIR."/modules/12top/admin/admin.php");

$admin = new AdminPanel($db);
$admin->echoheader("asd", "asd");
$admin->show();
$admin->echofooter();
?>