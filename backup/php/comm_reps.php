<?php

	// configuration
    require_once("../includes/global.php");
	
	// assoc array
	$reps = get_comm_reports($soc["soc_id"]);

	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"comm_reps.php"
				],
				 [
					"title"=> $title." - Reported Comments",
					"reps"=> $reps,
					"soc"=> $soc,
					"status" => $status
				   ]
		   );	
?>