<?php

	require("../includes/global.php");

	if (!isset($_POST["report_comm_id"]))	apologize("Comment not specified.");

	session_start();

	if (report_comment($_POST["report_comm_id"], $_POST["report_comm_reason"]) === false)
		apologize("Something went wrong.");

	redirect("post.php?soc=".$_GET["soc"]."&pid=".$_GET["pid"]);

?>