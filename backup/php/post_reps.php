<?php

	// configuration
    require_once("../includes/global.php");
	
	// assoc array
	$reps = get_post_reports($soc["soc_id"]);

	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"post_reps.php"
				],
				 [
					"title"=> $title." - Reported Posts",
					"reps"=> $reps,
					"soc"=> $soc,
					"status" => $status
				   ]
		   );	
?>