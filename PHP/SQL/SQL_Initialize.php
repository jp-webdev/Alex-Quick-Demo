<?php
require_once "connection_variables.php";

$initial_connection = new mysqli($db_hostname, $db_username, $db_password);

errorHandler($initial_connection);

$db_initialization_query = "CREATE DATABASE IF NOT EXISTS demodb;";

$result = $initial_connection -> query($db_initialization_query);

if (!$result)
{
	die($connection -> error);
}

else
{
	mysqli_close($initial_connection);
	echo "DATABASE 'demodb' BUILT<br>";
}

$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);

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
	die("SCHEMA SUCCESSFULLY BUILT");
}
