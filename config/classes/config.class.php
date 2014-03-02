<?php

	require_once('config/modules/module_mysqli.class.php');
	require_once('config/modules/module_cbapi.class.php');

	class Config
	{
		public $mysqli = null;
		public $cbapi = null;

		public function __construct(array $module_options) {
			$this->mysqli = array_key_exists('mysqli_options', $module_options) ? new MySQLiModule($module_options['mysqli_options']) : null;
			$this->cbapi  = array_key_exists('cbapi_options', $module_options) ? new CbapiModule($module_options['cbapi_options']) : null;

			$this->initConfig();
		}

		private function initConfig() {
			if($this->mysqli == null)
			{
				throw new DomainException("The required mysqli_options module could not be found inside its parent container module_options. Please re-check the bootstrap file.");
			}
			elseif($this->cbapi == null)
			{
				throw new DomainException("The required cbapi_options module could not be found inside its parent container module_options. Please re-check the bootstrap file.");
			}else{
				return $this;
			}
		}
	}