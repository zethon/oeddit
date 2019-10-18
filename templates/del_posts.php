<script type="text/javascript">
  $(document).ready(function () {
    $('#del_posts').DataTable();
    $('#del_posts_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	// user reports
	$table = div(div(par("Deleted posts"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($posts, ["title", "text", "deleted by", "time", "comment"], "table", "del_posts", [2], [], [0]);

	echo to_html($table);
	
?>