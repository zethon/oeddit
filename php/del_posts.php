<?php

	// configuration
    require_once("../includes/global.php");
	
	$posts = get_del_posts($soc);

	render_mult([	
					"soc_common.php",
					"mod_common.php",
					"del_posts.php"
				],
				 [
					"title"=> $title." - Mod Log",
					"posts"=> $posts,
					"soc"=> $soc,
					"status" => $status
				   ]
		   );
	
?>