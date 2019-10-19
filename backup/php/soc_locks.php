<?php
    
    if (isset($_POST["soc_to_lock"]))
    {
    	if (!isset($_POST["lock_reason"])) 	
    		apologize("Must provide a reason for the lock.");

    	if (lock_soc($_POST["soc_to_lock"], $_POST["lock_reason"]) === false)
    		apologize("Something went wrong.");
    }
    elseif (isset($_POST["soc_to_unlock"]))
    {
    	if (!isset($_POST["unlock_reason"])) 	
    		apologize("Must provide a reason for the lock.");

    	if (unlock_soc($_POST["soc_to_unlock"], $_POST["unlock_reason"]) === false)
    		apologize("Something went wrong.");
    }

	// configuration
    require_once("../includes/global.php");
	
	$locks = get_locked_socs();

	render_mult([	
					"admin_common.php",
					"soc_locks.php"
				],
				[
					"title"=> $title." - Admin Log",
					"locks"=> $locks
			    ]
		   );
	
?>