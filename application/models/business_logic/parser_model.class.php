<?php

	/**
	 * The purpose of this class is to provide a set of low-level business logic for
	 *  parsing data returned from the model class (protocol).
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
		public function parseSimpleXmlObjectToArray($xml)
		{
			$traversable = json_decode(json_encode($xml), true);
			if(!is_array($traversable)){
				throw new UnexpectedValueException('The conversion from object to array was un-successful.');
			}else{
				return $traversable;
			}
		}
	}