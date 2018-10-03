<?php
/*
	/editor/set-add.php
*/

	// Connect to database
	require ('../config/open_db.php');
	include ("key.php");

	$fields = array (
		'name' => 'Name',
		'description'=> 'Description',
		'url' => 'URL',
		'contactName' => 'Contact name',
		'email' => 'Contact email'
		);
	
	extract ($_REQUEST);
	
	// If no parameters, display input form
	print "
<html>
<head>
<title>Add Set</title>
</head>
<body>
<h2>Add Set</h2>
";
	if (! isset ($key)) {
		print "<form method=POST action=set-add.php>\n<table>\n";
		foreach ($fields as $field => $display_name) {
			print "<tr><td align=right>$display_name</td>
				<td><input name=$field size=70></td></tr>\n";
		}
		$key = make_key();
		print "<tr><td align=right>Parent #</td>
				<td><input type=text name=parentID size=8></td></tr>
<tr><td colspan=2 align=center>
<input type=hidden name=key value='$key'>
<input type=submit value='Add set &gt;&gt;'>
</td></tr>
</table>
</form>
";
	}

	// Otherwise, insert the data
	else {
		// Make sure the key is valid
		if (! is_valid_key($key)) {
			print "<p>Invalid key!  No set added</p>\n";
		}
		else {
			foreach ($fields as $field => $display_name) {
				$value = mysql_real_escape_string ($_REQUEST [$field]);
				$q[] = "$field = '$value'";
			}
			$query = "INSERT INTO ElementSet SET " . implode(", ", $q);
			if (ctype_digit ($parentID))
				$query .= ", parentID = $parentID";
			$result = mysql_query ($query)
				or die(mysql_error());
			$id = mysql_insert_id ();	
			print "<p>Set # $id added:  $name</p>
<p>&nbsp;</p>
<p><a href=set-add.php>Add another set</a></p>
<p><a href=index.php>Editor menu</a></p>
";
			
		}
		
	}
	
	print "<p>&nbsp;</p>
<p><a href=index.php>Editor menu</a></p>
";

?>