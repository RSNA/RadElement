<?php
/*
    /api/elements/elements.php
*/

    // Specify JSON output
    header ('Content-type: application/json');

    // Get web service parameters
    //      /elements       List all elements
    //      /elements/RDE3      Show element 3
    //      /elements?name={x}

    // Parameters
    //      limit
    //      offset
    //      query

    extract ($_GET);

    // Connect to database
    require ('../../../config/open_db.php');

    // Define API base address
    $baseURL = 'http://radelement.org/api/v1';

    // Tally all elements
    $num = mysql_num_rows (mysql_query ("SELECT * FROM Element"));

    // If an element has been specified, show its details
    if ($_id <> '') {
        $id = mysql_real_escape_string ($_id);

        $result = mysql_query ("SELECT * FROM Element WHERE id = $id LIMIT 1")
            or die(mysql_error());

        if (mysql_num_rows ($result) == 0) {
            $response = array (
                'id' => null,
                'msg' => "ERROR - no such ID number"
                );
            print (json_encode ($response));
            exit;
        }
        $row = mysql_fetch_assoc ($result);
        extract ($row);

        $column = array (
            "id" => 1,
            "name" => 1,
            "shortName" => 1,
            "definition" => 1,
            "valueType" => 0,
            "valueSet" => 0,
            "valueMin" => 0,
            "valueMax" => 0,
            "stepValue" => 0,
            // "minCardinality" => 0,
            // "maxCardinality" => 0,
            "unit" => 0,
            "question" => 1,
            "instructions" => 1,
            "codes" => 1,
            "indexingCodes" => 1,
            "references" =>1,
            "version" => 1,
            "versionDate" => 1,
            "synonyms" => 1,
            "source" => 1,
            "status" => 1,
            "statusDate" => 1,
            "images" => 1
            );

        $valueData ['type'] = $valueType;

        foreach ($row as $key => $v) {
            if ($v != '' && $column[$key] == 1) {
                $response [$key] = $v;
            }
        }


        $response['id'] = "RDE$id";
        $response['name'] = $name;

        if (strcmp ($valueType, "valueSet") == 0) {
            if (isset($valueMin))
                $valueData['count']['min'] = $valueMin;
            if (isset($valueMax))
                $valueData['count']['max'] = $valueMax;
            $valueSet = array ();
            $valueResult = mysql_query (
                "SELECT code AS value, name, definition, images
                    FROM ElementValue
                    WHERE elementID = $id
                    ORDER BY id");
            while ($valueRow = mysql_fetch_assoc ($valueResult)) {
                $valueCode = $valueRow['value'];

                // List images
                $images = null;
                $image_result = mysql_query (
                    "SELECT sourceURL, caption
                        FROM ImageRef, Image
                        WHERE elementID = $id AND elementValue = '$valueCode'
                        AND ImageRef.imageID = Image.id
                        ORDER BY ImageRef.id");
                while ($image_row = mysql_fetch_assoc ($image_result)) {
                    $images [] = $image_row;
                }
                $valueRow ['images'] = $images;

                // For each value, tally and point to indexing codes
                $codes = null;
                $code_result = mysql_query (
                    "SELECT system, code, display,
                        CONCAT('$baseURL/codes/', system, '/', code) AS url
                        FROM IndexCodeElementRef, IndexCode
                        WHERE elementID = $id AND valueCode = '$valueCode'
                        AND IndexCodeElementRef.codeID = IndexCode.id
                        GROUP BY system, code");
                while ($code_row = mysql_fetch_assoc ($code_result)) {
                    $codes [] = $code_row;
                }
                $valueRow ['codes'] = $codes;

                $valueSet [] = $valueRow;
            }
            $valueData ['valueSet'] = $valueSet;
        }
        else if (strcmp ($valueType, "integer") == 0
                    || strcmp ($valueType, "float") == 0
                    || strcmp ($valueType, "date") == 0) {
            if ($valueMin <> '')
                $valueData['min'] = $valueMin;
            if (isset($valueMax))
                $valueData['max'] = $valueMax;
            if (isset($stepValue))
                $valueData['step'] = $stepValue;
            if ($unit <> '')
                $valueData['unit'] = $unit;
        }

        $response['values'] = $valueData;


        // List images
        $images = null;
        $result = mysql_query (
            "SELECT sourceURL, caption
                FROM ImageRef, Image
                WHERE elementID = $id AND elementValue IS NULL
                AND ImageRef.imageID = Image.id
                ORDER BY ImageRef.id")
                    or die(mysql_error());

        while ($row = mysql_fetch_assoc ($result)) {
            $images [] = $row;
        }
        $response ['images'] = $images;


        // List indexing codes
        $codes = null;
        $result = mysql_query (
            "SELECT DISTINCT system, code, display,
                        CONCAT('$baseURL','/codes/',system,'/',code) AS url
                FROM IndexCodeElementRef, IndexCode
                WHERE elementID = $id AND valueCode IS NULL
                AND IndexCodeElementRef.codeID = IndexCode.id
                ORDER BY system, code")
                    or die(mysql_error());

        while ($row = mysql_fetch_assoc ($result)) {
            $codes [] = $row;
        }
        $response ['codes'] = $codes;

        // List sets
        $result = mysql_query (
            "SELECT CONCAT('RDES',elementSetID) AS id, ElementSet.name,
                        CONCAT('$baseURL','/sets/RDES',elementSetID) AS url
                FROM ElementSetRef, ElementSet
                WHERE elementID = $id
                AND elementSetID = ElementSet.id
                ORDER BY elementSetID")
                    or die(mysql_error());

        while ($row = mysql_fetch_assoc ($result)) {
            $sets [] = $row;
        }
        $response ['sets'] = $sets;
    }

    // Else (no element or query specified), list them all
    else {

        if (isset($name)) {
            $response ['query']['name'] = $name;
            $xname = mysql_real_escape_string ($name);
            $query_string .= "name LIKE '%$xname%'";
            $queryURL = "&name=" . urlencode($name);
        }
        else {
            $xquery = '';
            $query_string = '1';
            $queryURL = '';
        }

        $limit = (ctype_digit($limit) ? min(0+$limit,100) : 20);
        $offset = (ctype_digit($offset) ? 0+$offset : 0);

        $result = mysql_query (
                "SELECT id, name, CONCAT('$baseURL/elements/RDE',id) AS url
                    FROM Element
                    WHERE $query_string
                    ORDER BY id
                    LIMIT $limit OFFSET $offset")
            or die(mysql_error());

        $response ['query']['limit'] = $limit;
        $response ['query']['offset'] = $offset;
        $response ['query']['count'] = mysql_num_rows ($result);

        while ($row = mysql_fetch_assoc ($result)) {
            $row['id'] = "RDE" . $row['id'];
            $element [] = $row;
        }
        $response ['elements'] = $element;

        // Tally total number of elements
        $totalCount = mysql_num_rows (mysql_query ("SELECT * FROM Element WHERE $query_string"));
        $response ['query']['totalCount'] = $totalCount;

        // Point to next set of elements
        $next = $offset + $limit;
        $response ['query']['next'] = ($totalCount > $next
                        ? "$baseURL/elements?limit=$limit&offset=$next$queryURL"
                        : null);
    }

    print json_encode($response);

?>