<?php
	
	function validate($username, $password) {
		if ( is_null($username) || is_null($password) ) {
			return false;
		}
		return true;
	}

?>