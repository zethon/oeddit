
<?php 
	$t = isset($_GET["view"]) ? $_GET["view"]:"mods";
	$pg = "mod_panel.php?soc=".$soc["soc_name"]."&view=";
?>

<div class="panel container-fluid well">
<div class="panel-heading well well-sm">
	<h3>Mod Panel</h3>
</div>
<div class="panel-body well well-sm">
<ul class="nav nav-tabs">
	<li role="navigation" class=<?php echo $t=="main" ? "active":""?>>
		<a href=<?php echo $pg."main" ?>> 
			<span><i class="fa fa-tachometer"></i></span>
			Dashboard
		</a>
	</li>
	<li role="presentation" class=<?php echo $t=="mods"  ? "active":"" ?> >
		<a href=<?php echo $pg."mods" ?>>
			<span class="glyphicon glyphicon-list"></span>
		   Mod List
		</a>
	</li>
	<li role="presentation" class=<?php echo $t=="bans"  ? "active":"" ?> >
		<a href=<?php echo $pg."bans" ?>>
			<span class="glyphicon glyphicon-ban-circle"></span>
			 User bans
		</a>
	</li>
	<li role="presentation" class=<?php echo $t=="log"   ? "active":"" ?> >
		<a href=<?php echo $pg."log" ?>>
			<span class="glyphicon glyphicon-list-alt"></span>
			 Mod Log
		</a>
	</li>
	<li role="presentation" class=<?php echo $t=="dposts" ? "active":"" ?> >
		<a href=<?php echo $pg."dposts" ?>>
			<span class="glyphicon glyphicon-file"></span>
			 Deleted Posts
		</a>
	</li>
	<li role="presentation" class=<?php echo $t=="dcomms" ? "active":"" ?> >
		<a href=<?php echo $pg."dcomms" ?>>
			<span class="glyphicon glyphicon-comment"></span>
			 Deleted Comments
		</a>
	</li>
	<li role="presentation" class=<?php echo $t=="preps" ? "active":"" ?> >
		<a href=<?php echo $pg."preps" ?>>
			<span class="glyphicon glyphicon-warning-sign"></span>
			<span class="glyphicon glyphicon-file"></span>
			 Reported posts
		</a>
	</li>
	<li role="presentation" class=<?php echo $t=="creps" ? "active":"" ?> >
		<a href=<?php echo $pg."creps" ?>>
			<span class="glyphicon glyphicon-warning-sign"></span>
			<span class="glyphicon glyphicon-comment"></span>
			 Reported comments
		</a>
	</li>
</ul>
<div class="well">