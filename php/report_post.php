<?php

	require("../includes/global.php");

	if (!isset($_POST["report_post_id"]))	apologize("Post not specified.");

	session_start();

	if (report_post($_POST["report_post_id"], $_POST["report_post_reason"]) === false)
		apologize("Something went wrong.");

	redirect("post.php?soc=".$_GET["soc"]."&pid=".$_GET["pid"]);

?>