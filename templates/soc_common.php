<div class="container-fluid well well-lg page-header" >
	<h1 class="soc_title"><?php echo to_html(soc_link($soc["soc_name"]), "soc-title"); ?><small></small></h1>
	<div class="btn-toolbar">
		<hr>
		<?php
			echo "<a role=button href=\"soc.php?soc=".$soc["soc_name"]."&saction=";
			// sub/unsub button
			if ($status["sub"])
				echo "unsub\" class=\"btn btn-success btn-sm\"><span class=\"glyphicon glyphicon-ok-sign\"></span> Subscribed";
			else
				echo "sub\" class=\"btn btn-default btn-sm\"><span class=\"glyphicon glyphicon-plus-sign\"></span> Subscribe";
			echo "</a>";
			// info
			echo "	<a role=button href=\"soc.php?soc=".$soc["soc_name"]."&view=info\" class=\"btn btn-default btn-sm\">
							<span class=\"glyphicon glyphicon-info-sign\"></span>
							About
						</a>";
			// mod panel
			if ($status["mod"] || $status["admin"])
				echo "	<a role=button href=\"mod_panel.php?soc=".$soc["soc_name"]."\" class=\"btn btn-info btn-sm\">
							<span class=\"glyphicon glyphicon-cog\"></span> 
							Mod Panel
						</a>";
			if (!$status["admin"])
				echo "<a class=\"btn btn-xs btn-link soc-report-btn\" id=\"\" href=\"\" data-toggle=\"modal\" data-target=\"#report-soc\" value=\"3\" style=\"float:right;\">report</a>";
		?>
	</div>
</div>
<!-- society-report modal -->
<div>
	<div id="report-soc" class="modal fade">
		<div class="modal-dialog" role="form">
			<div class="modal-content">
				<div class="modal-header">
					<a class="close" data-dismiss="modal">×</a>
					<h3 id="report-soc-heading">Report society</h3>
				</div>
				<form id="soc_report_f" class="" method="POST" action=<?php echo "\"report_soc.php?soc=".$soc["soc_name"]."\""; ?> >
					<div class="modal-body">
						<div class="form-group">
						<input name="report_soc_id" id="report-soc-id" class="form-control hidden" value=<?php echo $soc["soc_id"]; ?> readonly="">
						</div>
						<div class="form-group">
						<textarea name="report_soc_reason" id="report-soc-text" class="form-control" rows="4" placeholder="Reason for reporting..."></textarea>
						</div>
					</div>
					<div class="modal-footer">
						<input class="btn btn-default" type="submit" value="Confirm" id="report-soc-btn">
						<a href="#" class="btn" data-dismiss="modal">Cancel</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>