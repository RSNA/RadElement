<?php
	$dbname = "radelement";
	$host = "localhost";
	$user = "root";


	$dbh = mysql_connect ($host,$user,"")
		or die ('Cannot connect to database: ' . mysql_error(). '');
	mysql_select_db ($dbname)
		or die ('Cannot select database: ' . mysql_error());
	mysql_query( "SET CHARACTER SET utf8", $dbh);
	mysql_query ("SET NAMES 'utf8'", $dbh);
?>
