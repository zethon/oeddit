<?php
	
	$posts = get_post_hist($u);

	render_mult([	
					"user_common.php",
					"uphist.php"
				],
				[
					"title" => $u["username"]."'s Profile",
					"posts" => $posts,
					"pg"	=> "user.php?u=".$u["username"],
					"self"  => $self,
					"u" => $u
				]);

?>