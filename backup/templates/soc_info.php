
<div class="panel panel-default well">
	<div class="panel-heading">
		<div class="row">
			<h3 class="col-md-3">About <?php echo $soc["soc_name"]; ?></h3>
			<div class="col-md-9" style="float:right;">
				<div style="float:right;">
				<a class="btn btn-default" data-toggle="modal" data-target="#soc-info-edit"><i class="fa fa-pencil-square-o"/></i>	Edit</a>
				<a class="btn btn-default" data-toggle="modal" data-target="#soc-info-hist"><i class="fa fa-history"/></i>	View history</a>
				</div>
			</div>
		</div>
	</div>
	<div class="panel-body well">
		<?php 
			if ($soc["info"])
			{
				echo "<small><p>Latest revision by ".u($soc["revised_by"]);
				echo " (".$soc["time"].")";
				echo "<p></small>";
				echo "<div class=\"well\"><p>".$soc["info"]."</p></div>";
			}
		?>
	</div>
</div>
<!-- editing modal -->
<div id="soc-info-edit" class="modal fade">
	<div class="modal-dialog" role="form">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Edit</h3>
			</div>
			<form id="soc-info-form" class="" method="POST" action="soc_info.php" >
				<div class="modal-body">
					<div class="form-group">
						<input name="soc_id" class="form-control hidden" value=<?php echo $soc["soc_id"]; ?> hidden readonly>
						<input name="soc_name" class="form-control hidden" value=<?php echo $soc["soc_name"]; ?> hidden readonly>
					</div>
					<div class="form-group">
						<textarea name="info" class="form-control" rows="" placeholder=""><?php echo $soc["info"]; ?></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<input class="btn btn-default" type="submit" value="Submit" id="new_post">
					<a href="#" class="btn" data-dismiss="modal">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- history modal -->
<div id="soc-info-hist" class="modal fade">
	<div class="modal-dialog" role="">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>History</h3>
			</div>
			<div class="modal-body">
				<div>
					<ul class="edit-hist">
						<?php
							foreach ($hist as $edit) 
							{
								echo "<li>";

								$link = a("#", "soc-rev-link", "soc-rev-link-".$edit["rev_id"]);
								$link["data"] = $edit["time"];
								$link["attribs"]["role"] = "button";
								$link["attribs"]["value"] = $edit["rev_id"];
								$link["attribs"]["data-toggle"] = "modal";
								$link["attribs"]["data-target"] = "#soc-old-info-view";
								echo to_html($link)." - ".u($edit["username"]).(($edit["rev_id"]==$soc["rev_id"]) ? "<strong>(current)</strong>":"");
								
								echo "</li>";
							}
						?>
					</ul>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			</div>
		</div>
	</div>
</div>

<!-- revision view modal -->
<div id="soc-old-info-view" class="modal fade">
	<div class="modal-dialog" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3 id="soc-old-info-details"></h3>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<p id="soc-old-info-text" class="" readonly></p>
				</div>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Cancel</a>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(".soc-rev-link").click(function() {
			$.ajax({
			    url: "soc_info_hist.php",
			    data: {
			        rev_id: $(this).attr("value")
			    },
			    type: "POST",
			    dataType : "json",
			    context: this,
			    success: function( revision ) {
			    	$("#soc-old-info-details").html("Revision by "+revision.username+" on "+revision.time);
			    	$("#soc-old-info-text").text(revision.info);
			    },
			    error: function( xhr, status, errorThrown ) {
			        alert( "Sorry, there was a problem!" );
			        console.log( "Error: " + errorThrown );
			        console.log( "Status: " + status );
			        console.dir( xhr );
			    }
			});
		});
	});
</script>