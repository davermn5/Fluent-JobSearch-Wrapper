<?php

	interface IModuleSignature
	{
		public function validateUsableOptions(array $individual_module_options);
	}