<?php

	require("../includes/global.php");

	if (!(isset($_POST["sticky_post_id"]) && isset($_POST["soc_name"])))
		apologize("Something went wrong.");

	session_start();

	switch ($_POST["action"])
	{
	 	case 'STICKY':
			if (sticky_post($_POST["sticky_post_id"], $_POST["soc_id"]) === false)
				apologize("Failed to sticky post.");
	 		break;
	 	case 'UNSTICKY':
			if (unsticky_post($_POST["sticky_post_id"], $_POST["soc_id"]) === false)
				apologize("Failed to sticky post.");
	 		break;
	 }

	redirect("post.php?soc=".$_POST["soc_name"]);

?>