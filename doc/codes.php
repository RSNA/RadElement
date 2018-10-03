<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>codes | RadElement API</title>
<meta name="description" content="codes | RadElement.org API documentation" />
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
<h2>codes</h2>
<hr>

<h3>GET /api/v1/codes</h3>
<p>Lists the coding systems used to index the data elements.</p>
<br>

<h4><a target="api" href="http://radelement.org/api/v1/codes">http://radelement.org/api/v1/codes</a></h4>
<pre class="prettyprint">
{
    "count": 3,
    "systems": [
        {
            "system": "LOINC",
            "name": "Logical Observation Identifiers Names and Codes",
            "href": "http://radelement.org/api/v1/codes/LOINC"
        },
        {
            "system": "RADLEX",
            "name": "Radiology Lexicon",
            "href": "http://radelement.org/api/v1/codes/RADLEX"
        },
        {
            "system": "SNOMEDCT",
            "name": "Systematized Nomenclature of Medicine - Clinical Terms",
            "href": "http://radelement.org/api/v1/codes/SNOMEDCT"
        }
    ]
}
</pre>
<hr>

<h3>GET /api/v1/codes/<i>{SYSTEM}</i></h3>
<p>Displays information about the specified coding system.
Lists the codes from that system that index the data elements.
</p>
<p>Parameters:
<dl>
<dt>limit</dt>
<dd>Maximum number of codes to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl>
</p>
<br>

<h4><a target="api" href="http://radelement.org/api/v1/codes/RADLEX">http://radelement.org/api/v1/codes/RADLEX</a></h4>
<pre class="prettyprint">
{
    "system": {
        "abbrev": "RADLEX",
        "name": "Radiology Lexicon",
        "oid": "2.16.840.1.113883.6.256",
        "systemURL": "http://bioportal.bioontology.org/ontologies/RADLEX"
    },
    "query": {
        "limit": 20,
        "offset": 0,
        "count": 20,
        "totalCount": 89,
        "next": "http://radelement.org/api/v1/codes/RADLEX?limit=20&offset=20"
    },
    "codes": [
        {
            "code": "RID10",
            "display": "image quality",
            "href": "http://radelement.org/api/v1/codes/RADLEX/RID10"
        },
        . . . 
    ]
}
</pre>
<hr>

<h3>GET /api/v1/codes/<i>{SYSTEM}</i>/<i>{CODE}</i></h3>
<p>Displays information about the specified code and lists its data elements.
</p>
<p>Parameters:
<dl>
<dt>limit</dt>
<dd>Maximum number of elements to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl>
</p>
<br>

<h4><a target="api" href="http://radelement.org/api/v1/codes/RADLEX/RID28662">http://radelement.org/api/v1/codes/RADLEX/RID28662</a></h4>
<pre class="prettyprint">
{
    "query": {
        "system": "RADLEX",
        "code": "RID28662",
        "display": "Attenuation",
        "codeURL": "http://www.radlex.org/RID/RID28662"
    },
    "elements": {
        "count": 3,
        "href": "http://radelement.org/api/v1/codes/RADLEX/RID28662/elements"
    },
    "values": {
        "count": 0,
        "href": null
    }
}
</pre>

<h3>GET /api/v1/codes/<i>{SYSTEM}</i>/<i>{CODE}</i>/elements</h3>
<p>Displays information about the specified code and lists its data elements.
</p>
<p>Parameters:
<dl>
<dt>limit</dt>
<dd>Maximum number of elements to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl>
</p>
<br>

<h4><a target="api" href="http://radelement.org/api/v1/codes/RADLEX/RID28662/elements">http://radelement.org/api/v1/codes/RADLEX/RID28662/elements</a></h4>
<pre class="prettyprint">
{
    "query": {
        "limit": 20,
        "offset": 0,
        "count": 3,
        "totalCount": 3,
        "next": null
    },
    "elements": [
        {
            "id": "RDE43",
            "name": "Unenhanced attenuation",
            "href": "http://radelement.org/api/v1/elements/RDE43"
        },
        {
            "id": "RDE44",
            "name": "Enhanced attenuation",
            "href": "http://radelement.org/api/v1/elements/RDE44"
        },
        {
            "id": "RDE45",
            "name": "Delayed attenuation",
            "href": "http://radelement.org/api/v1/elements/RDE45"
        }
    ]
}
</pre>

<h3>GET /api/v1/codes/<i>{SYSTEM}</i>/<i>{CODE}</i>/values</h3>
<p>Displays the data-element values indexed by the specified code.
</p>
<p>Parameters:
<dl>
<dt>limit</dt>
<dd>Maximum number of values to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl>
</p>
<br>

<h4><a target="api" href="http://radelement.org/api/v1/codes/SNOMEDCT/24028007/values">http://radelement.org/api/v1/codes/SNOMEDCT/24028007/values</a></h4>
<pre class="prettyprint">
{
    "query": {
        "limit": 20,
        "offset": 0,
        "count": 13,
        "totalCount": 13,
        "next": null
    },
    "values": [
        {
            "id": "RDE28",
            "code": "12",
            "name": "Midbrain - R",
            "href": "http://radelement.org/api/v1/elements/RDE28/values/12"
        },
        . . .
    ]
}
</pre>

<hr>
<p><a href="./">Return to API Overview</a>.</p>
</div></div></div>
</body>
</html>