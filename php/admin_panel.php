<?php

	// configuration
    require_once("../includes/global.php");

	if (!am_admin())
	{
		apologize("Access Denied.");
	}

	$title = "Admin Panel";
	static $admap = [
						"trends" => "admin_dash_trends.php",
						"main"	 => "admin_dash.php",
						"bans" 	 => "user_bans.php",
						"locks"  => "soc_locks.php",
						"admins" => "admins.php",
						"log"    => "admin_log.php",
						"ureps"  => "user_reps.php",
						"sreps"  => "soc_reps.php"
					];

	if (!(isset($_GET["view"]) && array_key_exists($_GET["view"], $admap)))
		$_GET["view"] = "main";
	require($admap[$_GET["view"]]);
?>