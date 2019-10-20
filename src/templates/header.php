<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="DataTables/datatables.min.css" rel="stylesheet">
		<link href="css/font-awesome.min.css" rel="stylesheet">
		<script src="js/jquery-2.1.3.min.js"></script>
		<script src="DataTables/datatables.min.js" rel="stylesheet"></script>
		<script src="js/bootstrap.min.js"></script>

		<title><?php echo $title; ?></title>
		<style>
			/*body { background-color: #ccc; }*/
			.post-title { color: black; }
			.soc-title { color: black; }
			.post-details { color: #777; }
			.modal { text-align: left; }
			a { color: #555; }
			/*
			.active { background-color: #aaa; }
			.well { background-color: #aaa;}*/
		</style>
	</head>
	<body>
		<nav class="navbar navbar-inverse navbar-static-top">
				<div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
					  <a class="navbar-brand" href="/">oeddit</a>
					</div>

			    	<ul class="nav navbar-nav">
					<p class="navbar-text navbar-right">
						<?php
							if (isset($_SESSION["user"]))
							{
								echo "Signed in as ";
								echo "<a href=\"user.php\" class=\"navbar-link\">";
								echo $_SESSION["user"]["username"];
								echo "</a> (";
								echo "<a href=\"logout.php\" class=\"navbar-link\" >logout";
								echo "</a>)";
							}
						?>
					</p>
			    		<li>
							<?php
								if (isset($_SESSION["user"]) && $_SESSION["user"]["status"] == "ADMIN")
								{
									echo "<a href=\"admin_panel.php\" class=\"navbar-link\">";
										echo "<span class=\"glyphicon glyphicon-cog\"> </span>";
										echo " Admin Panel";
									echo "</a>";
								}
							?>
						</li>
					</ul>
				</div><!-- /.container-fluid -->
		</nav>
		<div class="container">