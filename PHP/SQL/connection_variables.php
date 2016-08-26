<?php
$db_hostname = '127.0.0.1';
$db_database = 'demodb';
$db_username = 'root';
$db_password = '';

// Sanitization functions, would be implemented in a non-demo version of the site
function mysql_entities_fix_string($connection, $string)
{
    return htmlentities(mysql_fix_string($connection, $string));
}

function mysql_fix_string($connection, $string)
{
    if (get_magic_quotes_gpc())
    {
        $string = stripslashes($string);
        return $connection -> real_escape_string($string);
    }
}

// Error handling function used to simplfy SQL connections
function errorHandler($connection)
{
	if ($connection -> connect_error)
	{
		die($connection -> connect_error);
	}
}
?> 