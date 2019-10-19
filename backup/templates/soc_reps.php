<script type="text/javascript">
  $(document).ready(function () {
    $('#sreps').DataTable();
    $('#sreps_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	// user reports
	$table = div(div(par("Reports about societies"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($reps, ["society", "reported by", "time", "reason"], "table", "sreps", [1], [0]);

	echo to_html($table);
	
?>