<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>elements | RadElement API</title>
<meta name="description" content="elements | RadElement.org API documentation" />
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
<h2>elements</h2>
<hr>

<!--
<ul>
<li><a href="#elements">elements</a></li>
<li><a href="#ID">elements/<i>{ID}</i></a></li>
<li><a href="#values">elements/<i>{ID}</i>/values</a></li>
<li><a href="#codes">elements/<i>{ID}</i>/codes</a></li>
<li><a href="#sets">elements/<i>{ID}</i>/sets</a></li>
</ul>
-->

<hr>
<div id="elements">
<h3>GET /api/v1/elements</h3>
<p>Lists all common data elements (CDEs) in the RadElement.org database. Returns number of elements
retrieved (<i>count</i>) and the list of elements (<i>elements</i>).</p>
<p>Parameters:
<dl>
<dt>name</dt>
<dd>Query string used to search the names of data elements</dd>
<dt>limit</dt>
<dd>Maximum number of elements to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl>
<br>

<h4><a target="api" href="http://radelement.org/api/v1/elements">http://radelement.org/api/v1/elements</a></h4>
<pre class="prettyprint">
{
    "query": {
        "limit": 20,
        "offset": 0,
        "count": 20,
        "totalCount": 62,
        "next": "http://radelement.org/api/v1/elements?limit=20&offset=20"
    },
    "elements": [
        {
            "id": "RDE2",
            "name": "Imaging modality",
            "href": "http://radelement.org/api/v1/elements/RDE2"
        },
        . . .
    ]
}
</pre>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/elements?name=atten">http://radelement.org/api/v1/elements?name=atten</a></h4>
<pre class="prettyprint">
{
    "query": {
        "name": "atten",
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
</div>

<hr>

<div id="ID">
<h3>GET /api/v1/elements/<i>{ID}</i></h3>
<p>Displays the detailed definition of the specified common data element (CDE).</p>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/elements/RDE44">http://radelement.org/api/v1/elements/RDE44</a></h4>
<pre class="prettyprint linenums">
{
    "id": "RDE44",
    "name": "Enhanced attenuation",
    "definition": "Mean attenuation in Hounsfield units (HU) of the region of interest on CT images obtained immediately after IV contrast administration.",
    "question": "Enter the attenuation of the region of interest on the enhanced CT.",
    "version": "1",
    "versionDate": "2015-07-05",
    "source": "CAR/DS Adrenal Nodule",
    "status": "Proposed",
    "statusDate": "2016-01-03",
    "values": {
        "type": "integer",
        "min": "-1024",
        "max": "1024",
        "step": "1",
        "unit": "HU",
    },
    "codes": [
        {
        "system": "RADLEX",
        "code": "RID28662",
        "display": "Attenuation",
        "href": "http://radelement.org/api/v1/codes/RADLEX/RID28662"
        }
    ],
    "sets": [
        {
        "id": "3",
        "name": "CAR/DS Adrenal Nodule",
        "href": "http://radelement.org/api/v1/sets/3"
        }
    ]
}
</pre>
<br>
<p>For CDEs that have value sets ("pick lists"), the values are enumerated along with
their indexing codes.</p>
<h4><a target="api" href="http://radelement.org/api/v1/elements/RDE42">http://radelement.org/api/v1/elements/RDE42</a></h4>
<pre class="prettyprint linenums">
{
    "id": "RDE42",
    "name": "Side",
    "definition": "The side of the body (right or left).",
    "version": "1",
    "versionDate": "2015-07-05",
    "source": "CAR/DS Adrenal Nodule",
    "status": "Proposed",
    "statusDate": "2016-01-03",
    "values": {
        "type": "valueSet",
        "count": {
            "min": "1",
            "max": "1"
        },
        "valueSet": [
            {
                "value": "R",
                "name": "Right",
                "definition": null,
                "images": null,
                "codes": [
                    {
                        "system": "RADLEX",
                        "code": "RID5825",
                        "display": "Right",
                        "href": "http://radelement.org/api/v1/codes/RADLEX/RID5825"
                    },
                    {
                        "system": "SNOMEDCT",
                        "code": "24028007",
                        "display": "Right",
                        "href": "http://radelement.org/api/v1/codes/SNOMEDCT/24028007"
                    }
                ]
            },
            {
                "value": "L",
                "name": "Left",
                "definition": null,
                "images": null,
                "codes": [
                    {
                        "system": "RADLEX",
                        "code": "RID5824",
                        "display": "Left",
                        "href": "http://radelement.org/api/v1/codes/RADLEX/RID5824"
                    },
                    {
                        "system": "SNOMEDCT",
                        "code": "7771000",
                        "display": "Left",
                        "href": "http://radelement.org/api/v1/codes/SNOMEDCT/7771000"
                    }
                ]
            }
        ]
    },
    "codes": [ ... ],
    "sets": [ ... ]
}
</pre>
</div>

<hr>
<div id="values">
<h3>GET /api/v1/elements/<i>{ID}</i>/values</h3>
<p>Lists the enumerated values for a common data element (CDE).</p>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/elements/RDE42/values">http://radelement.org/api/v1/elements/RDE42/values</a></h4>
<pre class="prettyprint linenums">
{
    "values": [
        {
            "value": "R",
            "name": "Right",
            "href": "http://radelement.org/api/v1/elements/RDE42/values/R"
        },
        {
            "value": "L",
            "name": "Left",
            "href": "http://radelement.org/api/v1/elements/RDE42/values/L"
        }
    ]
}
</pre>

<h3>GET /api/v1/elements/<i>{ID}</i>/values/<i>{value}</i></h3>
<p>Displays information for a specific enumerated value of a common data element.</p>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/elements/RDE42/values/R">http://radelement.org/api/v1/elements/RDE42/values/R</a></h4>
<pre class="prettyprint linenums">
{
    "name": "Right",
    "definition": null,
    "images": null,
    "codes": [
        {
            "system": "RADLEX",
            "code": "RID5825",
            "display": "Right",
            "href": "http://radelement.org/api/v1/codes/RADLEX/RID5825"
        },
        {
            "system": "SNOMEDCT",
            "code": "24028007",
            "display": "Right",
            "href": "http://radelement.org/api/v1/codes/SNOMEDCT/24028007"
        }
    ]
}
</pre>
</div>

<hr>

<h3>GET /api/v1/elements/<i>{ID}</i>/codes</h3>
<p>Lists the indexing codes for the specified common data element (CDE).</p>
<p>Parameters:
<dl>
<dt>limit</dt>
<dd>Maximum number of codes to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/elements/RDE42/codes">http://radelement.org/api/v1/elements/RDE42/codes</a></h4>
<pre class="prettyprint linenums">
{
    "query": {
        "limit": 20,
        "offset": 0,
        "count": 2,
        "totalCount": 2,
        "next": null
    },
    "codes": [
        {
            "system": "RADLEX",
            "code": "RID5821",
            "display": "laterality",
            "href": "http://radelement.org/api/v1/codes/RADLEX/RID5821"
        },
        {
            "system": "SNOMEDCT",
            "code": "272741003",
            "display": "Laterality",
            "href": "http://radelement.org/api/v1/codes/SNOMEDCT/272741003"
        }
    ]
}
</pre>

<hr>

<div id="sets">
<h3>GET /api/v1/elements/<i>{ID}</i>/sets</h3>
<p>Lists the sets that include the specified common data element (CDE).</p>
<p>Parameters:
<dl>
<dt>limit</dt>
<dd>Maximum number of sets to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/elements/RDE42/sets">http://radelement.org/api/v1/elements/RDE42/sets</a></h4>
<pre class="prettyprint linenums">
{
    "query": {
        "limit": 20,
        "offset": 0,
        "count": 1,
        "totalCount": 1,
        "next": null
    },
    "sets": [
        {
            "id": "3",
            "name": "CAR/DS Adrenal Nodule",
            "href": "http://radelement.org/api/v1/sets/3"
        }
    ]
}
</pre>
</div>

<hr>


<p><a href="./">Return to API Overview</a>.</p>
</div></div></div>
</body>
</html>