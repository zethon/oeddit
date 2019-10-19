<?php
	
	$score = get_user_score($u["user_id"]);

	render_mult([	"user_common.php",
					"profile.php"
				],
				[
					"title"  => $u["username"]."'s Profile",
					"u"      => $u,
					"pg"     => "user.php?u=".$u["username"],
					"self"   => $self,
					"u"      => $u,
					"pscore" => $score["pscore"],
					"cscore" => $score["cscore"]
				]);

?>