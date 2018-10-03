<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>sets | RadElement API</title>
<meta name="description" content="sets | RadElement.org API documentation" />
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
<h2>sets</h2>
<hr>

<h3>GET /api/v1/sets</h3>
<p>Lists all sets.
Returns the number of sets retrieved (<i>count</i>) and the list of sets (<i>sets</i>).</p>
<p>Parameters:
<dl>
<dt>top</dt>
<dd>Restrict the search to <b>top-level</b> sets, that is, those that have no parent set.</dd>
<dt>name</dt>
<dd>Search the set name by the specified text string.</dd>
<dt>limit</dt>
<dd>Maximum number of sets to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl></p>

<br>
<h4><a target="api" href="http://radelement.org/api/v1/sets">http://radelement.org/api/v1/sets</a></h4>
<pre class="prettyprint">
{
    "query": {
        "limit": 20,
        "offset": 0,
        "count": 4,
        "totalCount": 4,
        "next": null
    },
    "sets": [
        {
            "id": "RDES1",
            "name": "Traumatic Brain Injury",
            "url": "",
            "email": "",
            "href": "http://radelement.org/api/v1/sets/RDES1"
        },
        . . .
    ]
}
</pre>

<br>
<h4><a target="api" href="http://radelement.org/api/v1/sets?top">http://radelement.org/api/v1/sets?top</a></h4>
<pre class="prettyprint">
{
    "query": {
        "top": true,
        "limit": 20,
        "offset": 0,
        "count": 3,
        "totalCount": 3,
        "next": null
    },
    "sets": [
        {
            "id": "RDES1",
            "name": "Traumatic Brain Injury",
            "url": "http://www.radreport.org/template/284",
            "email": "charles.kahn@uphs.upenn.edu",
            "href": "http://radelement.org/api/v1/sets/RDES1"
        },
        . . .
    ]
}
</pre>
<hr>

<h3>GET /api/v1/sets/<i>{setID}</i></h3>
<p>Returns information about the specified set and its associated data elements.</p>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/sets/RDES3">http://radelement.org/api/v1/sets/RDES3</a></h4>
<pre class="prettyprint">
{
    "set": {
        "id": "RDES3",
        "name": "CAR/DS Adrenal Nodule",
        "description": "",
        "url": "",
        "contactName": "",
        "email": "",
        "parent": {
            "id": "RDES2",
            "name": "Computer-Assisted Reporting / Decision Support (CAR/DS)",
            "href": "http://radelement.org/api/v1/sets/RDES2"
        },
        "children": null
    },
    "elements": {
        "count": 9,
        "href": "http://radelement.org/api/v1/sets/RDES3/elements"
    }
}
</pre>

<hr>

<h3>GET /api/v1/sets/<i>{setID}</i>/elements</h3>
<p>Lists the data elements for the specified set.</p>
<p>Parameters:
<dl>
<dt>limit</dt>
<dd>Maximum number of sets to retrieve (default = 20); up to 100.</dd>
<dt>offset</dt>
<dd>Position in listing (default = 0).</dd>
</dl></p>
<br>
<h4><a target="api" href="http://radelement.org/api/v1/sets/RDES3/elements?limit=2">http://radelement.org/api/v1/sets/RDES3/elements?limit=2</a></h4>
<pre class="prettyprint">
{
    "query": {
        "limit": 2,
        "offset": 0,
        "count": 2,
        "totalCount": 9,
        "next": "http://radelement.org/api/v1/sets/RDES3/elements?limit=2&offset=2"
    },
    "elements": [
        {
            "id": "RDE11",
            "name": "Nodule size",
            "href": "http://radelement.org/api/v1/elements/RDE11"
        },
        {
            "id": "RDE12",
            "name": "Side",
            "href": "http://radelement.org/api/v1/elements/RDE12"
        }
    ]
}
</pre>

<hr>
<p><a href="./">Return to API Overview</a>.</p>
</div></div></div>
</body>
</html>