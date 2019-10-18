<?php

	// require("../includes/global.php");

	$pms = get_outbox();

	render_mult([	"user_common.php",
					"outbox.php"
				],
				[
					"title" => $u["username"]."'s Outbox",
					"pg" => "user.php?u=".$u["username"],
					"self" => $self,
					"u" => $u,
					"pms" => $pms
				]);

?>