<?php
require_once "SQL/connection_variables.php";

$name = $username = $password = "";
$salt1 = "qQmOOp002!!!";
$salt2 = "$$5jsmOO!!&&&";
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

if ($connection -> connect_error)
{
	die($connection -> connect_error);
}

if (isset($_POST['username'], $_POST['password']))
	{
		$user_temp = filter_input(INPUT_POST, 'username');
		$pass_temp = filter_input(INPUT_POST, 'password');

		$query = "SELECT * FROM site_users WHERE username='$user_temp'";
		$result = $connection -> query($query);

		if (!$result)
		{
			die($connection -> error);
		}

		elseif ($result -> num_rows)
		{
			$row = $result -> fetch_array(MYSQLI_NUM);
			$result -> close();

			$token = hash('ripemd128', "$salt1$pass_temp$salt2");

			if ($token == $row[2])
			{
				session_start();
				ini_set('session.gc.maxlifetime', 604800);
				$_SESSION['username'] = $user_temp;
				$_SESSION['password'] = $pass_temp;
				$_SESSION['name'] = $name = $row[0];
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