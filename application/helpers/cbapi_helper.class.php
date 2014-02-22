<?php

class CbapiHelper
{
	public $model = null;
	public $request_url;

	public function __construct( CbapiModel $model, $request_url ){
		$this->model = $model;
		$this->request_url = $request_url;
	}

	public function getResponse(){
		try{
			return $this->model->getResponse($this->request_url, 'array');
		}catch(UnexpectedValueException $e){
			$this->showErrorMessage($e);
		}
	}

	private function showErrorMessage($e){
		echo 'A ' . get_class($e) . ' type of exception was thrown with the following message: ' . $e->getMessage() . '</br></br>StackTrace:</br>'
			. $e->getTraceAsString();
	}
}
