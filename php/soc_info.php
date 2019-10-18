<?php
	
	require_once("../includes/global.php");

	if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
    	session_start();

		if (edit_soc_info($_POST["soc_id"], $_POST["info"]) === false)
			apologize("Failed to make edit.");

		redirect("soc.php?soc=".$_POST["soc_name"]);
	}
	else
	{
		$hist = get_soc_edit_history($soc["soc_id"]);

		render_mult([
						"soc_common.php",
						"soc_info.php"
					], 
					[
						"title"  => "About ".$soc["soc_name"],
						"soc"    => $soc,
						"status" => $status,
						"hist"	 => $hist
					]
					);
	}

?>