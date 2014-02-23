<?php

require_once('application/interfaces/helper_signature.interface.php');

class CbapiHelper implements IHelperSignature
{
	public $model = null;
	public $request_url;

	public function __construct( IHelperSignature $model ){
		$this->model = $model;
	}

	public function getResponse($request_url, $returnType){
		try{
			return $this->model->getResponse($request_url, $returnType);
		}catch(UnexpectedValueException $e){
			$this->showErrorMessage($e);
		}
	}

	private function showErrorMessage($e){
		echo 'A ' . get_class($e) . ' type of exception was thrown with the following message: ' . $e->getMessage() . '</br></br>StackTrace:</br>'
			. $e->getTraceAsString();
	}
}
