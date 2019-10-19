<?php 
	$t = isset($_GET["view"]) ? $_GET["view"]:"admins";
	$pg = "admin_panel.php?&view=";
?>
<div class="panel container-fluid well">
<div class="panel-heading well well-sm">
	<h3>Admin Panel</h3>
</div>
<div class="panel-body well well-sm">
	<div>
		<ul class="nav nav-tabs">
			<li role="navigation" class=<?php echo $t=="main" ? "active":""?>>
				<a href=<?php echo $pg."main" ?>> 
					<span><i class="fa fa-tachometer"></i></span>
					Dashboard
				</a>
			</li>
			<li role="navigation" class=<?php echo $t=="admins" ? "active":""?>>
				<a href=<?php echo $pg."admins" ?>> 
					<span class="glyphicon glyphicon-list"></span>
					Admin List
				</a>
			</li>
			<li role="navigation" class=<?php echo $t=="bans" ? "active":""?>>
				<a href=<?php echo $pg."bans" ?>>
					<span class="glyphicon glyphicon-ban-circle"></span>
					User bans
				</a>
			</li>
			<li role="navigation" class=<?php echo $t=="locks" ? "active":""?>>
				<a href=<?php echo $pg."locks" ?>>
					<span class="glyphicon glyphicon-lock"></span>
					Locked Societies
				</a>
			</li>
			<li role="navigation" class=<?php echo $t=="log" ? "active":""?>>
				<a href=<?php echo $pg."log" ?>>
					<span class="glyphicon glyphicon-list-alt"></span>
					Admin Log
				</a>
			</li>
			<li role="navigation" class=<?php echo $t=="ureps" ? "active":""?>>
				<a href=<?php echo $pg."ureps" ?>>
					<span class="glyphicon glyphicon-warning-sign"></span>
					<span class="glyphicon glyphicon-user"></span>
					Reported users
				</a>
			</li>
			<li role="navigation" class=<?php echo $t=="sreps" ? "active":""?>>
				<a href=<?php echo $pg."sreps" ?>>
					<span class="glyphicon glyphicon-warning-sign"></span>
					<span class="glyphicon glyphicon-home"></span>
					Reported societies
				</a>
			</li>
		</ul>
	</div>
	<div class="well">