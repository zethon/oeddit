<?php

	require("../includes/global.php");

	if (!isset($_POST["report_soc_id"]))	apologize("society not specified.");

	session_start();

	if (report_society($_POST["report_soc_id"], $_POST["report_soc_reason"]) === false)
		apologize("Something went wrong.");

	redirect("soc.php?soc=".$_GET["soc"]);

?>