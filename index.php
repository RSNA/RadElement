<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>RadElement.org &ndash; Common Data Elements (CDE) for Radiology</title>
<meta name="description" content="RadElement.org | Common Data Elements (CDE) for Radiology" />
<?php require('config/open_db.php'); ?>
<?php require_once('config/header.php'); ?>
<style>
td.set {
	width: 25%;
	height: 30px;
    border: solid #ccc 1px;
	cell-padding: 14px;
	margin: 10px;
}
a.set {
	cell-line-height: 16px; 
	font-size: 14px; 
	font-weight: bold; 
	text-color: dark-blue;
}
</style>
</head>

<body onload="document.forms[0].q.focus();">
<?php
include_once("config/analyticstracking.php");
echo $topbar;
?>
<div class="container">
	<div class="content">
		<div class="page-header">
		<table>
		<tr><td><img src='/images/RadElement_logo_50.png'></td>
		<td>
        <h2>Common Data Elements (CDEs) for Radiology</h2>
		<p>Standardizing the names and attributes of data elements
		to support clinical and translational research, patient care, 
		and performance improvement in diagnostic and interventional 
		radiology. 
		</p>
		</td></tr>
		</table>
		</div>
<form action="/search.php" method="POST" autocomplete="off">
 <div align="center" style="margin:40px">
  <input type="text" class="span5 omnitype" placeholder="Search CDEs" name="q">
  <button class="btn info" style="background-color:#553388" type="submit" style="margin:0;">Search</button>
</div>
 </form>
<hr>

<table class="set">
<tr>
<?php  
	$result = mysql_query (
		"SELECT ElementSet.id, name, count(*) AS numElements
			FROM ElementSet, ElementSetRef
			WHERE ElementSet.id = elementSetID
			GROUP BY name ORDER BY RAND() LIMIT 3")
		or die(mysql_error());
	
	while ($row = mysql_fetch_assoc ($result)) {
		extract ($row);
		$s = ($numElements > 1 ? 's' : '');
		
		/*
		echo "<p><dt style=\"line-height: 16px; font-size:14px; font-weight:bold; text-color:black;\">
			<a data-original-title=\"$name\" href=\"/set/$id\" >$name</a></dt>
			<dd>$numElements data element$s</dd>
			</p>\n";
		*/
		
		print "<td class=\"set\"><a class=\"set\" data-original-title=\"$name\" href=\"/set/RDES$id\" >$name</a>
<br> &nbsp; $numElements data element$s</td>\n";
	}
	?>    
</tr>
</table>
<br>
<div align=center><a class=set href=/set/all.php>Browse all sets</a></div>

 <?php
echo $footer;
?>
	</div></div>
</body>
</html>
