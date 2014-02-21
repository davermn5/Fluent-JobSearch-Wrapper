<?php

include('bootstrap.php');

class CbapiHelper
{
	private $config = null;
	private $request_url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=";

	public function __construct(Configuration $config){
		$this->config = $config;
		$this->initRequestUrl();
	}

	private function initRequestUrl(){
		$this->request_url .= $this->config->cbapi->getKey();
	}

	public function keyword($keyword){
		$this->request_url .= "&Keywords={$keyword}";
		return $this;
	}

	public function location($location){
		$this->request_url .= "&Location={$location}";
		return $this;
	}

	public function last($timeGroup, $defaultTimeGroup = 'month'){
		$validGroups = array(
			'day' => 1,
			'swing' => 3,
			'week' => 7,
			'month' => 30
		);

		if(array_key_exists($timeGroup, $validGroups))
		{
			$defaultTimeGroup = $validGroups[$timeGroup];
		}
		else{
			$defaultTimeGroup = $validGroups[$defaultTimeGroup];
		}
		$this->request_url .= "&PostedWithin={$defaultTimeGroup}";
		return $this;
	}

	public function initialize(){
		return $this->request_url;
	}
}

$cbapi_helper = new CbapiHelper($config);

echo $cbapi_helper->last('day')->keyword('php')->location('denver')->initialize();