<?php
	require_once('config/interfaces/module_signature.interface.php');

	class CbapiModule implements IModuleSignature
	{
		private $_key = '';
		private $_blankApplicationServiceURL = false;
		private $_locationCity = false;
		private $_relocationCovered = false;

		public function __construct(array $cbapi_options) {
			$this->validateUsableOptions($cbapi_options);
		}

		public function validateUsableOptions(array $cbapi_options) {
			foreach($cbapi_options as $option => $val)
			{
				if($option == '_key')
				{
					if(is_string($val))
					{
						$token = preg_replace('/\s+/', '', $val);
						if(!empty($token))
						{
							$this->_key = $token;
						}else{
							throw new UnexpectedValueException('The _key option must be a non-empty string!');
						}
					}else{
						throw new UnexpectedValueException('The _key option must be a valid string!');
					}
				}else{
					if($option == '_blankApplicationServiceURL' || $option == '_locationCity' || $option == '_relocationCovered')
					{
						if(filter_var($val, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) == null)
						{
							throw new InvalidArgumentException("The {$option} option is not a valid boolean filter!");
						}else{
							$this->$option = $val;
						}
					}else{
						throw new OutOfBoundsException("{$option} is not a valid option for the Cbapi API. ");
					}
				}
			}

			return $this;
		}

		public function getKey() {
			return $this->_key;
		}

		public function getBlankApplicationServiceURL() {
			return $this->_blankApplicationServiceURL;
		}

		public function getLocationCity() {
			return $this->_locationCity;
		}

		public function getRelocationCovered() {
			return $this->_relocationCovered;
		}
	}

?>
