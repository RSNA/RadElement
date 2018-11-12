<?php
/*
    /api/elements/element-sets.php
    Display sets for a specific element
*/

    // Specify JSON output
    header ('Content-type: application/json');

    // Get web service parameters
    //      /elements/3/sets        Show sets for element 3
    extract ($_GET);

    // Connect to database
    require ('../../../config/open_db.php');

    // Define API base address
    $baseURL = 'http://radelement.org/api/v1';

    // Make sure it's a valid element ID
    $id = mysql_real_escape_string ($_id);
    $result = mysql_query ("SELECT * FROM Element WHERE id = $id")
            or die(mysql_error());
    if (mysql_num_rows ($result) == 0) {
        $response = array (
            'id' => null,
            'msg' => "ERROR - no such ID number"
            );
        print (json_encode ($response));
        exit;
    }

    // Provide information about sets
    $numSets = mysql_num_rows (mysql_query (
                    "SELECT * FROM ElementSetRef WHERE elementID = $id"));

    // Tally sets for the specified element
    $limit = (ctype_digit($limit) ? min(0+$limit,100) : 20);
    $offset = (ctype_digit($offset) ? 0+$offset : 0);

    $result = mysql_query (
            "SELECT elementSetID AS id, ElementSet.name,
                        CONCAT('$baseURL','/sets/',elementSetID) AS url
                FROM ElementSetRef, ElementSet
                WHERE elementID = $id
                AND elementSetID = ElementSet.id
                ORDER BY elementSetID
                LIMIT $limit OFFSET $offset")
        or die(mysql_error());

    $response ['query']['limit'] = $limit;
    $response ['query']['offset'] = $offset;
    $response ['query']['count'] = mysql_num_rows ($result);

    while ($row = mysql_fetch_assoc ($result)) {
        $sets [] = $row;
    }
    $response ['sets'] = $sets;

    // Tally total number of sets
    $totalCount = mysql_num_rows (mysql_query ("SELECT * FROM ElementSetRef WHERE elementID = $id"));
    $response ['query']['totalCount'] = $totalCount;

    // Point to next set of elements
    $next = $offset + $limit;
    $response ['query']['next'] = ($totalCount > $next
                ? "$baseURL/elements/$id/sets?limit=$limit&offset=$next" : null);

    print json_encode($response);

?>