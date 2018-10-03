<?php
/*
	/editor/set-edit.php
*/

	// Connect to database
	require ('../config/open_db.php');
	
	// Specify input fields
	$fields = array (
		'name' => 'Name',
		'description'=> 'Description',
		'url' => 'URL',
		'contactName' => 'Contact name',
		'email' => 'Contact email'
		);
	
	extract ($_REQUEST);
	
	// Display page header
	print "
<html>
<head>
<title>Edit Set</title>
</head>
<body>
<h2>Edit Set</h2>
";

	// If no parameters, ask set number
	if (! isset ($id)) {
		print "<form method=POST action=set-edit.php>
<p>Set ID:&nbsp; RDES<input name=id size=8><input type=submit value='&gt;&gt;'></p>
</form>
<p>&nbsp;</p>
<p><a href=index.php>Editor menu</a></p>
";
		exit;
	}
		
	// If ID, but no key, display the values
	else if (! isset ($key)) {
		
		// Make sure ID is numeric and a valid set number
		if (! ctype_digit($id)) {
			print "<form method=POST action=set-edit.php>
<p>Set ID:&nbsp; RDES<input name=id size=8><input type=submit value='&gt;&gt;'></p>
</form>
<p>Invalid ID '$id'</p>
<p>&nbsp;</p>
<p><a href=index.php>Editor menu</a></p>
";
			exit (1);
		}
		$row = mysql_fetch_assoc (mysql_query (
				"SELECT * FROM ElementSet WHERE id = $id LIMIT 1"));
		if (! $row) {
			print "<form method=POST action=set-edit.php>
<p>Set ID:&nbsp; RDES<input name=id size=8><input type=submit value='&gt;&gt;'></p>
</form>
<p>Invalid ID '$id'</p>
<p>&nbsp;</p>
<p><a href=index.php>Editor menu</a></p>
";
			exit (1);
		}
		
		print "<form method=POST action=set-edit.php>
<table>
<tr><td align=right>Set</td><td>RDES$id</td></tr>
";
		foreach ($fields as $field => $display_name) {
			$value = htmlentities ($row[$field]);
			print "<tr><td align=right>$display_name</td>
				<td><input name=$field size=70 value=\"$value\"></td></tr>
";
		}

		$key = crypt(date('Ymd'), 'CDE');
		$parent_value = htmlentities ($row['parentID']);

		print "<tr><td align=right>Parent: RDES</td>
				<td><input type=text name=parentID size=8 value='$parent_value'></td></tr>
<tr><td colspan=2 align=center>
<input type=hidden name=key value='$key'>
<input type=hidden name=id value='$id'>
<input type=submit value='Save changes &gt;&gt;'>
</td></tr>
</table>
</form>
";
	}

	// Otherwise, update the record
	else {
		// Make sure the key is valid
		if ($key <> crypt(date('Ymd'), 'CDE')) {
			print "<p>Invalid key!</p>\n";
		}
		else {
			foreach ($fields as $field => $display_name) {
				$value = mysql_real_escape_string ($_REQUEST [$field]);
				$q[] = "$field = '$value'";
			}
			$query = "UPDATE ElementSet SET " . implode(", ", $q)
						. ", parentID = " . (ctype_digit($parentID) ? $parentID : 'NULL')
						. " WHERE id = $id";
			$result = mysql_query ($query)
				or die(mysql_error());
			// print $query;
			print "<p>Set # $id updated:  $name</p>\n";
			
		}
		
	}
	
	print "<p>&nbsp;</p>
<p><a href=set-edit.php>Edit another set</a></p>
<p><a href=index.php>Editor menu</a></p>
";

?>