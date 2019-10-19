<?php

	// configuration
    require_once("../includes/global.php");
	
	$active = get_active_users();
	$admins = get_active_admins();
	$regs = get_new_regs_today();
	$asocs = get_most_active_socs();
	$gsocs = get_fastest_growing_socs();

	render_mult([	
					"admin_common.php",
					"admin_dash.php"
				],
				[
					"title"  => $title." - Dashboard",
					"active" => $active,
					"regs" => $regs,
					"admins" => $admins,
					"asocs" => $asocs,
					"gsocs" => $gsocs
			    ]
		   );
	
?>