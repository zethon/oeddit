<script type="text/javascript">
  $(document).ready(function () {
    $('#ureps').DataTable();
    $('#ureps_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	// user reports
	$table = div(div(par("Reports about users"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($reps, ["user", "reported by", "time", "reason"], "table", "ureps", [0, 1]);

	echo to_html($table);
	
?>