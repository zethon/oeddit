<script type="text/javascript">
  $(document).ready(function () {
    $('#pms').DataTable();
    $('#pms_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	$table = div(div(par("Sent Messages"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($pms, ["receiver", "subject", "msg", "time"], "table", "pms");

	echo to_html($table);
	
?>