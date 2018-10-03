<?php
/*
	set/all.php
	
	RadElement -- List all sets
	CEK 2016-06-22
*/

	include ('../config/open_db.php');
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php require_once('../config/header.php'); ?>
<title>Sets | RadElement.org</title>
<meta name="description" content="Sets | RadElement.org" />
</head>

<body>
<?php 
include_once("../config/analyticstracking.php");
echo $topbar;
?>
<div class="container">
	<div class="content">
<h2>Sets</h2>
<hr>
<ul style="font-size: 14pt; line-height: 150%;">
<?php
	$result = mysql_query (
		"SELECT ElementSet.*, count(*) AS numElements
			FROM ElementSet, ElementSetRef
			WHERE ElementSet.id = elementSetID
			GROUP BY ElementSet.id
			ORDER BY ElementSet.name") 
		or die(mysql_error());
		
	if (mysql_num_rows ($result) == 0) {
		header ("Location: /");
		exit;
	}

	while ($row = mysql_fetch_assoc ($result)) {
		extract ($row);
		$s = ($numElements > 1 ? 's' : '');
		print "<li style=\"font-size: 14pt; line-height: 130%;\"><a href=\"/set/RDES$id\">$name</a> &nbsp; <span style=\"font-size:10pt;\">($numElements element$s)</span></li>\n";
	}
?>
</ul>
<?php echo $footer; ?>
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
