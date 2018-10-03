<?php
/*
	/editor/key.php
*/

// -- this print statement is test encrypting user passwords
// print crypt("cde4RSNA", '$1$CD$');


	function make_key () {
		$key = crypt($_SERVER['PHP_AUTH_USER'], 'CD');
		return ($key);
	}
	
	function is_valid_key ($key) {
		// list ($a, $b) = explode (":", $key);
		// $time = strrev(base64_decode($a));
		// print "$time\n";
		return ($key == crypt($_SERVER['PHP_AUTH_USER'], 'CD'));
	}

?>