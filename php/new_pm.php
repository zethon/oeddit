<?php
	
	require("../includes/global.php");

	session_start();

    // sanity checks
	if (empty($_POST['subject']))
		$_POST['subject'] = null;

	// make PM
	if (make_pm(get_user($_POST["to"]), $_POST['subject'], $_POST['text']) === false)
		apologize("Failed to submit post.");

	
	redirect("user.php?u=".$_POST["to"]);
?>