<?php
if (!securePage($_SERVER['PHP_SELF'])){die();}

//Links for logged in user
if(isUserLoggedIn()) {
	echo "
	<ul>
        <li><b>". $loggedInUser->displayname ."</b></li>
	<li class='leftmenu'><a class='leftmenu' href='apis.php'>Available APIs</a></li>
	<li class='leftmenu'><a class='leftmenu' href='user_settings.php'>User Settings</a></li>
	<li class='leftmenu last'><a class='leftmenu' href='logout.php'>Logout</a></li>";
	
	//Links for permission level 2 (default admin)
	if ($loggedInUser->checkPermission(array(2))){
	echo "
	<li class='leftmenu' style='margin-top: 2em;'><a class='leftmenu' href='admin_configuration.php'>Admin Configuration</a></li>
	<li class='leftmenu'><a class='leftmenu' href='admin_users.php'>Admin Users</a></li>
	<li class='leftmenu'><a class='leftmenu' href='admin_permissions.php'>Admin Permissions</a></li>
	<li class='leftmenu last'><a class='leftmenu' href='admin_pages.php'>Admin Pages</a></li>
	";
	}
	echo "
        <li class='leftmenu' style='margin-top: 2em;'><a class='leftmenu' href='help.php'>Help</a></li>
	<li class='leftmenu last'><a class='leftmenu' href='about.php'>About</a></li>
	</ul>";
} 
//Links for users not logged in
else {
	echo "
	<ul>
	<li class='leftmenu'><a class='leftmenu' href='login.php'>Login</a></li>
	<li class='leftmenu'><a class='leftmenu' href='register.php'>Register</a></li>
	<li class='leftmenu'><a class='leftmenu' href='forgot-password.php'>Forgot Password</a></li>";
	if ($emailActivation)
	{
	echo "<li class='leftmenu'><a class='leftmenu' href='resend-activation.php'>Resend Activation Email</a></li>";
	}
	echo "
        <li class='leftmenu' style='margin-top: 2em;'><a class='leftmenu' href='help.php'>Help</a></li>
	<li class='leftmenu last'><a class='leftmenu' href='about.php'>About</a></li>
	</ul>";
}

?>
