<?php

    // configuration
    require("../includes/global.php"); 
	
	verify_access();
	
    if (!isset($_GET["soc"]))
    {
        redirect("home.php");
    }

    if (get_society_id($_GET["soc"]) === false)
    {
        apologize("that mofo does not exist!");
    }

	// assoc array containing name, creator, date created, and description 
    $soc = get_society($_GET["soc"]);
	if (!$soc)
	{
		apologize("that mofo is empty");
	}

	log_soc_activity($soc["soc_id"]);

	$title = $soc["soc_name"];
	$status = soc_rel($soc);

    if (isset($_GET["saction"]))
    {
        if (strcasecmp($_GET["saction"], "sub") == 0 && !$status["sub"])
        {
            sub_soc($soc);
        }
        elseif (strcasecmp($_GET["saction"], "unsub") == 0 && $status["sub"])
        {
            unsub_soc($soc);
        }

        $status = soc_rel($soc);
    }	

?>