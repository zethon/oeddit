<?php

	require("../includes/global.php");

    if (!isset($_POST["soc"]))
    {
        redirect("home.php");
    }

    if(make_soc($_POST["soc"]) === false)
    {
        apologize("Failed to create society: ".$_POST["soc"]);
    }

	redirect("soc.php?soc=".$_POST["soc"]);

?>