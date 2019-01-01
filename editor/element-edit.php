<?php
/*
	/editor/element-edit.php
*/

	// Connect to database
	require ('../config/open_db.php');
	include ("key.php");

		// print "<pre>"; print_r ($_REQUEST); print "</pre>";

	$fields = array (
		'name' => 'text',
		'shortName' => 'text', 
		'definition' => 'textarea', 
		'valueType' => array ('boolean','date','integer','float','string','valueSet','set'), 
		'valueMin' => 'number', 
		'valueMax' => 'number', 
		'stepValue' => 'number', 
		'unit' => 'text', 		
		'question' => 'textarea', 
		'instructions' => 'textarea', 
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
	print "<html>
<head>
<title>Edit Element</title>
</head>
<body>
<h2>Edit Element</h2>
";

	// If no parameters, ask the element ID
	if (! isset ($id)) {
		print "<form method=POST action=element-edit.php>
<p>Element ID:&nbsp; RDE<input name=id size=8><input type=submit value='&gt;&gt;'></p>
</form>
<p>&nbsp;</p>
<p><a href=index.php>Editor menu</a></p>
";
		exit;
	}
	
	// If ID is set, but there's no key, display current values
	else if (! isset ($key)) {
		
		// Make sure ID is numeric and a valid element
		if (! ctype_digit($id)) {
			print "<form method=POST action=element-edit.php>
<p>Element ID:&nbsp; RDE<input name=id size=8><input type=submit value='&gt;&gt;'></p>
</form>
<p>Invalid ID '$id'</p>
<p>&nbsp;</p>
<p><a href=index.php>Editor menu</a></p>
";
			exit;
		}
		$row = mysql_fetch_assoc (mysql_query (
				"SELECT * FROM Element WHERE id = $id LIMIT 1"));
		if (! $row) {
			print "<form method=POST action=element-edit.php>
<p>Element ID:&nbsp; RDE<input name=id size=8><input type=submit value='&gt;&gt;'></p>
</form>
<p>Invalid ID '$id'</p>
<p>&nbsp;</p>
<p><a href=index.php>Editor menu</a></p>
";
			exit;
		}
		
		print "<form method=POST action=element-edit.php>
<table cellspacing=4>
<tr><td align=right>&nbsp;</td><td>RDE$id</td></tr>
";
		foreach ($fields as $field => $field_type) {
			$value = htmlentities ($row[$field]);
			if (is_array($field_type)) {
				print "<tr><td align=right>$field</td><td><select name=$field>\n";
				foreach ($field_type as $v) {
					$selected = ($v == $value ? ' SELECTED' : '');
					print " <option$selected>$v</option>\n";
				}
				print "</select></td></tr>\n";
			}
			else if ($field_type == 'textarea') {
				print "<tr><td align=right valign=top>$field</td>
					<td><textarea name=$field rows=4 cols=70>$value</textarea></td></tr>\n";
			}
			else {
				$size = ($field_type == 'text' ? 70 : 12);
				$type = ($field_type == 'number' ? 'text' : $field_type);
				print "<tr><td align=right>$field</td>
					<td><input name=$field type=$type size=$size value=\"$value\"></td></tr>\n";
			}
		}
		$key = make_key();
		print "
<tr><td colspan=2 align=center>
<input type=hidden name=id value='$id'>
<input type=hidden name=key value='$key'>
<input type=submit value='Save changes &gt;&gt;'>
</td></tr>
</table>
</form>
";
	}

	// Otherwise, update the Element table if the key is valid
	else /*if (is_valid_key($key))*/ {
		foreach ($fields as $field => $field_type) {
			$value = mysql_real_escape_string ($_REQUEST [$field]);
			$q[] = "`$field` = '$value'";
		}
		$query = "UPDATE Element SET " . implode(", ", $q)
					. " WHERE id = $id LIMIT 1";
		// print "<pre>$query</pre>\n";		//----------------
		$result = mysql_query ($query)
			or die(mysql_error());
		print "<p>Element RDE$id updated:  $name</p>
<p><a href=element-edit.php?id=$id>Edit RDE$id again</a></p>
<p><a href=element-edit.php>Edit another element</a></p>
<p><a href=index.php>Editor menu</a></p>
";
		exit;
	}
	
	
	// Display values for valueSet element
	if (strcmp($row['valueType'], 'valueSet') == 0) {
		print "<br><h3><a href=element-values.php?id=$id>Values</a></h3>
<ul>		
";
		$result = mysql_query ("SELECT code, name FROM ElementValue
								WHERE elementID = $id
								ORDER BY id");
		while ($row = mysql_fetch_assoc ($result)) {
			extract ($row);
			print "<li>$code:&nbsp; $name</li>\n";
		}
		print "</ul>\n";
	}

	// Display indexing codes
	print "<br>
<h3><a href=element-codes.php?id=$id>Indexing codes</a></h3>
<ul>
";

	$result = mysql_query ("SELECT system, code, display FROM IndexCode, CodeRef
							WHERE elementID = $id AND valueCode IS NULL
							AND IndexCode.id = codeID
							ORDER BY system, code");
	while ($row = mysql_fetch_assoc ($result)) {
		extract ($row);
		print "<li>$system::$code : $display</li>\n";
	}
	print "</ul>\n";

	// Display the element's sets
	print "<br>
<h3><a href=element-sets.php?id=$id>Sets</a></h3>
<ul>
";
	$result = mysql_query ("SELECT elementSetID, name
							FROM ElementSetRef, ElementSet
							WHERE elementID = $id
							AND ElementSet.id = elementSetID
							ORDER BY name");
	while ($row = mysql_fetch_assoc ($result)) {
		extract ($row);
		print "<li>$name (RDES$elementSetID)</li>\n";
	}

	// Print footer
	print "</ul><p>&nbsp;</p>
<p><a href=element-edit.php>Edit another element</a></p>
<p><a href=index.php>Editor menu</a></p>
";

?>