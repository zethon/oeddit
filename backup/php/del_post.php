<?php

	require("../includes/global.php");

	if (!(isset($_POST["del_post_id"]) && isset($_POST["soc_name"])))
		apologize("Something went wrong.");

	session_start();

	if (del_post($_POST["del_post_id"], $_POST["soc_name"], $_POST["del_post_reason"]) === false)
		apologize("Failed to sticky post.");

	redirect("post.php?soc=".$_POST["soc_name"]);

?>