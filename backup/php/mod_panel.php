<?php

    require("soc_common.php");

	if (!am_mod($soc))
	{
		apologize("Access Denied.");
	}

	$title .= " - Mod Panel";
	
	static $mmap = [
						"trends" => "mod_dash_trends.php",
						"main"   => "mod_dash.php",
						"bans"   => "soc_bans.php",
						"dposts" => "del_posts.php",
						"dcomms" => "del_comms.php",
						"mods"   => "mods.php",
						"log" 	 => "mod_log.php",
						"preps"  => "post_reps.php",
						"creps"  => "comm_reps.php"
					];

	if (!(isset($_GET["view"]) && array_key_exists($_GET["view"], $mmap)))
		$_GET["view"] = "main";
	require($mmap[$_GET["view"]]);
	
?>