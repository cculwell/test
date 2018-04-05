<?php session_start(); ?>
<!DOCTYPE html>
<html class="no-js" lang="en" dir="ltr">

<head>
    <title>Users</title>   
	<?php
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
	?>
</head>
<body>
<?php
	if (isset ($_SESSION['valid_email']) && ($_SESSION['valid_status']=='Admin'))
	{?>		
		<a href='php/AddEditUser.php'><h3>Add a new user</h1></a><br>
		
		<?php	
		$sql = "SELECT * FROM users ORDER BY last_name";
		if ($result = mysqli_query($mysqli, $sql))
		{
			if ($result->num_rows > 0)
			{
				echo "<table border='1' cellpadding='15'>";
				echo "<tr><th><h4 align='center'>Last Name</h4></th><th><h4 align='center'>First Name</h4></th><th><h4 align='center'>Email</h4></th><th><h4 align='center'>Password</h4></th><th><h4 align='center'>Status</h4></th><th><h4 align='center'>Edit User</h4></th><th><h4 align='center'>Delete User</h4></th></tr>";
				while ($row=mysqli_fetch_row($result))
				{
					echo
						"<tr>"
							."<td width='10%' align='center'><h5>".$row[2]."</h5></td>"
							."<td width='10%' align='center'><h5>".$row[1]."</h5></td>"
							."<td width='10%' align='center'><h5>".$row[3]."</h5></td>"
							."<td width='8%' align='center'><h5>".$row[4]."</h5></td>"
							."<td width='5%' align='center'><h5>".$row[5]."</h5></td>"
							."<td width='5%' align='center'><h5><a href='php/AddEditUser.php?id=" . $row[0] . "'>Edit</a></h5></td>"
							."<td width='5%' align='center'><h5><a href='php/DeleteUser.php?id=" . $row[0] . "'>Delete</a></h5></td>"
						."</tr>";
				}
				// Free result set
				mysqli_free_result($result);
			}
			else
			{
				echo "No users to display.";
			}
		}
		else
		{
			echo "Error: " . $mysqli_error;
		}
		$mysqli->close();
		?>
<?php
	}
	else
	{
		echo "<p><h3>You are not authorized to view this page.</h3></p>";
		echo "<p><a href='../public_html/php/UserLogin.php'>User Login</a></p>";
		echo "<p><a href='../public_html/php/UserLogout.php'>User Logout</a></p>";
		echo "<p><a href='../public_html/Home.html'>Home Page</a></p>";
	}
?>
</body>
</html>