<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>About | RadElement.org</title>
<meta name="description" content="About RadElement.org - Radiology Common Data Elements (CDEs)" />
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
       
        <h2>About RadElement.org</h2>
        <hr>
        <h3>Common Data Elements</h3>
		<p>
		Common data elements (CDEs) define the attributes and allowable values of a unit of information,
		so that information can be collected and stored uniformly across institutions and studies.
		</p>
		<p>
		CDEs are defined in a data dictionary, which specifies attributes such as:
		<ul>
		<li>the item's name, </li>
		<li>the way the item is collected,</li>
		<li>valid values and coding, and </li>
		<li>data type (e.g., number or text).</li>
		</ul>
		</p>
		<p>
		CDEs make biomedical data interoperable for a variety of applications,
		including:
		<ul>
		<li>clinical radiology reports,</li> 
		<li>computer-assisted reporting systems, </li>
		<li>structured image annotations, </li>
		<li>case report forms for clinical research, and </li>
		<li>radiology case collections ("teaching files"). </li>
		</ul>
		</p>
		<h3>RadElement.org</h3>
        <p>The <b>RadElement.org</b> site offers a catalog of radiology CDEs,
		indexed by title and controlled terms such as SNOMED CT, LOINC,
		and RadLex. CDEs can be grouped into "sets"
		that list the CDEs used in particular applications. 
		CDEs can be reused, and hence may belong to more than one set.</p>
		</p>The site's <b>web service</b> (using REST and JSON) makes it easy 
		for automated systems to discover and apply radiology CDEs.
		Documentation is available at <a href="http://www.radelement.org/doc/">http://www.radelement.org/doc/</a>.
		</p>
		<h3>Governance</h3>
		<p>This initiative is a collaboration of:
		<ul>
		<li>Radiological Society of North America (RSNA)</li>
		<li>American College of Radiology (ACR)</li>
		</ul>
		</p>
		<h3>Publications</h3>
		<p><ul>
		<li>Common data elements in radiology. <a target=pub href=http://dx.doi.org/10.1148/radiol.2016161553>Radiology 2017; 283:837–844</a>.</li>
		</ul></p>

    </div>
    <hr>
    </div> 
<?php echo $footer; ?>
</div>
<!-- /container -->
</body>
</html>
