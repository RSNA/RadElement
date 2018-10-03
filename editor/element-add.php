<?php
/*
	/editor/element-add.php
*/

	// Connect to database
	require ('../config/open_db.php');
	
	$fields = array (
		'name' => 'text',
		'shortName' => 'text', 
		'definition' => 'text', 
		'valueType' => array ('boolean','date','integer','float','string','valueSet','set'), 
		'valueMin' => 'number', 
		'valueMax' => 'number', 
		'stepValue' => 'number', 
		'unit' => 'text', 		
		'question' => 'text', 
		'instructions' => 'text', 
		'references' => 'text', 
		'version' => 'text', 
		'versionDate' => 'date', 
		'synonyms' => 'text', 
		'source' => 'text', 
		'status' => array ('Draft','Proposed','Trial Use','Active','Retired'), 
		'statusDate' => 'date'
		);
	
	extract ($_REQUEST);
	
	// Display header info
	print "
<html>
<head>
<title>Add Element</title>
</head>
<body>
<h2>Add Element</h2>
";

	// If no parameters, display input form
	if (! isset ($key)) {
		print "<p>Please check existing data elements before adding a new one.</p>\n";
		print "<form method=POST action=element-add.php>\n<table>\n";
		foreach ($fields as $field => $field_type) {
			if (is_array($field_type)) {
				print "<tr><td align=right>$field</td><td><select name=$field>\n";
				foreach ($field_type as $v)
					print " <option>$v</option>\n";
				print "</select></td></tr>\n";
			}
			else {
				$size = ($field_type == 'text' ? ' size=70' : '');
				print "<tr><td align=right>$field</td>
					<td><input name=$field type=$field_type$size></td></tr>\n";
			}
		}
		$key = crypt(date('Ymd'), 'CDE');
		print "
<tr><td colspan=2 align=center>
<input type=hidden name=key value='$key'>
<input type=submit value='Add element &gt;&gt;'>
</td></tr>
</table>
</form>
";
	}

	// Otherwise, insert the data
	else {
		// Make sure the key is valid
		if ($key <> crypt(date('Ymd'), 'CDE')) {
			print "<p>Invalid key!  No element added</p>\n";
		}
		else {
			foreach ($fields as $field => $field_type) {
				$value = mysql_real_escape_string ($_REQUEST [$field]);
				if ($value == '') continue;
				// $quote = ($field_type == 'number' ? "" : "'");
				// $q[] = "$field = $quote$value$quote";
				$q[] = "$field = '$value'";
			}
			$query = "INSERT INTO Element SET " . implode(", ", $q);
			print "<pre>$query</pre>\n";		//----------------
			$result = mysql_query ($query)
				or die(mysql_error());
			$id = mysql_insert_id ();	
			print "<p>Added element RDE$id:  $name</p>\n";
			print "<p><a href=element-edit.php?id=$id>Edit RDE$id</a></p>\n";
		}
	}
	
	print "<p>&nbsp;</p>
<p><a href=element-add.php>Start adding another element</a></p>
<p><a href=index.php>Editor menu</a></p>
";
		

?>