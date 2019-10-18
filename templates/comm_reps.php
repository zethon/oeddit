<script type="text/javascript">
  $(document).ready(function () {
    $('#creps').DataTable();
    $('#creps_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	// user reports
	$table = div(div(par("Reports about posts"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($reps, ["comment text", "reported by", "time", "reason"], "table", "creps");

	echo to_html($table);
	
?>