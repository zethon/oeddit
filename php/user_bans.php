<?php
	
    if (isset($_POST["user_to_ban"]))
    {
    	if (!isset($_POST["ban_reason"])) 	
    		apologize("Must provide a reason for the ban.");

    	if (ban_user($_POST["user_to_ban"], $_POST["ban_reason"]) === false)
    		apologize("Something went wrong.");
    }
    elseif (isset($_POST["user_to_unban"]))
    {
    	if (!isset($_POST["unban_reason"])) 	
    		apologize("Must provide a reason for the ban.");

    	if (unban_user($_POST["user_to_unban"], $_POST["unban_reason"]) === false)
    		apologize("Something went wrong.");
    }

	// assoc array
	$bans = get_site_bans();
	
	render_mult([	
					"admin_common.php",
					"user_bans.php"
				],
				 [
					"title"=> $title." - Banned users",
					"bans"=> $bans
				   ]
		   );
	
?>