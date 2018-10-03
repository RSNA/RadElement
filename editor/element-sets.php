<?php
/*
	/editor/element-sets.php
*/

	// Connect to database
	require ('../config/open_db.php');
	include ("key.php");

	// Get parameters
	extract ($_REQUEST);
	
	// print "<pre>\n"; print_r ($_REQUEST); print "</pre>\n";
	
	// Make sure ID is ok
	if (! isset ($id) || ! ctype_digit($id)) {
		print "Invalid ID or key\n";
		exit (1);
		}		

	$elementID = $id;
	$row = mysql_fetch_assoc (mysql_query (
			"SELECT name FROM Element WHERE id = $elementID LIMIT 1"));
	if (! $row) {
		print "Invalid ID or key\n";
		exit (1);
	}		

	// Display header info
	extract ($row);
	$key = make_key();
	print "<html>
<head>
<title>Sets - Element RDE$elementID: $name</title>
</head>
<body>
<h2>Sets - Element RDE$elementID: $name</h2>
";

	// If there are parameters, apply them
	if (isset($new1)) {
		mysql_query ("DELETE FROM ElementSetRef WHERE elementID = $elementID");
		foreach ($p as $elementSetID => $on)
			mysql_query ("INSERT INTO ElementSetRef
							SET elementID = $elementID, elementSetID = $elementSetID");
		if ($new1 <> '')
			mysql_query ("INSERT INTO ElementSetRef
							SET elementID = $elementID, elementSetID = $new1");
		if ($new2 <> '')
			mysql_query ("INSERT INTO ElementSetRef
							SET elementID = $elementID, elementSetID = $new2");
	}
			
	// Display the element's sets
	print "
<form method=POST action element-sets.php>
<input type=hidden name=id value=$elementID>
<input type=hidden name=key value=\"$key\">
";

	$result = mysql_query ("SELECT elementSetID, name AS elementSetName
						FROM ElementSetRef, ElementSet
						WHERE elementID = $elementID
						AND ElementSet.id = elementSetID
						ORDER BY name");
	print "<table cellpadding=4>\n";
	while ($row = mysql_fetch_assoc ($result)) {
		extract ($row);
		print "<tr>
 <td><input name=p[$elementSetID] type=checkbox CHECKED></td>
 <td>$elementSetName (RDES$elementSetID)</td>
</tr>
";
	}
		
	for ($i=1; $i<=2; ++$i) {
		print "<tr>
 <td>&nbsp;</td>
 <td><select name=new$i><option value=''>- set -</option>\n";
		$result = mysql_query ("SELECT id AS elementSetID, name AS elementSetName
								FROM ElementSet ORDER BY name");
		while ($row = mysql_fetch_assoc ($result)) {
			extract ($row);
			print "<option value=$elementSetID>$elementSetName &nbsp; (RDES$elementSetID)</option>\n";
		}
		print " </select></td>
<tr>
";
	}
	print "</table>
<p>&nbsp;</p>
<input type=submit value='Update sets &gt;&gt;'>
</form><p>&nbsp;</p>\n";


	// Print footer
	print "</form><p>&nbsp;</p>
<p><a href=element-edit.php?id=$elementID>Go back to Edit Element</a></p>
<p><a href=index.php>Editor menu</a></p>
";

?>