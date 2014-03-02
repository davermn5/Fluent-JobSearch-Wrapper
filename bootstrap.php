<?php

	require_once('config/classes/config.class.php');

	$module_options = array(

		'mysqli_options' => array(
			'db_server'   => "127.0.0.1",
			'db_user'     => "",
			'db_password' => "",
			'db_database' => "cbapi",
			'db_port'     => "3306",
			'db_socket'   => ""
		),

		'cbapi_options'  => array(
			'_key'                        => 'WDTZ14P67NZKL453DBTN',
			'_blankApplicationServiceURL' => true,
			'_locationCity'               => true,
			'_relocationCovered'          => true
		)

	);

	global $config;
	try {
		$config = new Config($module_options);
		require_once('config/builder.class.php');
	} catch (Exception $e) {
		echo 'Bootstrap: A ' . get_class($e) . ' type of exception was thrown with the following message: ' . $e->getMessage();
	}