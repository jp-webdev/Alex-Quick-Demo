<?php
require_once "SQL/connection_variables.php";

// Creating the salt strings to secure password, and initializing user / connection variables
$name = $username = $password = "";
$salt1 = "qQmOOp002!!!";
$salt2 = "$$5jsmOO!!&&&";
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

if ($connection -> connect_error)
{
	die($connection -> connect_error);
}

// When the script recieves input ...
if (isset($_POST['username'], $_POST['password']))
	{
		// Grab the username and pass from post. Sanitization function would go here, in a full site
		$user_temp = filter_input(INPUT_POST, 'username');
		$pass_temp = filter_input(INPUT_POST, 'password');

		$query = "SELECT * FROM site_users WHERE username='$user_temp'";
		$result = $connection -> query($query);

		if (!$result)
		{
			die($connection -> error);
		}

		// If query returns a result...
		elseif ($result -> num_rows)
		{
			// Assign the row associated with the login information to the $row variable
			$row = $result -> fetch_array(MYSQLI_NUM);
			$result -> close();

			// Regenerating the password token. Should match pw in db
			$token = hash('ripemd128', "$salt1$pass_temp$salt2");

			// If our generated token matches the token in the db ...
			if ($token == $row[2])
			{
				// Start session, initialize session variables
				session_start();
				ini_set('session.gc.maxlifetime', 604800);
				$_SESSION['username'] = $user_temp;
				$_SESSION['password'] = $pass_temp;
				$_SESSION['name'] = $name = $row[0];
				// Create an assertin variable that can be checked on other pages in production. Unique ID used to prevent session hijacking
				$_SESSION['assertion'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'].$_SERVER['HTTP_USER_AGENT']);

				echo "Hi, $row[0]! Redirecting you to the salary calculation tool";
				die();
			}

			else
			{
				echo "Invalid username / password";
			}
		}

		else
		{
			echo "Invalid username / password";
		}
	}