<?php

	require_once('..includes/global.php');
	session_start();

	if (isset($_POST))
	{
		$curr_vote = ($v = comm_rel($_POST["comm_id"])) ? $v["vote"]:"NONE";
		$json = [];
		if ($curr_vote == $_POST["vote"])
			$json["msg"] = "Already voted.";
		
		switch ($_POST["vote"]) 
		{
			case 'UP':
				upvote_comment($_POST["comm_id"]);   break;
			case 'DOWN':
				downvote_comment($_POST["comm_id"]); break;
			case 'UNVOTE':
				unvote_comment($_POST["comm_id"]);   break;
		}
		echo json_encode($json);
	}

?>