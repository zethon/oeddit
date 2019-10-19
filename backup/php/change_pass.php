<?php

	// configuration
	require("../includes/global.php");
	
	verify_access();
	
	if (empty($_POST))
	{
		render_mult([	
						// "user_common.php",
						"change_passfm.php"
					],
					[
						"title" => "Change Password",
						// "pg" => "user.php?u=".$u["username"],
						// "self" => $self
					]
				   );
	}
	else
	{
    	if ($_SERVER["REQUEST_METHOD"] != "POST")			redirect("index.php");    	
		
		// if form was submitted...
  		// validate submission
    	if (empty($_POST["old_pass"]))            			apologize("You must provide your current password.");
    	if (empty($_POST["new_pass"]))          			apologize("You must provide a new password.");        	
    	if (empty($_POST["confirmation"]))          		apologize("You must confirm your new password.");        	
    	if ($_POST["new_pass"] != $_POST["confirmation"])	apologize("Password and confirmation do not match.");
    	
		// compare hash of user's input against the old hash
		if (!password_verify($_POST["old_pass"], $_SESSION["user"]["password"]))
	    	apologize("Invalid username and/or password.");
		
		if (tquery(" 	UPDATE users
						SET password = ?
						WHERE user_id = ?",
						[	password_hash($_POST["new_pass"], PASSWORD_DEFAULT), 
							$_SESSION["user"]["user_id"]
						]
					)
				=== false)
			apologize("Failed to change password.");

		// redirect to home
		redirect("index.php");
	}
	
?>
