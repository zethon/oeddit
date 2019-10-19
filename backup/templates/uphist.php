<script type="text/javascript">
  $(document).ready(function () {
    $('#uphist').DataTable();
    $('#uphist_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	$table = div(div(par("Post history"), "panel-heading"), "panel panel-primary");
	$table["children"][] = make_table($posts, ["title", "votes", "society", "time"], "table", "uphist", [], [2], [0]);

	echo to_html($table);

?>