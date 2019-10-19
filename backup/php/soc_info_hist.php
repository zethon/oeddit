<?php

	require_once("../includes/global.php");

	session_start();

	if (!isset($_POST["rev_id"])) 
		apologize("Something went wrong.");

	if (($revision = get_soc_info_revision($_POST["rev_id"])) === false)
		apologize("Revision not found.");

	$revision["username"] = u($revision["username"]);

	echo json_encode($revision);

?>