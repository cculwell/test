<?php
session_start()
?>

	<!DOCTYPE html>
	<html class="no-js" lang="en" dir="ltr">

	<head>
		<title>Work Queue</title>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../resources/library/bootstrap/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="../resources/library/jquery-ui/jquery-ui.min.css">
		<link rel="stylesheet" href="../resources/library/DataTables/datatables.css">
		<link rel="stylesheet" href="../resources/library/DataTables/Select/css/select.dataTables.css">
		<link rel="stylesheet" href="css/WorkQueue.css">

		<script src="../resources/library/jquery-3.2.1.min.js"></script>
		<script src="../resources/library/jquery-ui/jquery-ui.min.js"></script>
		<script src="../resources/library/DataTables/datatables.js"></script>
		<script src="../resources/library/DataTables/Select/js/dataTables.select.min.js"></script>
		<script src="../resources/library/jquery_chained/jquery.chained.js"></script>


	</head>
	<?php
	if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin' || $_SESSION['valid_status']=='User'))
{?>
	<body>
	<div class="callout large">
	</div>
	<div class="row setup-content" id="step-1">
		<div class="col-xs-12">
			<div class="col-xs-12 well text-center">

				<div class="col-xs-6" id="left-side">
					<div class="col-xs-12  row">

						<div class="col-xs-12 panel-primary row">
						<div class="panel-heading">Admin</div>
						<div class="panel-body" id="reports_page"></div>
						<script>
							$("#reports_page").load( 'php/div_wq_admin.php' );
						</script>
						</div>

						<div class="col-xs-12 panel-primary row">
							<div class="panel-heading">Work Queue</div>
							<div class="panel-body" id="div_wq_tables"></div>
							<script>
								$("#div_wq_tables").load( 'php/div_wq_tables.php' );
	//                            $( "#div_wq_tables" ).resizable();
							</script>
						</div>




					</div>
				</div>


				<div class="col-xs-6" id="right-side">
					<div class="col-xs-12 panel-primary">
						<div class="panel-heading">Requests Details</div>
						<div class="panel-body" id="div_wq_form"></div>
						<div id="debug"></div>
						<script>
							$("#div_wq_form").load( 'php/div_wq_form.php' );
						</script>
					</div>
				</div>
				<p><a href='php/UserLogout.php'style='float: left'>Logout</a></p>
			</div>
		</div>
	</div>
	</body>
	</html>
<?php
}
else
{
	echo "<p><h3>You are not authorized to visit this page.</h3></p>";
	echo "<p><a href='php/UserLogin.php'>User Login</a></p>";
	echo "<p><a href='php/UserLogout.php'>User Logout</a></p>";
	echo "<p><a href='Home.html'>Home Page</a></p>";
}
?>
