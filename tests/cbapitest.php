<?php
	require_once('../cbapi.php');

	class CbapiTest extends PHPUnit_Framework_TestCase
	{
		protected $_cbapi = null;
		protected $_key = 'WDTZ14P67NZKL453DBTN';
		protected $_keyword = 'php';
		protected $_location = 'san diego';
		protected $_since_arr = array(1, 3, 7, 30);
		protected $_onetcode_arr = array('15-1099.04', '15-1031.00');

		protected $_jobdid = "J3H1VY687Q1TFP8ZWT9";
		protected $_identifier = array(
			"LocationState",
			"RelocationCovered",
			"BlankApplicationServiceURL",
			"LocationCity"
		);

		protected $_jobdid_arr = array(
			0 => array(
				'DID' => "J3H1VY687Q1TFP8ZWT9"
			),

			1 => array(
				'DID' => "J3J6NN654XQNPPV528C"
			)
		);

		protected $_onetcode_matches_arr = array();

		protected $_specificJobDetails_arr = array("BlankApplicationServiceURL", "LocationCity", "RelocationCovered");


		public function __construct()
		{
		}

		public function setUp()
		{
			$this->_cbapi = new cbapi();
		}

		public function tearDown()
		{
			unset($this->_cbapi);
		}

		public function testGetKeyKeywordLocationSinceRaw()
		{
			//Test our inputs are proper before we invoke logic..
			$this->assertInternalType('string', urlencode($this->_key));
			$this->assertInternalType('string', urlencode($this->_keyword));
			$this->assertInternalType('string', urlencode($this->_location));
			$this->assertContainsOnly('int', $this->_since_arr);

			//Invoke the call..
			for ($i = 0; $i < count($this->_since_arr); ++$i) {
				$rawResults = $this->_cbapi->getKeyKeywordLocationSinceRaw($this->_key, $this->_keyword, $this->_location, $this->_since_arr[$i]);
				$this->assertInternalType('object', $rawResults);
			}
			return $rawResults;
		} //testGetKeyKeywordLocationSinceRaw


		/**
		 * @depends testGetKeyKeywordLocationSinceRaw
		 */
		public function testMapToArray(SimpleXMLElement $rawResults)
		{
			$this->assertInternalType('object', $rawResults);
			$parsed_output = $this->_cbapi->mapToArray($rawResults);
			$this->assertInternalType('array', $parsed_output);

			return $parsed_output;
		}


		/**
		 * @depends testMapToArray
		 */
		public function testFilterJobsOnetCode(array $parsed_output)
		{
			$this->assertInternalType('array', $parsed_output);
			$this->assertInternalType('array', $this->_onetcode_arr);
			$onetcode_matches_arr = $this->_cbapi->filterJobsOnetCode($parsed_output, $this->_onetcode_arr);
			$this->assertInternalType('array', $onetcode_matches_arr);

			$this->_onetcode_matches_arr = $onetcode_matches_arr; //Set internally..
		}


		public function testGetJobDetails()
		{
			//Test our inputs are proper before we invoke logic..
			$this->assertInternalType('string', $this->_key);
			$this->assertInternalType('array', $this->_jobdid_arr); //Might need to update contents of $this->_jobdid_arr as they tend to expire..

			foreach ($this->_jobdid_arr as $k1 => $v1) { //Foreach loop is for the test, not the actual method..
				$this->assertInternalType('array', $v1);
				foreach ($v1 as $k2 => $v2) {
					$this->assertInternalType('string', $v2);
				}
			}

			$jobDetails_container = $this->_cbapi->getJobDetails($this->_key, $this->_jobdid_arr); //@jobDetails_arr (@return) is an array of objects..
			$this->assertInternalType('array', $jobDetails_container);
			return $jobDetails_container;
		}


		/**
		 * @depends testGetJobDetails
		 */
		public function testPerformFinalMerge(array $jobDetails_container)
		{
			$jobDetails_arr = $this->_cbapi->mapToArray($jobDetails_container);
			$this->assertInternalType('array', $jobDetails_arr);
			$this->assertInternalType('array', $this->_onetcode_matches_arr);
			$this->assertInternalType('array', $this->_specificJobDetails_arr);
			$finalMerge_arr = $this->_cbapi->performFinalMerge($jobDetails_arr, $this->_onetcode_matches_arr, $this->_specificJobDetails_arr);
			$this->assertInternalType('array', $finalMerge_arr);
		}

	}

?>
