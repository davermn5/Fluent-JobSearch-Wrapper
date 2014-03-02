<?php

	class Builder
	{
		private $config = null;
		private $request_url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=";
		private $request_url_built = "";

		public function __construct(Config $config) {
			$this->config = $config;
			$this->initRequestUrl();
		}

		private function initRequestUrl() {
			$this->request_url .= $this->config->cbapi->getKey();
			$this->request_url_built = $this->request_url;
		}

		public function keyword($keyword) {
			$this->request_url_built .= "&Keywords={$keyword}";

			return $this;
		}

		public function location($location) {
			$this->request_url_built .= "&Location={$location}";

			return $this;
		}

		public function last($timeGroup, $defaultTimeGroup = 'month') {
			$validGroups = array(
				'day'   => 1,
				'swing' => 3,
				'week'  => 7,
				'month' => 30
			);

			if(array_key_exists($timeGroup, $validGroups))
			{
				$defaultTimeGroup = $validGroups[$timeGroup];
			}else{
				$defaultTimeGroup = $validGroups[$defaultTimeGroup];
			}
			$this->request_url_built .= "&PostedWithin={$defaultTimeGroup}";

			return $this;
		}

		public function initialize() {
			$currentState            = $this->request_url_built;
			$this->request_url_built = $this->request_url;

			return $currentState;
		}
	}