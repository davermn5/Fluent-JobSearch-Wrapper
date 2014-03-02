<?php

	/**
	 * The purpose of this class is to build the url which will be passed along to the career builder api
	 */
	class Builder
	{
		/**
		 * @var Config The class member which will hold the configuration object
		 */
		private $config = null;

		/**
		 * @var string The beginning url before any custom changes are added
		 */
		private $request_url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=";

		/**
		 * @var string The custom-built url string to use
		 */
		private $request_url_built = "";


		/**
		 * Responsible for creating a new Builder object, then adding-on the api developer key
		 *
		 * @param Config $config The configuration object that will be used to supply the developer key
		 *
		 * @examples:
		 *  $config_builder = new Builder($config);
		 */
		public function __construct(Config $config) {
			$this->config = $config;
			$this->addDeveloperKey();
		}


		/**
		 * Adds the required developer key into the url request string
		 */
		private function addDeveloperKey() {
			$this->request_url .= $this->config->cbapi->getKey();
			$this->request_url_built = $this->request_url;
		}


		/**
		 * Adds a custom keyword into the url request string
		 *
		 * //TODO-davermn5 Add support for multiple keywords.
		 *
		 * @param string $keyword The keyword to search for
		 *
		 * @examples:
		 *  $request_url = $config_builder->keyword('php')->initialize();
		 *
		 * @return Returns a fluent interface
		 */
		public function keyword($keyword) {
			$this->request_url_built .= "&Keywords={$keyword}";

			return $this;
		}


		/**
		 * Adds a custom location into the url request string
		 *
		 * @param string $location The location to search for
		 *
		 * @examples:
		 *  $request_url = $config_builder->location('denver')->initialize();
		 *
		 * @return Returns a fluent interface
		 */
		public function location($location) {
			$this->request_url_built .= "&Location={$location}";

			return $this;
		}


		/**
		 * Limits all matching results to the specified time frame
		 *
		 * @param string $timeFrame        The time frame to choose from
		 * @param string $defaultTimeFrame An optional default time frame
		 *
		 * @examples:
		 *  $request_url = $config_builder->last('week')->initialize();
		 *  $request_url = $config_builder->last('', 'day')->initialize();
		 *
		 * @return Returns a fluent interface
		 */
		public function last($timeFrame, $defaultTimeFrame = 'month') {
			$validGroups = array(
				'day'   => 1,
				'swing' => 3,
				'week'  => 7,
				'month' => 30
			);

			if(array_key_exists($timeFrame, $validGroups))
			{
				$defaultTimeFrame = $validGroups[$timeFrame];
			}else{
				$defaultTimeFrame = $validGroups[$defaultTimeFrame];
			}
			$this->request_url_built .= "&PostedWithin={$defaultTimeFrame}";

			return $this;
		}


		/**
		 * Returns the current state of the url request string, but not before
		 *  resetting it. (This allows re-usability for all new url strings, which
		 *  does not require the class to be instantiated again)
		 *
		 * @examples:
		 *  $request_url = $config_builder->{method(arg)}->initialize();
		 */
		public function initialize() {
			$currentState            = $this->request_url_built;
			$this->request_url_built = $this->request_url;

			return $currentState;
		}
	}