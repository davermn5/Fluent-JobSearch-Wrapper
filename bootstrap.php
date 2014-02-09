<?php

	require_once('includes/configuration.php');

	$mysqli_options = array(
		'db_server' => "",
		'db_user' => "",
		'db_password' => "",
		'db_database' => "",
		'db_port' => "",
		'db_socket' => ""
	);

	global $config;
	$config = new Configuration($mysqli_options);
