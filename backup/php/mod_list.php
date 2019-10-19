<?php

	// configuration
    require_once("../includes/global.php");
	
	// assoc array
	$mods = get_mod_list($soc);
	
	render("mods.php", ["mods"=>$mods,
						"soc"=>$soc
						]);
	
?>