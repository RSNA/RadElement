<pre>
<?php
/*
	/editor/key.php
*/

print crypt("cde4RSNA", '$1$CD$');
print "\n";
print crypt("CDE4ever", '$1$CD$');
print "\n";
print crypt("cde4RSNA", '$1$CD$');
print "\n";
print crypt("cde4RSNA");
print "\n";
print crypt("cde@rsna");
print "\n";



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