<?php

	include('bootstrap.php');
	require_once('application/helpers/cbapi_helper.class.php');
	require_once('application/models/cbapi_model.class.php');
	require_once('application/models/parser_model.class.php');

	$config_builder = new Builder($config);

	$request_url = $config_builder->last('day')->keyword('php')->location('denver')->initialize();

	$cbapi_helper = new CbapiHelper( new CbapiModel() );

	echo '<pre>' . print_r( $cbapi_helper->getResponse($request_url, 'array') ) . '</pre>';
