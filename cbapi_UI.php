<?php

	include('bootstrap.php');
	require_once('application/helpers/cbapi_helper.class.php');
	require_once('application/models/cbapi_model.class.php');

	//TODO-davermn5 : This is how the config object should store it's Filters & onetCodes ..
	//TODO-davermn5 : At some point, each key name should be converted via 'strtolower' ..
	$filters = array(
		'onetcodes' => array(
			"15-1099.04" => true,
			"43-6011.00" => true
		)
	);

	//Builds the request.
	$config_builder = new Builder($config);
	$request_url = $config_builder->last('week')->keyword('php')->location('denver')->initialize();

	//Gets the response.
	$cbapi_helper = new CbapiHelper( new CbapiModel() );
	$haystack = $cbapi_helper->getResponse($request_url, 'array');

	//Applies filters to the response.
	//TODO-davermn5 : 2nd arg NEEDS to be a method which returns the filterable options..
	//TODO-davermn5 : These filterable options (above line) were applied to the config array, via the bootstrap file.
	$haystack_filtered = $cbapi_helper->applyFilters( $haystack, $filters );

	print_r($haystack_filtered);