<?php
	class dbConnectionMySQLi
	{
		public function __construct(array $mysqli_options){
			$mysqli = new mysqli($mysqli_options['db_server'],
				$mysqli_options['db_user'],
				$mysqli_options['db_password'],
				$mysqli_options['db_database'],
				$mysqli_options['db_port'],
				$mysqli_options['db_socket']);
			if (mysqli_connect_error()) {
				die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			} else {
				return $mysqli;
			}
		}
	}
?>
