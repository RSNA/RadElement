<?php
/*
	/editor/element-values.php
*/

	// Connect to database
	require ('../config/open_db.php');
	include ("key.php");

	// Get parameters
	extract ($_REQUEST);
	$elementID = $id;

	print "<pre>\n"; print_r ($_REQUEST); print "</pre>\n";

	// Make sure element ID is ok
	if (! isset ($elementID) || ! ctype_digit($elementID)) {
		print "Invalid ID or key\n";
		exit (1);
	}

	$row = mysql_fetch_assoc (mysql_query (
			"SELECT name FROM Element WHERE id = $elementID LIMIT 1"));
	if (! $row) {
		print "Invalid ID\n";
		exit (1);
	}
	extract ($row);

	// Update value list
	if (isset ($key)) {
		mysql_query ("DELETE FROM ElementValue WHERE elementID = $elementID")
			or die (mysql_error ());
		// print "DELETE FROM ElementValue WHERE elementID = $elementID\n";	//-------------
		foreach ($value_code as $i => $c) {
			if ($c <> '') {
				$c = mysql_real_escape_string ($c);
				$n = mysql_real_escape_string ($value_name [$i]);
				mysql_query ("INSERT INTO ElementValue (elementID, code, name)
							VALUES ($elementID, '$c', '$n')")
								or die (mysql_error());
				// print "INSERT INTO ElementValue (elementID, code, name)
							// VALUES ($elementID, '$c', '$n')\n";
			}
		}
	}

	// Display header info
	print "<html>
<head>
<title>Values - Element RDE$elementID: $name</title>
</head>
<body>
<h2>Values - Element RDE$elementID: $name</h2>
";

	// Display the element's current values
	$n = 0;
	$key = make_key();

	$result = mysql_query (
		"SELECT ElementValue.id AS valueID, ElementValue.code, ElementValue.name,
			GROUP_CONCAT(CONCAT(IndexCode.system,'::',IndexCode.code,' - ',IndexCode.display)
							SEPARATOR '<br>') AS codelist
			FROM ElementValue
			LEFT JOIN (IndexCodeRef, IndexCode)
				ON (IndexCodeRef.elementID = $elementID
					AND IndexCodeRef.valueCode = ElementValue.code
					AND IndexCodeRef.codeID = IndexCode.id)
			WHERE ElementValue.elementID = $elementID
			GROUP BY ElementValue.id");

			print "<form method=POST action=element-values.php>
<input type=hidden name=id value=$elementID>
<input type=hidden name=key value='$key'>
<table cellpadding=6>
<tr>
 <th>#</th>
 <td>Code</td>
 <td>Name</td>
 <td colspan=2>Index codes</td>
</tr>
";

	while ($row = mysql_fetch_assoc ($result)) {
		++$n;
		extract ($row);
		print "<tr valign=top>
 <th>$n</th>
 <td><input name=value_code[$n] value='$code' size=32></td>
 <td><input name=value_name[$n] value='$name' size=64>
 <td><a href=element-value-codes.php?elementID=$elementID&valueCode=$code>[Edit]</a></td>
 <td>$codelist</td>
</tr>
";
	}

	// Display 4 more rows for entry
	$another = 4;
	while ($another--) {
		++$n;
		print "<tr>
 <th>$n</th>
 <td><input name=value_code[$n] value='' size=32></td>
 <td><input name=value_name[$n] value='' size=64></td>
 <td>&nbsp;</td>
</tr>
";
	}
	print "</table>
<input type=submit value='Update values &gt;&gt;'>
</form>
";

	// Print footer
	print "<p>&nbsp;</p>
<p><a href=element-edit.php?id=$elementID>Go back to Edit Element</a></p>
<p><a href=index.php>Editor menu</a></p>
";

	exit;


?>