<?php

	// configuration
    require_once("../includes/global.php");
	
	// assoc array
	$reps = get_soc_reports();
	

	render_mult([	
					"admin_common.php",
					"soc_reps.php"
				],
				[
					"title"=> $title." - Reported societies",
					"reps"=> $reps
			    ]
		   );
	
?>