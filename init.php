<?php

	// Error Reporting

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	include 'admin/connect.php';

	$sessionUser = '';
	
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}

	// Routes

	$tpl 	= 'includes/templates/'; // Template Directory
	$func	= 'includes/functions/'; // Functions Directory
	// Include The Important Files

	include $func . 'functions.php';
	include $tpl . 'header.php';
	

	