<script type="text/javascript">
  $(document).ready(function () {
    $('#user_log').DataTable();
    $('#soc_log').DataTable();
    $('#user_log_wrapper').css({"padding":"10px"});
    $('#soc_log_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	// User related activities
	$table = div(div(par("User related actions"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($log["user"], ["username", "admin name", "action", "time", "comment"], "table", "user_log", [0, 1]);

	echo to_html($table);
	
	// Soc related activities
	$table = div(div(par("Society related actions"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($log["soc"], ["society", "admin name", "action", "time", "comment"], "table", "soc_log", [1], [0]);

	echo to_html($table);
	
?>