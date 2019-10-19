<?php

	// configuration
    require_once("../includes/global.php");
	
    if (isset($_POST["user_to_ban"]))
    {
    	if (!isset($_POST["ban_reason"])) 	apologize("Must provide a reason for the ban.");

    	if (ban_from_soc($_POST["user_to_ban"], $soc, $_POST["ban_reason"]) === false)
    		apologize("Something went wrong.");
    }
    elseif (isset($_POST["user_to_unban"]))
    {
    	if (!isset($_POST["unban_reason"])) 	apologize("Must provide a reason for the ban.");

    	if (unban_from_soc($_POST["user_to_unban"], $soc, $_POST["unban_reason"]) === false)
    		apologize("Something went wrong.");
    }

	// assoc array
	$bans = get_soc_bans($soc);
	
	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"soc_bans.php"
				],
				 [
					"title"=> $title." - Banned users",
					"bans"=> $bans,
					"soc"=> $soc,
					"status" => $status
				   ]
		   );
	
?>