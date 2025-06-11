<?php
	function InputFilter($input){
		global $mysqli_connect;

		$input = trim($input);
		$filter = filter_var($input, FILTER_SANITIZE_STRING);
		$filter = str_replace('"', "", $filter);
		$filter = str_replace("'", "", $filter);
		$mysqli_connect->real_escape_string($filter);
		return $filter;
	}

	function EmailFilter($input){
		global $mysqli_connect;

		$input = trim($input);
		$filter = filter_var($input, FILTER_SANITIZE_EMAIL);
		$filter = str_replace('"', "", $filter);
		$filter = str_replace("'", "", $filter);
		$mysqli_connect->real_escape_string($filter);
		return $filter;
	}

	function NumberFilter($input){
		global $mysqli_connect;

		$input = trim($input);
		$filter = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
		$filter = str_replace('"', "", $filter);
		$filter = str_replace("'", "", $filter);
		$mysqli_connect->real_escape_string($filter);
		return $filter;
	}

	function ValidateEmail($input){
		if (!filter_var($input, FILTER_VALIDATE_EMAIL) === false)
			return true;
		else
			return false;
	}
?>