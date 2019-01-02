<?php
/*
	set/set.php

	RadElement -- Display set information
	CEK 2016-05-15
*/

	include ('../config/open_db.php');

	// Get element ID
	extract ($_GET);
	if (! isset ($id) || ! ctype_digit ($id)) {
		header ("Location: /");
		exit;
	}

	$elementSetID = $id;
	$result = mysql_query ("SELECT ElementSet.*, count(*) AS numElements
							FROM ElementSet
							LEFT JOIN ElementSetRef ON (ElementSet.id = elementSetID)
							WHERE ElementSet.id = $elementSetID
							GROUP BY ElementSet.id")
		or die(mysql_error());

	if (mysql_num_rows ($result) == 0) {
		header ("Location: /");
		exit;
	}


	extract ($row = mysql_fetch_assoc ($result));

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php require_once('../config/header.php'); ?>
<title><?php echo $name;?> | RadElement.org</title>
<meta name="description" content="<?php echo $name;?> | RadElement.org" />
</head>

<body>
<?php
include_once("../config/analyticstracking.php");
echo $topbar;
?>
<div class="container">
	<div class="content">

<?php
	print "
<h5>Set RDES$elementSetID</h5>
<h2>$name</h2>
<hr>
<table>\n";

	// Display set attributes
	print_info ('Description', $description);
	print_info ('URL', ($url <> ''
		? "<a target=\"set\" href=\"$url\">$url</a>&nbsp;&nbsp;<img src=\"/images/linkout.png\">"
		: ''));
	print_info ('Contact name', $contactName);
	print_info ('Email', ($email <> '' ? "<a href=\"mailto:$email\">$email</a>&nbsp;<img src=\"/images/email.png\">" : ''));

	// List parent sets
	if ($parentID <> '') {
		extract (mysql_fetch_assoc (mysql_query (
			"SELECT name FROM ElementSet WHERE id = $parentID")));
		print_info ("Parent set", "<a href=\"/set/RDES$parentID\">$name</a>");
	}

	// List children sets
	$result = mysql_query ("SELECT ElementSet.id, name, count(*) AS numElements
								FROM ElementSet
								LEFT JOIN ElementSetRef ON (ElementSet.id = elementSetID)
								WHERE parentID = $elementSetID
								GROUP BY ElementSet.id");
	if (mysql_num_rows ($result) > 0) {
		print "<tr><td>More specific set(s):</td><td>\n";
		while ($row = mysql_fetch_assoc ($result)) {
			extract ($row);
			print "<a href=\"/set/RDES$id\">$name</a> ($numElements elements)<br>\n";
		}
		print "</ul></td></tr>\n";
	}

	// Display data elements
	$result = mysql_query ("SELECT elementID, name
								FROM ElementSetRef, Element
								WHERE elementSetID = $elementSetID
								AND elementID = Element.id
								ORDER BY ElementSetRef.id");
	if (mysql_num_rows ($result) > 0) {
		print "<tr><td>Data elements:</td><td><ul>\n";
		while ($row = mysql_fetch_assoc ($result)) {
			extract ($row);
			print "<li><a href=\"/element/RDE$elementID\">$name</a></li>\n";
		}
		print "</ul></td></tr>\n";
	}
	// Display indexing codes
    $result = mysql_query (
                "SELECT system, code, display, codeURL
                 FROM IndexCodeElementSetRef, IndexCode, IndexCodeSystem
                 WHERE IndexCodeElementSetRef.elementSetID = $elementSetID
                 AND IndexCodeElementSetRef.codeID = IndexCode.id
                 AND IndexCode.system = IndexCodeSystem.abbrev
                 ORDER BY system, code") or die(mysql_error());
    if (mysql_num_rows ($result) > 0) {
        print "<tr><td>Index codes:</td><td>\n";
        while ($row = mysql_fetch_assoc ($result)) {
            extract ($row);
            $href = preg_replace ('/\$code/', $code, $codeURL);
            print "<a target=\"code\" href=\"$href\">$display &ndash; $system::$code</a>&nbsp;&nbsp;<img src=\"/images/linkout.png\"><br>\n";
        }
        print "</td></tr>\n";
    }

	print "</table>\n";


?>

<?php  echo $footer; ?>
	</div></div>
</body>
</html>

<?php
	function print_info ($attribute, $value) {
		if ($value <> '') {
			print " <tr><td align=right>$attribute:</td><td>$value</td></tr>\n";
		}
	}
?>
