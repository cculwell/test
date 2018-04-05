<?php
	session_start();
	require "../../resources/config.php";
	# create connection to database
	$mysqli = new mysqli($config['db']['amsti_01']['host']
		, $config['db']['amsti_01']['username']
		, $config['db']['amsti_01']['password']
		, $config['db']['amsti_01']['dbname']);

	/* check connection */
	if ($mysqli->connect_errno) {
		printf("Connect failed: %s\n", $mysqli->connect_error);
		exit();
	}	
if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin'))
{	
	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$id = $_GET['id'];
		
		if ($stmt = $mysqli->prepare("DELETE FROM users WHERE user_id = ? LIMIT 1"))
		{
			$stmt->bind_param("i", $id);
			$stmt->execute();
			$stmt->close();
		}
		else
		{
			echo "ERROR: could not prepare SQL statement";
		}
		$mysqli->close();
	}
    printf("<script>location.href='../WorkQueue.php'</script>");
}
else
{
	echo "<p><h3>You are not authorized to view this page.</h3></p>";
	echo "<p><a href='../public_html/php/UserLogin.php'>User Login</a></p>";
	echo "<p><a href='../public_html/php/UserLogout.php'>User Logout</a></p>";
	echo "<p><a href='../public_html/Home.html'>Home Page</a></p>";
}
?>