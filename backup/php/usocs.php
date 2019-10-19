<?php

	$subs = get_subbed_socs();
	$msubs = get_modded_socs();

	render_mult([	"user_common.php",
					"usocs.php"
				],
				[
					"title" => $u["username"]."'s Profile",
					"subs"  => $subs,
					"msubs" => $msubs,
					"pg"	=> "user.php?u=".$u["username"],
					"self"  => $self
				]);

?>