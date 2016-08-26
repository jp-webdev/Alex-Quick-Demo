<?php
require_once "connection_variables.php";

// Create initial connection, required to create the DB
$initial_connection = new mysqli($db_hostname, $db_username, $db_password);

// Run the initial connection through an error handling function
errorHandler($initial_connection);

$db_initialization_query = "CREATE DATABASE IF NOT EXISTS demodb;";

// Runs query, ensures there are no errors
$result = $initial_connection -> query($db_initialization_query);

if (!$result)
{
	die($connection -> error);
}

else
{
	// Close SQL conection, tell user DB was built
	mysqli_close($initial_connection);
	echo "DATABASE 'demodb' BUILT<br>";
}

// Initializes new SQL connection, this time with the db included
$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

// Creates table necessary for login / signup functionality
$table_creation_query = "CREATE TABLE IF NOT EXISTS site_users (
name VARCHAR(32) BINARY NOT NULL,
username VARCHAR(32) BINARY NOT NULL UNIQUE,
password VARCHAR(32) BINARY NOT NULL
);";

$result = $connection -> query($table_creation_query);

if (!$result)
{
	die($connection -> error);
}

else
{
	// Kill script, tell user their schema has finished building
	die("SCHEMA SUCCESSFULLY BUILT");
}
