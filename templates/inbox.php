<script type="text/javascript">
  $(document).ready(function () {
    $('#pms').DataTable();
    $('#pms_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	$table = div(div(par("Received Messages"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($pms, ["sender", "subject", "msg", "time"], "table", "pms", [0]);

	echo to_html($table);
	
?>