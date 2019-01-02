<?php
/*
    element/element.php

    RadElement -- Display element information
    CEK 2016-05-15
*/

    include ('../config/open_db.php');

    // Get element ID
    extract ($_GET);
    if (! isset ($id) || ! ctype_digit ($id)) {
        header ("Location: /");
        exit;
    }

    $result = mysql_query ("SELECT * FROM Element WHERE id = $id LIMIT 1")
            or die(mysql_error());

    if (mysql_num_rows ($result) == 0) {
        header ("Location: /");
        exit;
    }

    extract ($row = mysql_fetch_assoc ($result));
    $elementID = $id;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<?php require_once('../config/header.php'); ?>
<title><?php echo $Name;?> | RadElement.org</title>
<meta name="description" content="<?php echo $Name;?> | RadElement.org" />
<link rel="api" href="/api/v1/elements/RDE<?php echo $elementID;?>">
</head>

<body>
<?php
include_once("../config/analyticstracking.php");
echo $topbar;
?>
<div class="container">
    <div class="content">

<?php

    print "<table>
    <tr><td width=60><img src='/images/RadElement_logo_50.png'></td><td><h2>$Name</h2></td></tr>
    </table>
    <hr>
    <table>
    <tr><td align=right>Data Element ID:</td><td>RDE$elementID</td></tr>
    <tr><td align=right>Name:</td><td>$Name</td></tr>
    <tr><td align=right>Definition:</td><td>$definition</td></tr>";

    // Type:  valueSet
    if (strcmp($valueType, "valueSet") == 0) {
        print "<tr><td align=right>Value:</td><td>Enumerated ";
        if (isset($valueMin)) {
            if (isset($valueMax)) {
                $s = ($valueMax > 1 ? 's' : '');
                if ($valueMin == $valueMax)
                    print "(exactly $valueMin value$s)\n";
                else
                    print "($valueMin - $valueMax value$s)\n";
            }
            else {
                $s = ($valueMin > 1 ? 's' : '');
                print "($valueMin or more values)\n";
            }
        }
        else {
            if (isset($valueMax)) {
                print "(0 - $valueMax value$s)\n";
            }
        }

        $query = "SELECT ElementValue.id,
                         ElementValue.value,
                         ElementValue.name,
                         GROUP_CONCAT(IndexCode.code SEPARATOR ',') as indexCodeList,
                         GROUP_CONCAT(IndexCode.system SEPARATOR ',') as indexCodeSystemList,
                         GROUP_CONCAT(IndexCodeSystem.codeURL SEPARATOR ',') as indexCodeSystemCodeURL
                    FROM ElementValue
                    LEFT JOIN IndexCode on ElementValue.value = IndexCode.display
                    LEFT JOIN IndexCodeSystem on IndexCode.system = IndexCodeSystem.abbrev
                    WHERE elementID = $elementID
                    GROUP BY ElementValue.id
                    ORDER BY ElementValue.id";

        $valueResult = mysql_query ($query);
        print "<p><ul>\n";
        while ($valueRow = mysql_fetch_assoc ($valueResult)) {
            extract ($valueRow);
            if (isset($indexCodeList)){
                $codes = explode (',', $indexCodeList);
                $systems = explode (',', $indexCodeSystemList);
                $urls = explode (',', $indexCodeSystemCodeURL);
                print "<li>$value = $name &nbsp;&nbsp;(";
                for ($i = 0; $i < count($codes); $i++) {
                    $href = preg_replace ('/\$code/', $codes[$i], $urls[$i]);
                    print "<a href=\"$href\">$codes[$i]</a>";
                    print ($i == count($codes) - 1 ? '' : ', ');
                }
                print ")</li>\n";
            }
            else
                print "<li>$value = $name</li>\n";
        }
        print "</ul></p>\n";
    }

    // Type:  integer, float, or date
    else /* if (strcmp ($valueType, "integer") == 0
                || strcmp ($valueType, "float") == 0
                || strcmp ($valueType, "date") == 0) */ {
        $value = ucfirst ($valueType);
        print "<tr><td align=right>Value:</td><td>$value<br>\n";
        print_info ('Minimum', $valueMin);
        print_info ('Maximum', $valueMax);
        print_info ('Step', $stepValue);
        print_info ('Units', $unit);
    }

    // Display other metadata
    $column = array (
        'question',
        'instructions',
        'codes',
        'indexingCodes',
        'references',
        'version',
        'synonyms',
        'source',
        'status',
        );

    $row['version'] .= ($row['versionDate'] == '' ? '' : '&nbsp; ('.$row['versionDate'].')');
    $row['status'] .= ($row['statusDate'] == '' ? '' : '&nbsp; ('.$row['statusDate'].')');

    foreach ($column as $key) {
        if (($x = $row[$key]) != '') {
            $key = ucfirst ($key);
            print " <tr><td align=right>$key:</td><td>$x</td></tr>\n";
        }
    }

    // Display related sets
    $result = mysql_query ("SELECT elementSetID, name
                            FROM ElementSetRef, ElementSet
                            WHERE ElementSetRef.elementID = $elementID
                            AND ElementsetID = ElementSet.id") or die(mysql_error());
    if (mysql_num_rows ($result) > 0) {
        print "<tr><td>Sets:</td><td>\n";
        while ($row = mysql_fetch_assoc ($result)) {
            extract ($row);
            print "<a href=\"/set/RDES$elementSetID\">$name</a><br>\n";
        }
        print "</td></tr>\n";
    }
    // Display indexing codes
    $result = mysql_query (
                "SELECT system, code, display, codeURL
                 FROM IndexCodeElementRef, IndexCode, IndexCodeSystem
                 WHERE IndexCodeElementRef.elementID = $elementID
                 AND IndexCodeElementRef.valueCode IS NULL
                 AND IndexCodeElementRef.codeID = IndexCode.id
                 AND IndexCode.system = IndexCodeSystem.abbrev
                 ORDER BY system, code") or die(mysql_error());
    if (mysql_num_rows ($result) > 0) {
        print "<tr><td>Index codes:</td><td>\n";
        while ($row = mysql_fetch_assoc ($result)) {
            extract ($row);
            $href = preg_replace ('/\$code/', $code, $codeURL);
            print "<a target=\"code\" href=\"$href\">$display &ndash; $system::$code</a>&nbsp;&nbsp;<img src=\"/images/linkout.png\"><br>\n";
        }
        print "</td></tr>\n";
    }

    print "</table>\n";
?>

<?php  echo $footer; ?>
    </div></div>
</body>
</html>

<?php
    function print_info ($title, $value) {
        if ($value <> '')
            print "<br>$title: $value\n";
    }
?>
