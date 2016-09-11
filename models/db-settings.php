<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

//Database Information
$db_host = "localhost"; //Host address (most likely localhost)
$db_name = "apis"; //Name of Database
$db_user = "apis"; //Name of database user
$db_pass = "Bahth4eiemohw4Ux"; //Password for database user
$db_table_prefix = "apis_";

GLOBAL $errors;
GLOBAL $successes;

$errors = array();
$successes = array();

/* Create a new mysqli object with database connection parameters */
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
GLOBAL $mysqli;

if(mysqli_connect_errno()) {
	echo "Connection Failed: " . mysqli_connect_errno();
	exit();
}

//Direct to install directory, if it exists
if(is_dir("install/"))
{
	header("Location: install/");
	die();

}

?>
