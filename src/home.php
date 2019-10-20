<?php

    // configuration
    require("../includes/global.php"); 
	
	verify_access();

	$subs = get_subbed_socs();
	$feed = get_news_feed();
	
	render("home.php", ["title" => "Home", "subs" => $subs, "posts" => $feed]);
  
?>