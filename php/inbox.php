<?php

	// require("../includes/global.php");

	$pms = get_inbox();

	render_mult([	"user_common.php",
					"inbox.php"
				],
				[
					"title" => $u["username"]."'s Inbox",
					"pg" => "user.php?u=".$u["username"],
					"self" => $self,
					"u" => $u,
					"pms" => $pms
				]);

?>