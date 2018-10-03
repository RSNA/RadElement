<?php
/*
	/api/elements/element-values.php
	Display allowed values for a specific element
*/

	// Specify JSON output
	header ('Content-type: application/json');

	// Get web service parameters
	//		/elements/RDE3/values			List allowed values for element RDE3
	//		/elements/RDE3/values/X			Show information about value X for element RDE3
	//		/elements/RDE3/values/X/codes	Show indexing codes for value X for element RDE3

	
	extract ($_GET);
	
	// Connect to database
	require ('../../../config/open_db.php');
	
	// Define API base address
	$baseURL = 'http://radelement.org/api/v1';
	
	// Make sure it's a valid element ID
	$id = mysql_real_escape_string ($_id);
	if (! ctype_digit($id)) {
		$response = array (
			'values' => null,
			'msg' => "Invalid ID number"
			);
	}
	$result = mysql_query ("SELECT * FROM Element WHERE id = $id") 
			or die(mysql_error());
	if (mysql_num_rows ($result) == 0) {
		$response = array (
			'values' => null,
			'msg' => "No such ID number"
			);
		print (json_encode ($response));
		exit;
	}
	
	// Get the data element's information
	extract (mysql_fetch_array ($result));
	
	// Make sure it's a valueSet
	if (strcmp($valueType, "valueSet") <> 0) {
		$response = array (
			'values' => null,
			'msg' => "Not a value set"
			);
		print (json_encode ($response));
		exit;
	}

	// If value specified, list its information
	if ($_param <> '') {
		$_param = mysql_real_escape_string ($_param);
		$valueResult = mysql_query (
			"SELECT name, definition
				FROM ElementValue 
				WHERE elementID = $id AND code = '$_param'
				LIMIT 1");
		$response = mysql_fetch_assoc ($valueResult);
		
		// List images
		$images = null;
		$result = mysql_query (
			"SELECT sourceURL, caption
				FROM ImageRef, Image
				WHERE elementID = $id AND elementValue = '$_param'
				AND ImageRef.imageID = Image.id
				ORDER BY ImageRef.id") 
					or die(mysql_error());
		
		while ($row = mysql_fetch_assoc ($result)) {
			$images [] = $row;
		}
		$response ['images'] = $images;
		
		// List indexing codes
		$response['codes'] = null;
		$result = mysql_query (
				"SELECT system, code, display,
						CONCAT('$baseURL/codes/',system,'/',code) AS href
					FROM Code, CodeRef
					WHERE elementID = $id AND valueCode = '$_param'
					AND Code.id = codeID
					ORDER BY system, code");
		while ($row = mysql_fetch_assoc ($result)) {
			$response['codes'] [] = $row;
		}
	}
	
	// Otherwise list all values
	else {
		$allowedValue = array ();
		$valueResult = mysql_query (
				"SELECT code AS value, name, 
					CONCAT('$baseURL/elements/RDE$id/values/',code) AS href
					FROM ElementValue 
					WHERE elementID = $id
					ORDER BY id");
		while ($valueRow = mysql_fetch_assoc ($valueResult)) {
			$allowedValue [] = $valueRow;	
		}
	$response ['values'] = $allowedValue;
	}
	
	print json_encode($response);
		
?>