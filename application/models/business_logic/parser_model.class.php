<?php

	class ParserModel
	{
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