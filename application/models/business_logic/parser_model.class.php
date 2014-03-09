<?php

	/**
	 * The purpose of this class is to provide a set of low-level business logic for
	 *  parsing data handed-off by the model class (protocol).
	 */
	class ParserModel
	{
		/**
		 * Parses a SimpleXMLElement object into an array
		 *
		 * @param SimpleXMLElement (object) $xml The SimpleXMLElement object to parse
		 *
		 * @return Returns an array upon success
		 * @throws UnexpectedValueException
		 */
		public function parseSimpleXmlObjectToArray($xml) {
			$traversable = json_decode(json_encode($xml), true);
			if(!is_array($traversable))
			{
				throw new UnexpectedValueException('The conversion from object to array was un-successful.');
			}else{
				return $traversable;
			}
		}


		/**
		 * Searches the target API response set for records having a specific OnetCode value(s)
		 *
		 * If any of the keys are found to be associative within JobSearchResult, this indicates that we are dealing with only 1x result record ($plural = 0)
		 * Conversely, if any of the keys are numeric, this suggests that we are dealing with multiple result records ($plural = 1)
		 *
		 * @param mixed $haystack    The array or object data-type which represents the data response from the API
		 * @param array $onetCodeArr An array of onetcode(s) we should limit the results by
		 *
		 * @return array Returns an array upon success
		 * @throws UnexpectedValueException
		 */
		public function applyFilterOnetCode($haystack, $onetCodeArr) {
			$haystackType = strtolower(gettype($haystack));
			if($haystackType != 'array')
			{
				if($haystackType == 'object')
				{
					//TODO-davermn5 : Convert the haystack object into an array; Ensure it meets the same structure as would the normal array API response type.
				}else{
					throw new UnexpectedValueException('The haystack type was modified, and is not currently supported.');
				}
			}

			$matches = array();
			if(array_key_exists('JobSearchResult', $haystack['Results']))
			{
				$array_keys_output   = array_keys($haystack['Results']['JobSearchResult']);
				$array_filter_output = array_filter($array_keys_output, 'is_numeric');

				if((bool)count($array_filter_output) !== false)
				{
					$plural = 1;
				}else{
					$plural = 0;
				}

				foreach($haystack['Results']['JobSearchResult'] as $k1 => $v1)
				{
					if($plural)
					{
						if(isset($onetCodeArr[$v1['OnetCode']]))
						{
							$matches[] = $v1;
						}
					}else{
						if($k1 == 'OnetCode')
						{
							if(isset($onetCodeArr[$v1]))
							{
								$matches[] = $haystack['Results']['JobSearchResult'];
							}
						}
					}
				}
			}else{
				throw new UnexpectedValueException('The API must have changed the haystack structure, since the JobSearchResult key is missing from the haystack[Results] array.');
			}

			return $matches;
		}
	}