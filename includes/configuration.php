<?php

	require_once('db_conn_mysqli.php');

	class Configuration
	{
		public $mysqli = null;

		public function __construct(array $mysqli_options)
		{
			$mysqli = new dbConnectionMySQLi($mysqli_options);
			$this->initConfig($mysqli);
		}

		private function initConfig(dbConnectionMySQLi $mysqli)
		{
			$this->mysqli = $mysqli;
		}
	}