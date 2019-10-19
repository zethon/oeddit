<?php

    // configuration
    require("../includes/global.php"); 

    // log out current user, if any
    logout();
	
    // redirect user
    redirect("login.php");

?>
