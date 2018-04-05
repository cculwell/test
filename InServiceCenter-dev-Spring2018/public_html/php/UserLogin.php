<!-- //Login page for users and admins -->
<?php
	session_start();
	require "../../resources/config.php";
	
	if (isset($_POST['email']) && isset($_POST['password']))
	{
		$email = $_POST['email'];
		$password = $_POST['password'];
		
		# create connection to database
		$mysqli = new mysqli($config['db']['amsti_01']['host']
			, $config['db']['amsti_01']['username']
			, $config['db']['amsti_01']['password']
			, $config['db']['amsti_01']['dbname']);

		/* check connection */
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", $mysqli->connect_error);
			exit();}	
			
		$sql = "SELECT * FROM users WHERE user_email='".$email."' AND user_password='".$password."'";
		$result = mysqli_query($mysqli, $sql);
		if ($result->num_rows > 0)
		{
			$row=mysqli_fetch_row($result);
			
			$_SESSION['valid_email'] = $email;
			$_SESSION['valid_status'] = $row[5];
		}
		$mysqli->close();
	}	
?>

<!DOCTYPE html>
<html>
<head>
<title>UserLogin</title>
</head>

<body>
<h1>User Login</h1>
<?php

	if (isset($_SESSION['valid_email']))
	{
        printf("<script>location.href='../WorkQueue.php'</script>");
//		header("location: ../WorkQueue.php");
	}
	else
	{
		if (isset($email))
		{
			echo "<p>You were not successfully logged in.</p>";
		}
		else
		{
			echo "<p>You are not logged in.</p>";
		}

		echo "<form action='UserLogin.php' method='post'>";
		echo "<h3>Login</h3>";
		echo "<p>Email Address:</p>";
		echo "<p><input type='text' name='email' id='email' size='30'/></p>";
		echo "<p>Password:</p>";
		echo "<p><input type='password' name='password' id='password' size='30'/></p>";
		echo "<button type='submit' name='login'>Login</button>";
		echo "</form>";
	}
?>

</body>
</html>