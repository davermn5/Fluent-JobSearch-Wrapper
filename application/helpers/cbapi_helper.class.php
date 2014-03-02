<?php

	require_once('application/interfaces/helper_signature.interface.php');

	/**
	 * This purpose of this class is to return the raw reponse set from the career builder api
	 *
	 * It also forms and defines a contractual relationship between lower-level protocols (which act as data models/resource-getters).
	 */
	class CbapiHelper implements IHelperSignature
	{
		/**
		 * @var IHelperSignature $model The model object (worker) which processes and returns the actual response set
		 */
		public $model = null;


		/**
		 * The class instantiation depends upon an object which implements the IHelperSignature interface
		 *
		 * @param IHelperSignature $model
		 */
		public function __construct(IHelperSignature $model) {
			$this->model = $model;
		}


		/**
		 * Gets the response set returned from the protocol
		 *
		 * @param string $request_url The url to send to the careerbuilder api
		 * @param string $returnType  Possible values are 'array' or ''(empty string)
		 *
		 * @return mixed Returns either an array, or a SimpleXMLElement object
		 *
		 * @catches An UnexpectedValueException
		 */
		public function getResponse($request_url, $returnType) {
			try {
				return $this->model->getResponse($request_url, $returnType);
			} catch (UnexpectedValueException $e) {
				$this->showErrorMessage($e);
			}
		}


		/**
		 * Shows the error message for a given exception
		 *
		 * @param $e
		 */
		private function showErrorMessage($e) {
			echo 'A ' . get_class($e) . ' type of exception was thrown with the following message: ' .
				$e->getMessage() . '</br></br>StackTrace:</br>' . $e->getTraceAsString();
		}
	}
