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
	}