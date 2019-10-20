<?php 
	$t = isset($_GET["view"]) ? $_GET["view"]:"profile";
	$pg = "user.php?&view=";
?>

<ul class="nav nav-tabs">
  <li role="presentation" class=<?php echo $t=="profile" ? "active":""?>><a href=<?php echo $pg."admins" ?> >Admin List</a></li>
  <li role="presentation" class=<?php echo $t=="inbox" ? "active":""?>><a href=<?php echo $pg."bans" ?>>User bans</a></li>
  <li role="presentation" class=<?php echo $t=="socs" ? "active":""?>><a href=<?php echo $pg."socs" ?>>Locked Societies</a></li>
  <li role="presentation" class=<?php echo $t=="log" ? "active":""?>><a href=<?php echo $pg."log" ?>>Admin Log</a></li>
  <li role="presentation" class=<?php echo $t=="ureps" ? "active":""?>><a href=<?php echo $pg."ureps" ?>>Reported users</a></li>
  <li role="presentation" class=<?php echo $t=="sreps" ? "active":""?>><a href=<?php echo $pg."sreps" ?>>Reported societies</a></li>
</ul>
<div class="well">