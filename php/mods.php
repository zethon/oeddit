<?php

	// configuration
    require_once("../includes/global.php");
	
    if (isset($_POST["user_to_mod"]))
    {
    	if (!isset($_POST["mod_reason"])) 	
    		apologize("Must provide a reason for the promotion.");

    	if (make_mod($_POST["user_to_mod"], $soc, $_POST["mod_reason"]) === false)
    		apologize("Something went wrong.");
    }
    elseif (isset($_POST["user_to_demod"]))
    {
    	if (!isset($_POST["demod_reason"])) 	
    		apologize("Must provide a reason for the demotion.");

    	if (del_mod($_POST["user_to_demod"], $soc, $_POST["demod_reason"]) === false)
    		apologize("Something went wrong.");
    }

	// assoc array
	$mods = get_mod_list($soc);
	
	render_mult([	"soc_common.php",
					"mod_common.php",
					"soc_mods.php"
				],
				 [
					"title"=> $title." - View Moderators",
					"mods"=> $mods,
					"soc"=> $soc,
					"status" => $status
				]
		   );
	
?>