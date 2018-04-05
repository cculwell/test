<!-- //Logout page for users and admins -->
<?php
	session_start();
	
	$userToLogout = $_SESSION['valid_email'];
	unset ($_SESSION['valid_email']);
	unset ($_SESSION['valid_status']);
	session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Logout</title>
</head>
<body>
<h1>User Logout</h1>
<?php
	if (!empty($userToLogout))
	{
		echo "<p>You have been successfully logged out.</p>";
	}
	else
	{
		echo "<p>You were not logged in.</p>";
	}
?>	
	<p><a href="UserLogin.php">User Login</a></p>
	<p><a href="../Home.html">Home Page</a></p>
	
</body>
</html>