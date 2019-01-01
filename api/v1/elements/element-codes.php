<?php
/*
    /api/elements/element-codes.php
    Display indexing codes for a specific element
*/

    // Specify JSON output
    header ('Content-type: application/json');

    // Get web service parameters
    //      /elements/RDE3/codes        Show codes for element 3
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

    // Provide information about codes
    $numCodes = mysql_num_rows (mysql_query (
                    "SELECT * FROM IndexCodeElementRef WHERE elementID = $id"));

    // Tally codes for the specified element
    $limit = (ctype_digit($limit) ? min(0+$limit,100) : 20);
    $offset = (ctype_digit($offset) ? 0+$offset : 0);

    $result = mysql_query (
            "SELECT DISTINCT system, code, display,
                        CONCAT('$baseURL','/codes/',system,'/',code) AS url
                FROM IndexCodeElementRef, IndexCode
                WHERE elementID = $id AND valueCode IS NULL
                AND IndexCodeElementRef.codeID = IndexCode.id
                ORDER BY system, code
                LIMIT $limit OFFSET $offset")
        or die(mysql_error());

    $response ['query']['limit'] = $limit;
    $response ['query']['offset'] = $offset;
    $response ['query']['count'] = mysql_num_rows ($result);

    while ($row = mysql_fetch_assoc ($result)) {
        $codes [] = $row;
    }
    $response ['codes'] = $codes;

    // Tally total number of codes
    $totalCount = mysql_num_rows (mysql_query ("SELECT * FROM IndexCodeElementRef
                    WHERE elementID = $id AND valueCode IS NULL"));
    $response ['query']['totalCount'] = $totalCount;

    // Point to next set of elements
    $next = $offset + $limit;
    $response ['query']['next'] = ($totalCount > $next
                ? "$baseURL/elements/RDE$id/codes?limit=$limit&offset=$next" : null);

    print json_encode($response);

?>