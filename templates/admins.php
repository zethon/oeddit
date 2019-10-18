
<script>
	$(document).ready(function() {
		$('#admin_list').DataTable();
		$('#admin_list_wrapper').css({"padding":"10px"});
	} );
</script>

<?php

	// promote form
	$fdiv = div(div(par("Promote a user to admin"), "panel-heading"), "panel panel-success");
	$form = make_form("admin_panel.php?view=admins", "post", "form-inline");
	$form = add_field($form, "user_to_admin", "Username", true, "form-control");
	$form = add_field($form, "admin_reason", "Reason for promotion", true, "form-control");
	$form = add_button($form, "Admin", "btn btn-default");
	$fdiv["children"][] = div(div($form, "form-group"), "panel-body");
	
	echo to_html($fdiv);

	// demote form
	$fdiv = div(div(par("De-admin a user"), "panel-heading"), "panel panel-danger");
	$form = make_form("admin_panel.php?view=admins", "post", "form-inline");
	$form = add_field($form, "user_to_deadmin", "Username", true, "form-control");
	$form = add_field($form, "deadmin_reason", "Reason for demotion", true, "form-control");
	$form = add_button($form, "Deadmin", "btn btn-default");
	$fdiv["children"][] = div(div($form, "form-group"), "panel-body");
	
	echo to_html($fdiv);

	// admin list
	$table = div(div(par("Admins"), "panel-heading"), "panel panel-info");
	$table["children"][] = make_table($admins, ["admin name", "promoted by", "time", "reason"], "table", "admin_list", [0, 1]);

	echo to_html($table);

?>