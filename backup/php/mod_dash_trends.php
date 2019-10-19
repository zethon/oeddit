<?php

	// configuration
    require_once("../includes/global.php");

	$atrend = get_active_trend_soc($soc["soc_id"]);
	$strend   = get_subs_trend($soc["soc_id"]);
	$ctrend  = get_comms_trend($soc["soc_id"]);
	$ptrend  = get_posts_trend($soc["soc_id"]);

	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"mod_dash_trends.php"
				],
				[
					"title"  => $title." - Dashboard",
					"soc"	 => $soc,
					"status" => $status,
					"atrend" => $atrend,
					"strend" => $strend,
					"ctrend"  => $ctrend,
					"ptrend"  => $ptrend
			    ]
		   );
	
?>