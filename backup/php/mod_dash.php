<?php

	// configuration
    require_once("../includes/global.php");
	
	$active = get_active_users_soc($soc["soc_id"]);
	$atrend = get_active_trend_soc($soc["soc_id"]);
	// $mods   = get_active_mods($soc["soc_id"]);
	$subs   = get_new_subs_today($soc["soc_id"]);
	$comms  = get_comms_today($soc["soc_id"]);
	$posts  = get_posts_today($soc["soc_id"]);

	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"mod_dash.php"
				],
				[
					"title"  => $title." - Dashboard",
					"soc"	 => $soc,
					"status" => $status,
					"active" => $active,
					"atrend" => $atrend,
					"subs"   => $subs,
					// "mods"   => $mods,
					"comms"  => $comms,
					"posts"  => $posts
			    ]
		   );
	
?>