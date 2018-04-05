<!-- //Allows an admin to add or edit a user/admin from the database-->
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
	function addEdit($first_name = '', $last_name = '', $email = '', $password = '', $status = '', $error = '', $id = '')
	{
?>			
		<!DOCTYPE html>
		<html>
			<head>
				<title><?php if ($id != '') { echo "Edit User"; } else { echo "New User"; } ?></title>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			</head>
			<body>
				<h1>
				<?php 
					if ($id != '')
					{
						echo "Edit user";
					}
					else
					{
						echo "New user";
					}
				?>
				</h1>
				<?php
					if ($error != '')
					{
						echo "<div style='padding:4px; border:1px solid red; color:red'>" . $error . "</div>";
					}
				?>
				
			<form action="" method="post">
				<div>
				<?php
					if ($id != '')
					{?>
						<input type="hidden" name="id" value="<?php echo $id; ?>" />
					<?php } ?>
					<strong>First Name:</strong> <input type="text" name="firstname" value="<?php echo $first_name; ?>"/><br/>
					<strong>Last Name:&nbsp;</strong> <input type="text" name="lastname" value="<?php echo $last_name; ?>"/><br/>
					<strong>Email:&emsp;&emsp;&nbsp;</strong> <input type="text" name="email" value="<?php echo $email; ?>"/><br/>
					<strong>Password:&ensp;&nbsp;</strong> <input type="text" name="password" value="<?php echo $password; ?>"/><br/>
					<strong>Status:&emsp;&emsp;&nbsp;</strong> <select name="status">
						<option <?php if($status == 'User') echo "selected"; ?> value="User">User</option>
						<option <?php if($status == 'Admin') echo "selected" ?> value="Admin">Admin</option></select>
					<p><input type="submit" name="submit" value="Submit" /></p>
				</div>
			</form>
			</body>
		</html>

<?php
	}
//Edit an existing user's information
	if (isset($_GET['id']))
	{
		if (isset($_POST['submit']))
		{
			if (is_numeric($_POST['id']))
			{
				$user_id = $_POST['id'];
				$first_name = htmlentities($_POST['firstname'], ENT_QUOTES);
				$last_name = htmlentities($_POST['lastname'], ENT_QUOTES);
				$user_email = htmlentities($_POST['email'], ENT_QUOTES);
				$user_password = htmlentities($_POST['password'], ENT_QUOTES);
				$user_status = htmlentities($_POST['status'], ENT_QUOTES);
				
				if ($first_name == '' || $last_name == '')
				{
					$error = 'ERROR: Please fill in all required fields';
					addEdit($first_name, $last_name, $user_email, $user_password, $user_status, $error, $user_id);
				}
				else
				{
					if ($stmt = ($mysqli->prepare("UPDATE users SET first_name = ?, last_name = ?, user_email = ?, user_password = ?, user_status = ? WHERE user_id = ?")))
					{
						$stmt->bind_param("sssssi", $first_name, $last_name, $user_email, $user_password, $user_status, $user_id);
						$stmt->execute();
						$stmt->close();
					}
					else
					{
						echo "ERROR: could not prepare SQL statement.";
					}
//					header("Location: ../WorkQueue.php");
                    printf("<script>location.href='../WorkQueue.php'</script>");
				}
			}
			else
			{
				echo "ERROR!";
			}
		}
		else
		{
			if (is_numeric($_GET['id']) && $_GET['id'] > 0)
			{
				$user_id = $_GET['id'];
				
				if ($stmt = $mysqli->prepare("SELECT * FROM users WHERE user_id = ?"))
				{
					$stmt->bind_param("i", $user_id);
					$stmt->execute();
					$stmt->bind_result($user_id, $first_name, $last_name, $user_email, $user_password, $user_status);
					$stmt->fetch();
					
					addEdit($first_name, $last_name, $user_email, $user_password, $user_status, NULL, $user_id);
					
					$stmt->close();
				}
				else
				{
					echo "ERROR: could not prepare SQL statement";
				}
			}
			else
			{	
//				header("Location: ../WorkQueue.php");
                printf("<script>location.href='../WorkQueue.php'</script>");
			}
		}
	}
	
//Add a new user's information

	else
	{
		if (isset($_POST['submit']))
		{
			$first_name = htmlentities($_POST['firstname'], ENT_QUOTES);
			$last_name = htmlentities($_POST['lastname'], ENT_QUOTES);
			$user_email = htmlentities($_POST['email'], ENT_QUOTES);
			$user_password = htmlentities($_POST['password'], ENT_QUOTES);
			$user_status = htmlentities($_POST['status'], ENT_QUOTES);
			
			if ($first_name == '' || $last_name == '')
			{
				$error = 'ERROR: Please fill in all required fields';
				addEdit($first_name, $last_name, $user_email, $user_password, $user_status, $error);
			}
			else
			{
				if ($stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, user_email, user_password, user_status) VALUES (?, ?, ?, ?, ?)"))
				{
					$stmt->bind_param("sssss", $first_name, $last_name, $user_email, $user_password, $user_status);
					$stmt->execute();
					$stmt->close();
				}
				else
				{
					echo "ERROR: could not prepare SQL statement";
				}
//				header("Location: ../WorkQueue.php");
                printf("<script>location.href='../WorkQueue.php'</script>");
			}
		}
		else
		{
			addEdit();
		}
	}
}
else
{
	echo "<p><h3>You are not authorized to view this page.</h3></p>";
	echo "<p><a href='../public_html/php/UserLogin.php'>User Login</a></p>";
	echo "<p><a href='../public_html/php/UserLogout.php'>User Logout</a></p>";
	echo "<p><a href='../public_html/Home.html'>Home Page</a></p>";
}
$mysqli->close();
?>