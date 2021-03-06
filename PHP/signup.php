<?php
require_once "SQL/connection_variables.php";

// Initializing salt strings, and user / connection variables
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
$name = $username = $password = "";
$salt1 = "qQmOOp002!!!";
$salt2 = "$$5jsmOO!!&&&";

// If data is posted, assign to variables. In production, this input would be sanitized
if (isset($_POST['name']))
{
	$name = fix_string($_POST['name']);
}

if (isset($_POST['username']))
{
	$username = fix_string($_POST['username']);
}

if (isset($_POST['password']))
{
	$password = fix_string($_POST['password']);
}

if (isset($_POST['pass_confirm']))
{
	$val_pass = fix_string($_POST['pass_confirm']);
}

// Add results of validation functions to string
$fail = validate_name($name);
$fail .= validate_username($username);
$fail .= check_availability($username, $connection);
$fail .= validate_password($password, $val_pass);

// If the fail string is empty, there are no errors
if ($fail == "")
{
	// Create a token from the saltstrings and passwords to store in the db, for security reasons
	$token = hash('ripemd128', "$salt1$password$salt2");
	$result = add_user($connection, $name, $username, $token);

	if ($result)
	{
		echo "Account successfully added!";
	}
}

else
{
	echo $fail;
}

// Ensures user inserts a name
function validate_name($name)
{
	if ($name == "")
	{
		return "Please enter a name\n";
	}

	else
	{
		return "";
	}
}

// Ensures username is available
function check_availability($username, $connection)
{
	$query = "SELECT * FROM site_users WHERE username='$username'";
	$result = $connection -> query($query);

	if (!$result)
	{
		die($connection -> error);
	}

	// If there are any results from this query, their username is taken
	if (mysqli_num_rows($result) > 0)
	{
		return "Your username is already taken\n";
	}

	else
	{
		return "";
	}
}

// Ensure username is OK
function validate_username($username)
{
	// Initializing a fail parameter to add the errors to.
	$failparam = "";

	if ($username == "")
	{
		$failparam .= "Please enter a username\n";
	}

	// Checks length of username
	else if (strlen($username) < 3)
	{
		$failparam .= "You username is less than 3 characters\n";
	}

	// Uses regex to make sure the username doesn't have any strange characters in it
	else if (preg_match("/[^a-zA-Z0-9_-]/", $username))
	{
		$failparam .= "Usernames can only contain letters, numbers, -, and _.\n";
	}

	return $failparam;
}

// Ensures password is OK
function validate_password($password, $validation_password)
{
	$failparam = "";

	if ($password == "")
	{
		$failparam .= "Please enter a password\n";
	}

	if (strlen($password) < 6)
	{
		$failparam .= "Your password is less than 6 characters\n";
	}

	// Uses three regular expressions to make sure the pass contains an uppercase char, lowercase char, and a number
	if (!preg_match("/[a-z]/", $password) || 
		     !preg_match("/[A-Z]/", $password) ||
		     !preg_match("/[0-9]/", $password))
	{
		$failparam .= "Your password must contain a lowercase character, uppercase character, and a number.\n";
	}

	// If the two inputted passwords don't match, fail
	if ($password != $validation_password)
	{
		$failparam .= "Your passwords didn't match.\n";
	}

	return $failparam;
}

// In production, this would be used as a SQL injection preventative measure
function fix_string($string)
{
	if (get_magic_quotes_gpc())
	{
		$string = stripslashes($string);
	}

	return htmlentities($string);
}

// Function that actually adds the user into the database. Takes a hash, not password, for security.
function add_user($connection, $name, $username, $hash)
{
	$query = "INSERT INTO site_users VALUES('$name', '$username', '$hash')";
	$result = $connection -> query($query);

	if (!$result)
	{
		die($connection -> error);
	}

	return true;
}