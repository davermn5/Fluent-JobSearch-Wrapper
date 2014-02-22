<?php
	require_once('config/interfaces/module_signature.interface.php');

	class MySQLiModule implements IModuleSignature
	{
		private $parsedCredentials = array();
		private $usable_options = array(
			'db_server',
			'db_user',
			'db_password',
			'db_database',
			'db_port',
			'db_socket'
		);

		public function __construct(array $mysqli_options)
		{
			$this->validateUsableOptions($mysqli_options);

			$mysqli = new mysqli($this->parsedCredentials['db_server'],
				$this->parsedCredentials['db_user'],
				$this->parsedCredentials['db_password'],
				$this->parsedCredentials['db_database'],
				$this->parsedCredentials['db_port'],
				$this->parsedCredentials['db_socket']);
			if (mysqli_connect_error()) {
				throw new mysqli_sql_exception('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
			} else {
				return $mysqli;
			}
		}

		public function validateUsableOptions(array $mysqli_options)
		{
			foreach ($mysqli_options as $option => $val) {
				if (in_array($option, $this->usable_options)) {
					if (is_string($val)) {
						$token = preg_replace('/\s+/', '', $val);
						$this->parsedCredentials[$option] = $token;
					} else {
						throw new UnexpectedValueException("The {$option} option must be a valid string!");
					}
				} else {
					throw new OutOfBoundsException("{$option} is a non-supported option for Mysqli. ");
				}
			}
		}
	}
