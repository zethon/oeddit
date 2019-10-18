<?php

	// configuration
    require_once("../includes/global.php");
	
	// assoc array
	$reps = get_user_reports();
	

	render_mult([	
					"admin_common.php",
					"user_reps.php"
				],
				[
					"title"=> $title." - Reported users",
					"reps"=> $reps
			    ]
		   );
	
?>