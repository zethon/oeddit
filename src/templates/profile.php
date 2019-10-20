
<dl class="dl-horizontal">
  <dt>Username</dt>
  <dd><?php echo $u["username"]; ?></dd>
  <dt>Member since</dt>
  <dd><?php echo $u["join_date"]; ?></dd>
  <dt>Post Score</dt>
  <dd><?php echo $pscore ?></dd>
  <dt>Comment Score</dt>
  <dd><?php echo $cscore ?></dd><div>
</dl>

<?php

	if ($self)
	{
		echo 
		"<a href=\"change_pass.php\" class=\"btn btn-default col-sm-offset-1\"> ".
			"Change Password ".
		"</a>";
	}

?>
