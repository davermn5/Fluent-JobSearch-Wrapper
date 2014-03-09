<?php

	require_once('application/interfaces/helper_signature.interface.php');
	require_once('application/models/business_logic/parser_model.class.php');

	/**
	 * The purpose of this class is to act as the main protocol for invoking
	 *  lower-level business logic for handling the response passed back from
	 *  the career builder api.
	 */
	class CbapiModel implements IHelperSignature
	{
		/**
		 * Gets the response back from the career builder api, and then performs
		 *  additional processing as necessary.
		 *
		 * @param string $url        The url to send to the careerbuilder api
		 * @param string $returnType Possible values are 'array' or defaults to ''(empty string)
		 *
		 * @return Returns either an array or a SimpleXMLElement object depending upon which return type is specified
		 * @throws UnexpectedValueException
		 */
		public function getResponse($url, $returnType = '') {
			$responseXmlObj = simplexml_load_file($url);
			if(($responseXmlObj instanceof SimpleXMLElement) !== true)
			{
				throw new UnexpectedValueException('The response did not return a SimpleXMLElement object.');
			}else{
				$returnType = strtolower($returnType);
				switch ($returnType)
				{
					case 'array' :
						$parser_model = new ParserModel();

						return $parser_model->parseSimpleXmlObjectToArray($responseXmlObj);
						break;

					default :
						return $responseXmlObj;
						break;
				}
			}
		}


		/**
		 * Responsible for:
		 *  1.) Determining which filter methods should be applied
		 *  2.) Aggregating all records (for further processing)
		 *  3.) Further processing, which consists of removing duplicate records, where filter logic over-laps
		 *
		 * @param mixed $haystack The array or object data-type which represents the data response from the API
		 * @param array $filters  An array of filter(s) to apply to the haystack
		 *
		 * @return mixed Upon success, returns filtered records either within an array or an object
		 */
		public function applyFilters($haystack, $filters) {
			$parser_model = new ParserModel();
			$matchesArr   = array();


			foreach($filters as $filterName => $filterContainer)
			{
				if($filterName == 'onetcodes')
				{
					if(is_array($filterContainer) && count($filterContainer) > 0)
					{
						$matchesArr[$filterName] = $parser_model->applyFilterOnetCode($haystack, $filterContainer);
					}
				}

				//TODO-davermn5 : Try to perform these steps in the following sequential order:

				//TODO-davermn5 : 1.) Need to add more filter options here as they become available, in the bootstrap file..
				//TODO-davermn5 : 2.) Need to aggregate all records (for further processing).
				//TODO-davermn5 : 3.) Need to remove duplicate records if present, where the filter logic over-laps.

				$haystackType = strtolower(gettype($haystack));
				if($haystackType == 'array')
				{
					return $matchesArr;
				}
				elseif($haystackType == 'object')
				{
					//TODO-davermn5 : Convert our array collection ($matchesArr) into a SimpleXMLElement object, and return.
				}
			}
		}
	}