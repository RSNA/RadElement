<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>API Documentation | RadElement.org</title>
<meta name="description" content="API Documentation for RadElement.org - Radiology Common Data Elements (CDEs)" />
<?php require_once('../config/header.php'); ?>
</head>

<body>
<?php 
include_once("../config/analyticstracking.php");
echo $topbar;
?>
<div class="container">
<div class="content">
     
    <div class="page-header">
       
        <h4>API Documentation</h4>
		<h2>Application Programming Interface</h2>
		<p>&nbsp;</p>

		<p>The application programming interface (API) uses
		Representational State Transfer (REST) 
		and responds with JavaScript Object Notation (JSON).</p>
        <hr>
        <h3><a href="elements.php">elements</a></h3>
		<p>
		Lists and retrieves informaton about the dictionary's common data elements. 
		</p>
		<br>
        <h3><a href="codes.php">codes</a></h3>
		<p>
		Lists coding schemes (e.g., RadLex, SNOMED CT) and their codes used to index this
		repository of common data elements.
		</p>
		<br>
        <h3><a href="sets.php">sets</a></h3>
		<p>
		Lists and retrieves informaton about sets of common data elements. 
		</p>
    </div> 
  <?php
echo $footer;
?>
</div>
<!-- /container -->
</body>
</html>
