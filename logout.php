<?php
/*
UserCake Version: 2.0.2
http://usercake.com
*/

require_once("models/config.php");
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Log the user out
if(isUserLoggedIn())
{
	$loggedInUser->userLogOut();
}

$websiteUrl = '';
if(!empty($websiteUrl)) 
{
	$add_http = "";
	
	if(strpos($websiteUrl,"http://") === false && strpos($websiteUrl, "https://") === false)
	{
		$add_http = "https://";
	}
	
	header("Location: ".$add_http.$websiteUrl);
	die();
}
else
{
	header("Location: https://".$_SERVER['HTTP_HOST']."/apis");
	die();
}	

?>

