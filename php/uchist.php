<?php
	
	$comms = get_comm_hist($u);

	render_mult([	
					"user_common.php",
					"uchist.php"
				],
				[
					"title" => $u["username"]."'s Profile",
					"comms" => $comms,
					"pg"	=> "user.php?u=".$u["username"],
					"self"  => $self,
					"u" => $u
				]);

?>