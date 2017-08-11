<?php
	$db = new pdo('mysql:host=localhost;dbname=thinklogic','root','');
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	date_default_timezone_set('Asia/Singapore');
	$script_tz = date_default_timezone_get();
?>