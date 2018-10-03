<?php
session_cache_limiter ('private_no_expire, must-revalidate');
//echo session_cache_limiter();
//echo session_cache_expire();
// Starting the session
session_start(); 

if($_SESSION['id']=="") {
	//set the returnURL variable for the page they were trying to see
	$returnURL=$_SERVER['REQUEST_URI'];
	//redirect the user to the login page
	header("Location:/authsub/example-google.php?uri=$returnURL");
	exit;
}
?>