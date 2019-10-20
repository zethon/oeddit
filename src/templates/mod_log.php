<script type="text/javascript">
  $(document).ready(function () {
    $('#user_log').DataTable();
    $('#post_log').DataTable();
    $('#comm_log').DataTable();
    $('#user_log_wrapper').css({"padding":"10px"});
    $('#post_log_wrapper').css({"padding":"10px"});
    $('#comm_log_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	// User related activities
	$table = div(div(par("User related actions"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($log["user"], ["username", "mod name", "action", "time", "comment"], "table", "user_log", [0, 1]);

	echo to_html($table);
	
	// Post related activities
	$table = div(div(par("Post related actions"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($log["post"], ["title", "mod name", "action", "time", "comment"], "table", "post_log", [1]);

	echo to_html($table);
	
	// Comment related activities
	$table = div(div(par("Comment related actions"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($log["comm"], ["text", "mod name", "action", "time", "comment"], "table", "comm_log", [1]);

	echo to_html($table);

?>