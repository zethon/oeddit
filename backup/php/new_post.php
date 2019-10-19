<?php

    require("soc_common.php");

    // sanity checks
	if (empty($_POST['title']))
		apologize("Post must have a title.");

	empty($_POST['title']) || $text = $_POST['text'];

	// make post
	if (make_post($soc, $_POST['title'], $text) === false)
		apologize("Failed to submt post.");

	redirect("soc.php?soc=".$soc["soc_name"]);
?>