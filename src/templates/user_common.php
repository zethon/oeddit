<!-- New PM modal -->
<div>
	<div id="new-pm" class="modal fade">
		<div class="modal-dialog" role="form">
			<div class="modal-content">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">×</a>
					<h3>Send Private Message</h3>
				</div>
				<form id="pmf" class="pm" method="POST" action=<?php echo "\""."new_pm.php"."\""; ?> >
					<div class="modal-body">
						<div class="form-group">
							<input name="subject" class="form-control" type="text" placeholder="Subject">
						</div>
						<div class="form-group">
							<textarea name="text" class="form-control" rows="4" placeholder="Message"></textarea>
							<input class="hidden" name="to" value=<?php echo $u["username"]; ?> id="new-pm-btn">
						</div>
					<div class="modal-footer">
						<input class="btn btn-default" type="submit" value="Send" id="new-pm-btn">
						<a href="#" class="btn" data-dismiss="modal">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- user-report modal -->
<div>
	<div id="report-user" class="modal fade">
		<div class="modal-dialog" role="form">
			<div class="modal-content">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">×</a>
					<h3 id="report-user-heading">Report user</h3>
				</div>
				<form id="user_report_f" class="" method="POST" action="report_user.php">
					<div class="modal-body">
						<div class="form-group">
						<input name="report_username" id="report-user-id" class="form-control hidden" value=<?php echo $_GET["u"]; ?> readonly="">
						</div>
						<div class="form-group">
						<textarea name="report_user_reason" id="report-user-text" class="form-control" rows="4" placeholder="Reason for reporting..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<input class="btn btn-default" type="submit" value="Confirm" id="report-user-btn">
						<a href="#" class="btn" data-dismiss="modal">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="btn-toolbar" style="float:right">
<?php
	if (!$self) 
	{
		echo "<a class=\"btn btn-warning soc-report\" id=\"\" href=\"\" data-toggle=\"modal\" data-target=\"#report-user\" value=\"\" style=\"float:right;\">report</a>";
		echo "<a data-toggle=\"modal\" data-target=\"#new-pm\" class=\"btn btn-primary\" >Send PM</a>";
	}

?>
</div>

<?php 
	$t = isset($_GET["view"]) ? $_GET["view"]:"profile";
	$pg .= "&view=";
?>

<ul class="nav nav-tabs">
	<li class=<?php echo $t=="profile" ? "active":""?>>
		<a href=<?php echo $pg."profile" ?>>
			<span class="glyphicon glyphicon-user"></span>
	  		 Profile
		</a>
	</li>
	<li class=<?php echo $t=="phist" ? "active":""?>>
		<a href=<?php echo $pg."phist" ?>>
			<span class="glyphicon glyphicon-file"></span>
			Post History
		</a>
	</li>
	<?php
		if ($self) 
		{
			echo "<li class=\"".($t=="inbox" ? "active":"")."\" >".
					"<a href=\"".$pg."inbox"."\" >".
						"<span class=\"glyphicon glyphicon-envelope\"></span>".
						" Inbox".
					"</a>".
				"</li>".
				"<li class=\"".($t=="outbox" ? "active":"")."\" >".
					"<a href=\"".$pg."outbox"."\" >".
						"<span class=\"glyphicon glyphicon-envelope\"></span>".
						" Outbox".
					"</a>".
				"</li>".
				"<li class=\"".($t=="chist" ? "active":"")."\" >".
					"<a href=\"".$pg."chist"."\" >".
						"<span class=\"glyphicon glyphicon-comment\"></span>".
						" Comment History".
					"</a>".
				"</li>".
				"<li class=\"".($t=="socs" ? "active":"")."\" >".
					"<a href=\"".$pg."socs"."\" >".
						"<span class=\"glyphicon glyphicon-home\"></span>".
						" Societies".
					"</a>".
				"</li>";
		}
	?>
</ul>
<div class="well">