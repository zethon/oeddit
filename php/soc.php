<?php

    require("soc_common.php");

	if (isset($_GET["view"]) && strcasecmp($_GET["view"], "info") == 0)
	{
		require("soc_info.php");
		exit;
	}

	// fetch posts
	$posts = get_posts($soc, isset($_GET["page"]) ? $_GET["page"]:0, 10);

	render_mult([	
					"soc_common.php", 
					"soc_front.php"
				], 
				[
					"title"  => $soc["soc_name"],
					"posts"  => $posts,
					"soc"    => $soc,
					"status" => $status
				]
				);

?>