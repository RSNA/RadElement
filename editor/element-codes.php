<?php
/*
	/editor/element-codes.php
*/

	// Connect to database
	require ('../config/open_db.php');
	include ("key.php");

	// Get parameters
	extract ($_REQUEST);
	$elementID = $id;
	$key = make_key();
	$codeURL = array (
		'RADLEX' => 'http://radlex.org/RID/',
		'LOINC' => 'http://bioportal.bioontology.org/ontologies/LOINC?p=classes&conceptid=',
		'SNOMEDCT' => 'http://bioportal.bioontology.org/ontologies/SNOMEDCT?p=classes&conceptid='
		);


	// print "<pre>\n"; print_r ($_REQUEST); print "</pre>\n";

	// Make sure element ID is ok
	if (! isset ($elementID) || ! ctype_digit($elementID)) {
		print "Invalid ID or key\n";
		exit (1);
	}

	$row = mysql_fetch_assoc (mysql_query (
			"SELECT name FROM Element WHERE id = $elementID LIMIT 1"));
	if (! $row) {
		print "Invalid ID or key\n";
		exit (1);
	}
	extract ($row);

	// Action: DELETE
	if (strcmp ($action, "delete") == 0) {
		mysql_query ("DELETE FROM CodeRef WHERE id = $codeRefID LIMIT 1");
		header ("Location: element-codes.php?id=$elementID");
		exit;
	}

	// Action: ADD
	if (strcmp ($action, "add") == 0) {
		foreach ($new as $n => $on) {
			list ($system, $code, $display) = explode ("|", $new_info[$n]);
			$result = mysql_query ("SELECT id AS codeID FROM IndexCode
						WHERE system = '$system' AND code = '$code' LIMIT 1");
			if (mysql_num_rows ($result) > 0) {
				extract (mysql_fetch_assoc ($result));
			}
			else {
				mysql_query ("INSERT INTO IndexCode (system, code, display)
								VALUES ('$system', '$code', '$display')");
				$codeID = mysql_insert_id ();
			}
			mysql_query ("INSERT INTO CodeRef (codeID, elementID, valueCode)
							VALUES ($codeID, $elementID, NULL)");
		}
		header ("Location: element-codes.php?id=$elementID&search=$search");
		exit;
	}

	// Display header info
	print "<html>
<head>
<title>Codes - Element RDE$elementID: $name</title>
</head>
<body>
<h2>Codes - Element RDE$elementID: $name</h2>
";

	// Display the element's current codes
	$result = mysql_query ("SELECT CodeRef.id AS codeRefID, system, code, display
						FROM CodeRef, IndexCode
						WHERE elementID = $elementID
						AND valueCode IS NULL
						AND IndexCode.id = codeID
						ORDER BY display");
	print "<ul>\n";
	while ($row = mysql_fetch_assoc ($result)) {
		extract ($row);
		$indexcode["$system:$code"] = 1;
		$url = $codeURL[$system] . $code;
		print "<li>$display (<a target=code href=$url>$system::$code</a>) &nbsp;
 <a href=element-codes.php?id=$elementID&codeRefID=$codeRefID&action=delete&key=$key>[delete]</a></li>
";
	}
	print "</ul>\n";

	// Search for indexing codes
	if (! isset ($search))  $search = $name;
	$xsearch = htmlentities ($search);
	$terms = annotate ($search);

	print "<p>&nbsp;</p>

<form method=POST action=element-codes.php>
<input type=hidden name=action value=search>
<input type=hidden name=id value=$elementID>
<input type=hidden name=key value=\"$key\">
<p>Search term:
<input name=search value='$xsearch'>
<input type=submit value='&gt;&gt;'>
</p>
</form>

<form method=POST action=element-codes.php>
<input type=hidden name=action value=add>
<input type=hidden name=id value=$elementID>
<input type=hidden name=key value=\"$key\">
<input type=hidden name=search value='$xsearch'>
";

	foreach ($terms as $n => $code_info) {
		list ($system, $code, $display) = explode ('|', $code_info);
		if (isset($indexcode["$system:$code"]))
			continue;
		$url = $codeURL[$system] . $code;
		print "<p><input type=checkbox name=new[$n]>
 <input type=hidden name=new_info[$n] value='$code_info'>
 $display (<a target=code href=$url>$system::$code</a>)</p>\n";
	}
	if (count ($terms))
		print "<input type=submit value='Add codes &gt;&gt;'>\n";
	print "</form>\n";


	// Print footer
	print "<p>&nbsp;</p>
<p><a href=element-edit.php?id=$elementID>Go back to Edit Element</a></p>
<p><a href=index.php>Editor menu</a></p>
";

	exit;


function annotate ($text) {

	$url = "http://data.bioontology.org/annotator?apikey=24e3df74-54e0-11e0-9d7b-005056aa3316"
			. "&ontologies=RADLEX,LOINC,SNOMEDCT&longest_only=true&text="
			. urlencode($text);

	$lines = json_decode (file_get_contents ($url), true);

	$termlist = array ();
	foreach ($lines as $line) {
		$annot = $line ['annotatedClass'];
		$code = getcode ($annot ['@id']);
		$onto = getcode ($annot ['links']['ontology']);
		$term = strtolower ($line ['annotations'][0]['text']);
		$termlist [] = "$onto|$code|$term";
	}

	return ($termlist);
}

function getcode ($text) {
	$y = explode ("#", $text);
	if (strlen ($y[1]) > 0)
		return ($y[1]);

	$x = explode ("/", $text);
	return (array_pop($x));
}

?>