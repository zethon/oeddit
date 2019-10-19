<?php

	// configuration
    require_once("../includes/global.php");
	
	$log = get_mod_log($soc);

	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"mod_log.php"
				],
				 [
					"title"  => $title." - Mod Log",
					"log"    => $log,
					"soc"    => $soc,
					"status" => $status
				   ]
		   );
	
?>