<?php

    require("../includes/global.php");
    session_start();

    // sanity checks
	if (empty($_POST['text']))		apologize("Cannot post empty comment.");
	if (empty($_GET["pid"]))		apologize("Post-id missing.");

	// make post
	if (make_comment(get_post($_GET["pid"]), $_POST["text"], !empty($_POST["parent_id"]) ? $_POST["parent_id"]:null) === false)
		apologize("Failed to submt comment.");

	redirect("post.php?soc=".$_GET["soc"]."&pid=".$_GET["pid"]);

?>