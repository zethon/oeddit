<?php

	require("../includes/global.php");

	if (!isset($_POST["del_comm_id"]))	apologize("Comment not specified.");

	session_start();

	if (del_comment($_POST["del_comm_id"], $_GET["soc"], $_POST["del_comm_reason"]) === false)
		apologize("Something went wrong.");

	redirect("post.php?soc=".$_GET["soc"]."&pid=".$_GET["pid"]);

?>