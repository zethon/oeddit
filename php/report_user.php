<?php

	require("../includes/global.php");

	if (!isset($_POST["report_username"]))	apologize("User not specified.");

	session_start();

	if (report_user($_POST["report_username"], $_POST["report_user_reason"]) === false)
		apologize("Something went wrong.");

	redirect("user.php?u=".$_POST["report_username"]);

?>