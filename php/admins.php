<?php

	// configuration
    require_once("../includes/global.php");
	
    if (isset($_POST["user_to_admin"]))
    {
    	if (!isset($_POST["admin_reason"])) 	
    		apologize("Must provide a reason for the promotion.");

    	if (make_admin($_POST["user_to_admin"], $_POST["admin_reason"]) === false)
    		apologize("Something went wrong.");
    }
    elseif (isset($_POST["user_to_deadmin"]))
    {
    	if (!isset($_POST["deadmin_reason"])) 	
    		apologize("Must provide a reason for the demotion.");

    	if (del_admin($_POST["user_to_deadmin"], $_POST["deadmin_reason"]) === false)
    		apologize("Something went wrong.");
    }

	// assoc array
	$admins = get_admin_list();
	
	render_mult([
					"admin_common.php",
					"admins.php"
				],
				 [
					"title"=> $title." - View Admins",
					"admins"=> $admins
										   ]
		   );
	
?>