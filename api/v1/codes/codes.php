<?php
/*
	/api/codes/codes.php
*/

	
	// Connect to database
	require ('../../../config/open_db.php');
	
	// Define API base address
	$baseURL = 'http://radelement.org/api/v1';
	
	// Specify JSON output
	header ('Content-type: application/json');

	// 	Get web service parameters
	//			/codes					List available coding systems
	//			/codes/X				List all codes used from system X
	//			/codes/X/Y				Tally data elements + values indexed by code Y
	//			/codes/X/Y/elements		List data elements indexed by code Y
	//			/codes/X/Y/values		List data-element values indexed by code Y

	
	extract ($_GET);
	
	list ($system, $code, $item) = explode ("/", $_param);	
	$system = mysql_real_escape_string ($system);
	$code = mysql_real_escape_string ($code);

	
	// If set, $item must be either 'elements' or 'values'
	$item = mysql_real_escape_string ($item);
	if ($item <> '' && strcmp ($item, 'elements') && strcmp ($item, 'values')) {
		$response = array (
			'id' => null,
			'msg' => "ERROR - invalid parameter '$item'"
			);
		print (json_encode ($response));
		exit;
	}

 
 
	$limit = (ctype_digit($limit) ? min(0+$limit,100) : 20);
	$offset = (ctype_digit($offset) ? 0+$offset : 0);

	
	
	//
	//			/codes
	//
	// If no parameters ("/codes"), list all coding systems
	if ($system == '' && $code == '') {
		$result = mysql_query (
				"SELECT abbrev AS system, name, 
						CONCAT('$baseURL/codes/',abbrev) AS href
					FROM CodeSystem 
					ORDER BY abbrev")
			or die(mysql_error());
		
		$response ['count'] = mysql_num_rows ($result);	
		while ($row = mysql_fetch_assoc ($result)) {
			$response ['systems'] [] = $row;
		}
	}

	
	//
	//			/codes/SYSTEM
	//
	// If only the SYSTEM is specified ("/codes/LOINC"), 
	//		list the code values used from that system
	else if ($system <> '' && $code == '') {
		
		// Retrieve details about coding system
		$result = mysql_query ("SELECT abbrev, name, oid, systemURL
				FROM CodeSystem WHERE abbrev = '$system' LIMIT 1") 
			or die(mysql_error());
		
		if (mysql_num_rows ($result) == 0) {
			$response = array (
				'id' => null,
				'msg' => "ERROR - no '$system' coding system"
				);
			print (json_encode ($response));
			exit;
		}
		$response ['system'] = mysql_fetch_assoc ($result);

		// List the codes used from that system
		$result = mysql_query (
				"SELECT code, display, 
						CONCAT('$baseURL/codes/$system/',code) AS href
					FROM Code 
					WHERE system = '$system'
					ORDER BY code
					LIMIT $limit OFFSET $offset") 
						or die(mysql_error());
						
		$response ['query']['limit'] = $limit;
		$response ['query']['offset'] = $offset;
		$response ['query']['count'] = mysql_num_rows ($result);		
		$response ['codes'] = null;

		while ($row = mysql_fetch_assoc ($result)) {
			$response ['codes'] [] = $row;
		}
		
		// Tally total number of codes
		$totalCount = mysql_num_rows (mysql_query (
						"SELECT * FROM Code WHERE system = '$system'"));
		$response ['query']['totalCount'] = $totalCount;

		// Point to next set of elements
		$next = $offset + $limit;
		$response ['query']['next'] = ($totalCount > $next 
					? "$baseURL/codes/$system?limit=$limit&offset=$next" : null);
	}

	
	//
	//			/codes/SYSTEM/CODE/...
	//
	// If a coding SYSTEM and code VALUE are specified,
	//		tally the elements and values indexed by the given code
	else {
		
		// Make sure it's a valid coding system; get the codeURL
		$result = mysql_query ("SELECT codeURL FROM CodeSystem WHERE abbrev = '$system' LIMIT 1") 
			or die(mysql_error());
		
		if (mysql_num_rows ($result) == 0) {
			$response = array (
				'id' => null,
				'msg' => "ERROR - no '$system' coding system"
				);
			print (json_encode ($response));
			exit;
		}
		extract (mysql_fetch_assoc ($result));

		// Make sure it's a valid code;  get the codeID
		$result = mysql_query ("SELECT id as codeID, display FROM Code 
								WHERE system = '$system' AND code = '$code' LIMIT 1") 
			or die(mysql_error());
		
		if (mysql_num_rows ($result) == 0) {
			$response = array (
				'id' => null,
				'msg' => "ERROR - no $system '$code' code"
				);
			print (json_encode ($response));
			exit;
		}
		extract (mysql_fetch_assoc ($result));
		
		
		//
		//			/codes/SYSTEM/CODE
		//
		if ($item == '') {

			$response ['query'] = array (
							'system' => $system,
							'code' => $code,
							'display' => $display,
							'codeURL' => preg_replace ('/\$code/', $code, $codeURL)
							);
			
			// List the elements indexed by the specified system + code
			$numElements = mysql_num_rows (mysql_query (
					"SELECT elementID FROM CodeRef
						WHERE codeID = $codeID
						AND valueCode IS NULL")); 
			$numValues = mysql_num_rows (mysql_query (
					"SELECT elementID, valueCode FROM CodeRef
						WHERE codeID = $codeID
						AND valueCode IS NOT NULL")); 
			
			$response ['elements'] = array (
				'count' => $numElements,
				'href' => ($numElements ? "$baseURL/codes/$system/$code/elements" : null)
				);
			$response ['values'] = array (
				'count' => $numValues,
				'href' => ($numValues ? "$baseURL/codes/$system/$code/values" : null)
				);
			
		}

		
		//
		//			/codes/SYSTEM/CODE/elements
		//
		// List the elements indexed by the given code
		else if ($item == 'elements') {
			
			$result = mysql_query (
				"SELECT CONCAT('RDE', CodeRef.elementID) AS id, Element.name,
							CONCAT('$baseURL/elements/RDE', CodeRef.elementID) AS href
						FROM CodeRef, Element
						WHERE codeID = $codeID
						AND Element.id = CodeRef.elementID
						AND CodeRef.valueCode IS NULL
						ORDER BY elementID
						LIMIT $limit OFFSET $offset")
				or die(mysql_error());
					
			$response ['query']['limit'] = $limit;
			$response ['query']['offset'] = $offset;
			$response ['query']['count'] = mysql_num_rows ($result);
			
			$response ['elements'] = null;

			while ($row = mysql_fetch_assoc ($result)) {
				$response ['elements'] [] = $row;
				}

			// Tally total number of elements
			$totalCount = mysql_num_rows (mysql_query (
					"SELECT DISTINCT elementID 
						FROM Code, CodeRef 
						WHERE system = '$system' and code = '$code'
						AND Code.id = codeID
						AND valueCode IS NULL"));
			$response ['query']['totalCount'] = $totalCount;

			// Point to next set of elements
			$next = $offset + $limit;
			$response ['query']['next'] = ($totalCount > $next 
						? "$baseURL/codes/$system/$code/elements?limit=$limit&offset=$next" : null);
		}

	
		//
		//			/codes/SYSTEM/CODE/values
		//
		// List the values indexed by the given code
		else if ($item == 'values') {
			
			$result = mysql_query (
				"SELECT CONCAT('RDE', CodeRef.elementID) AS id, 
						valueCode AS code,
						ElementValue.name,
						CONCAT('$baseURL/elements/RDE', CodeRef.elementID, 
									'/values/', CodeRef.valueCode) AS href
					FROM CodeRef, Element, ElementValue
					WHERE codeID = $codeID
					AND CodeRef.elementID = Element.id
					AND CodeRef.valueCode = ElementValue.code
					AND CodeRef.elementID = ElementValue.elementID 
					ORDER BY CodeRef.elementID, CodeRef.valueCode
					LIMIT $limit OFFSET $offset")
				or die(mysql_error());
					
			$response ['query']['limit'] = $limit;
			$response ['query']['offset'] = $offset;
			$response ['query']['count'] = mysql_num_rows ($result);
			
			$response ['values'] = null;

			while ($row = mysql_fetch_assoc ($result)) {
				$response ['values'] [] = $row;
				}

			// Tally total number of values
			$totalCount = mysql_num_rows (mysql_query (
					"SELECT DISTINCT elementID, valueCode 
						FROM CodeRef 
						WHERE codeID = $codeID
						AND valueCode IS NOT NULL"));
			$response ['query']['totalCount'] = $totalCount;

			// Point to next set of values
			$next = $offset + $limit;
			$response ['query']['next'] = ($totalCount > $next 
						? "$baseURL/codes/$system/$code/values?limit=$limit&offset=$next" : null);
		}
	}
	
	print json_encode($response);

?>