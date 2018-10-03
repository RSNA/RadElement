<?php
/*
	search.php
	
	RadElement -- Search CDEs by name or ID number_format
	CEK 2016-05-16
	
*/
	require_once ('./config/open_db.php');
	
	extract ($_REQUEST);

	if (isset ($q)) {
		$q = mysql_real_escape_string ($q);
		if (ctype_digit ($q))
			$query = "WHERE id = $q";
		else
			$query = "WHERE name LIKE '%$q%'";
	}
	else {
		$query = '';
	}

	$result = mysql_query ("SELECT id, name, definition FROM Element WHERE $query");
	if (mysql_num_rows ($result) == 1) {
		extract (mysql_fetch_assoc ($result));
		header ("Location: /element/RDE$id");
		exit;
	}
	
	$result = mysql_query (
		"SELECT id, name, shortName, definition 
			FROM Element 
			$query
			ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Radiology CDEs | RadElement.org</title>
<meta name="description" content="Radiology CDE search results | RadElement.org">
<meta name="author" content="">
<?php require_once('config/header.php'); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="js/bootstrap-twipsy.js"></script>
<script src="js/bootstrap-popover.js"></script>
</head>

<body>
<?php
include_once("config/analyticstracking.php");
echo $topbar;
?>

<div class="container">
	<div class="content">
		 
		<div class="span14">
		<h2><?php echo ($q == '' ? "Complete Listing" : "Search:&nbsp; &quot;$q&quot;"); ?></h2>
		<br>
<?php
	if (mysql_num_rows ($result) == "0") {
		echo "<p>No matching data elements.</p>";
	}
	else {
		echo "<ul>";
		while ($row = mysql_fetch_array ($result)) {
			extract($row);
			echo "<li><a href=/element/RDE$id>$name</a>\n";
		}
	}
?>
</ul>
		</div>
	</div>	  
<?php
echo $footer;
?>
</div> <!-- /container -->
<script>
<!--

				
	$(function () {
        $("a[rel=twipsy]").twipsy({
            live: true,
			offset: 10
        })
    })
				
	$(function () {
        $("a[rel=popover]")
            .popover({
                offset: 0,
				placement: 'below',
				html: true
            })
			
            .click(function(e) {
                //e.preventDefault()
            })
			
		$("a[rel=popover2]")
            .popover({
              offset: 0,
			  placement: 'below',
			  html: true
            })
            .click(function(e) {
              //e.preventDefault()
            })
        })
-->			
</script>
</body>
</html>		