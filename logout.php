<?php
	session_start();
	unset($_SESSION['login']);
	unset($_SESSION['member']);
	if(isset($_COOKIE)){
		unset($_COOKIE['login']['token']);
		setcookie('login[token]', null, -1, '/');
	}
	header("location:index.php");
	exit();
?>