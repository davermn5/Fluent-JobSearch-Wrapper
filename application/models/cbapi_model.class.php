<?php

require_once('application/interfaces/helper_signature.interface.php');

class CbapiModel implements IHelperSignature
{
	public function getResponse($url, $returnType = ''){
		$responseXmlObj = simplexml_load_file($url);
		if( ($responseXmlObj instanceof SimpleXMLElement) !== true )
		{
			throw new UnexpectedValueException('The response did not return a SimpleXMLElement object.');
		}
		else{
			$returnType = strtolower($returnType);
			switch($returnType){
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