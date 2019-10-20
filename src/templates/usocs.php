<script type="text/javascript">
  $(document).ready(function () {
    $('#umsocs').DataTable();
    $('#ussocs').DataTable();
    $('#umsocs_wrapper').css({"padding":"10px"});
    $('#ussocs_wrapper').css({"padding":"10px"});
  });
</script>

<?php

	$table = div(div(par("Societies you moderate"), "panel-heading"), "panel panel-primary");
	$table["children"][] = make_table($msubs, ["society", "mod since"], "table", "umsocs", [], [0]);

	echo to_html($table);

	$table = div(div(par("Subscribed subs"), "panel-heading"), "panel panel-primary");
	$table["children"][] = make_table($subs, ["society", "subbed since"], "table", "ussocs", [], [0]);

	echo to_html($table);

?>