<?php
/*
    /api/sets/set-elements.php
    Display elements for a specific set
*/

    // Specify JSON output
    header ('Content-type: application/json');

    // Get web service parameters
    //      /sets/3/elements/       Show elements for set 3
    extract ($_GET);

    // Connect to database
    require ('../../../config/open_db.php');

    // Define API base address
    $baseURL = 'http://radelement.org/api/v1';

    // Make sure it's a valid set ID
    $id = mysql_real_escape_string ($_id);
    $result = mysql_query ("SELECT * FROM ElementSet WHERE id = $id")
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
    $numElementSets = mysql_num_rows (mysql_query (
                    "SELECT * FROM ElementSetRef WHERE elementSetID = $id"));

    // Tally sets for the specified element
    $limit = (ctype_digit($limit) ? min(0+$limit,100) : 20);
    $offset = (ctype_digit($offset) ? 0+$offset : 0);

    $result = mysql_query (
            "SELECT CONCAT('RDE',elementID) AS id, Element.name,
                    CONCAT('$baseURL/elements/RDE',elementID) AS url
                FROM ElementSetRef, Element
                WHERE elementSetID = $id
                AND elementID = Element.id
                ORDER BY elementID
                LIMIT $limit OFFSET $offset")
        or die(mysql_error());

    $response ['query']['limit'] = $limit;
    $response ['query']['offset'] = $offset;
    $response ['query']['count'] = mysql_num_rows ($result);

    while ($row = mysql_fetch_assoc ($result)) {
        $elements [] = $row;
    }
    $response ['elements'] = $elements;

    // Tally total number of sets
    $totalCount = mysql_num_rows (mysql_query ("SELECT * FROM ElementSetRef WHERE elementSetID = $id"));
    $response ['query']['totalCount'] = $totalCount;

    // Point to next set of elements
    $next = $offset + $limit;
    $response ['query']['next'] = ($totalCount > $next
                ? "$baseURL/sets/RDES$id/elements?limit=$limit&offset=$next" : null);

    print json_encode($response);

?>