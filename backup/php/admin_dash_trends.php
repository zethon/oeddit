<?php

	// configuration
    require_once("../includes/global.php");

	$utrend = get_active_trend();
	$atrend = get_active_admin_trend();
	$rtrend = get_new_regs_trend();
	
	render_mult([	
					"admin_common.php",
					"admin_dash_trends.php"
				],
				[
					"title"  => $title." - Dashboard",
					"utrend" => $utrend,
					"atrend" => $atrend,
					"rtrend" => $rtrend,
			    ]
		   );
	
?>