<?php
require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

if(!isUserLoggedIn())
{
   header("Location: login.php");
   die();
}

$api = $_GET['api'];
setcookie("api", $api);
header("Location: account.php");

?>

