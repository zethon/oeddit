<?php

	// configuration
    require_once("../includes/global.php");
	
	$comms = get_del_comms($soc);

	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"del_comms.php"
				],
				 [
					"title"=> $title." - Mod Log",
					"comms"=> $comms,
					"soc"=> $soc,
					"status" => $status
				   ]
		   );
		
?>