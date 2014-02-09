<?php

	/**
	 *
	 * Copyright (c) 2013 David Roman <davermn5@gmail.com
	 * All rights reserved.
	 *
	 * Redistribution and use in source and binary forms, with or without modification,
	 * are permitted provided that the following conditions are met:
	 *
	 * * Redistributions of source code must retain the above copyright notice,
	 * this list of conditions and the following disclaimer.
	 *
	 * * Redistributions in binary form must reproduce the above copyright notice,
	 * this list of conditions and the following disclaimer in the documentation
	 * and/or other materials provided with the distribution.
	 *
	 * * Neither the name of David Roman nor the names of contributors
	 * may be used to endorse or promote products derived from this software
	 * without specific prior written permission.
	 *
	 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
	 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
	 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
	 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER ORCONTRIBUTORS
	 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY,
	 * OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
	 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
	 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
	 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
	 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
	 * POSSIBILITY OF SUCH DAMAGE.
	 *
	 * @author  David Roman
	 * @license BSD License
	 */

	include('bootstrap.php');

	die();

	/*
	  *  Background: Careerbuilder.com provides a basic API through
	  *              which access can be made via public authentication.
	  *
	  *  Purpose:    A simple REST client which sits on top of the
	  *              careerbuilder.com API.
	  *
	  *  Helps to:   Abstract the API details for simplicity sake (see code below
	  *              the class itself), and for developers looking to
	  *              create projects from scratch using careerbuilder.com
	  *
	  */

	class Cbapi
	{
		public function __construct()
		{
		}


		/*
		   *   Method:  getKeyKeywordLocationSinceRaw()
		   *   @Purpose: Fetch raw results given a key, keyword, location and date
		   *    @param string    $key         The developer key provided
		   *    @param string    $keyword     The keyword to search for
		   *    @param string    $location    The location to search in
		   *    @param int       $since       Possible enum values are 1,3,7,30
		   *
		   *   @return SimpleXmlElement Object
		   */
		public function getKeyKeywordLocationSinceRaw($key, $keyword, $location, $since)
		{
			$url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=$key&Keywords=$keyword&Location=$location&PostedWithin=$since";

			try {
				$xml = simplexml_load_file($url);
			} catch (Exception $e) {
				print_r($e);
			}
			return $xml;
		} //end  getKeyKeywordLocationSinceRaw()..


		/*
		   *  Method  mapToArray()
		   *  @Purpose Convert the SimpleXmlElement Object into an array format
		   *           for easier (relatively) traversing..
		   *   @param  SimpleXmlElement Object  $rawResults  The object we wish to parse
		   *
		   *  @return array  $parsed_arr  The arrayified format that is returned
		   */
		public function mapToArray($rawResults)
		{
			$parsed_arr = @json_decode(@json_encode($rawResults), 1);
			return $parsed_arr;
		}


		/*
		   *  Method  filterJobsOnetCode()
		   *  @Purpose Search the haystack for jobs matching specific 'onetcode' inputs..
		   *   @param  array  $parsed_output    The haystack from which to search
		   *   @param  array  $onetcode_arr     A list of options specifying the onetcode to search for
		   *
		   *  @return array  $matches       Represents an array of matching jobs with their details, including
		   *                                 global details necessary for db insert later on
		   */
		public function filterJobsOnetCode($parsed_output, $onetcode_arr)
		{
			$matches = array();
			foreach ($parsed_output as $k1 => $v1) {
				if (($k1 != 'Results') && ($k1 != 'SearchMetaData')) {
					$matches[$k1] = $v1;
				}
				if ($k1 == 'Results') {
					if (count($v1) > 0) {
						$container_arr = $v1['JobSearchResult'];
						$plural = 0;
						foreach ($container_arr as $knum => $vnum) { //Do we have multiple records, or just 1x? Finding out now..
							if (is_numeric($knum)) {
								$plural = 1;
							}
						}
						if ($plural == 1) //More than 1x record constitutes each record being wrapped in a parent numeric index..
						{
							for ($i = 0; $i < count($container_arr); ++$i) {
								if (count($onetcode_arr) > 0) {
									if (in_array($container_arr[$i]['OnetCode'], $onetcode_arr)) {
										$matches[] = $container_arr[$i];
									}
								} elseif (count($onetcode_arr) < 1) //If onetcodes_arr was not filled out (ie empty)..
								{
									$matches[] = $container_arr[$i];
								}
							} //end for..
						} elseif ($plural == 0) //If 1x record exists, careerbuilder will not add a parent numeric index (container)..
						{
							if (in_array($container_arr['OnetCode'], $onetcode_arr)) {
								$matches[] = $container_arr;
							}
						}
					} else {
						echo 'No results found for the given parameters. Please go back and try again.';
					}
				} /////
				if ($k1 == 'SearchMetaData') {
					$metadata_container_arr = $v1['SearchLocations']['SearchLocation'];
					foreach ($metadata_container_arr as $k2 => $v2) {
						$matches[$k2] = $v2;
					}
				}
			}
			return $matches;
		} //filterJobsOnetCode()..


		/*
		   *  Method  getJobDetails()
		   *   @Purpose: The purpose of this method is to retrieve the
		   *             job attributes which are located across different API
		   *             response objects. So we make a call and return an
		   *             array of objects (consisting of the drill-down job details
		   *             for each job listed in $jobdid_arr)..
		   *   @param string  $key         The developer key provided
		   *   @param array   $jobdid_arr  An array consisting of generic job details
		   *
		   *  @return  An array of SimpleXmlElement Objects  (each object has drill-down job details)
		   */
		public function getJobDetails($key, $jobdid_arr)
		{
			if ((is_array($jobdid_arr)) && (is_string($key))) {
				$temp_arr = array();
				foreach ($jobdid_arr as $k1 => $v1) {
					if (is_numeric($k1)) {
						foreach ($v1 as $k2 => $v2) {
							if ($k2 == 'DID') {
								$url = "http://api.careerbuilder.com/v1/job?DeveloperKey=$key&DID=$v2";
								$xml = simplexml_load_file($url);

								try {
									$xml = simplexml_load_file($url);
								} catch (Exception $e) {
									print_r($e);
								}

								$temp_arr[$k1] = $xml;
							}
						}
					}
				} //end top foreach..
				return $temp_arr; //An array of objects..
			}

		} //end getJobDetails()..


		/*
		   *  Method  performFinalMerge()
		   *   @Purpose: Combine both arrays so we can return one final array prior to db insertion..
		   *    @param  array  $jobDetails_arr          Represents the array to pluck specific
		   *                                            information from            (pluck from)
		   *    @param  array  $onetcode_matches_arr    The existing array to stuff (turkey)
		   *    @param  array  $specificJobDetails_arr  The specific information we wish to pluck
		   *
		   *   @return  array  $onetcode_matches_arr  An updated, more specific-like version of
		   *                                          the original argument
		   */
		public function performFinalMerge($jobDetails_arr, $onetcode_matches_arr, $specificJobDetails_arr)
		{
			for ($i = 0; $i < count($jobDetails_arr); ++$i) {
				foreach ($jobDetails_arr[$i] as $k1 => $v1) {
					if ($k1 == 'Job') {
						foreach ($v1 as $k2 => $v2) {
							for ($j = 0; $j < count($specificJobDetails_arr); ++$j) {
								if (in_array($k2, $specificJobDetails_arr)) {
									$onetcode_matches_arr[$i][$k2] = $v2;
								}
							}
						}
					} //end if Job..
				} //end top  foreach..
			}
			return $onetcode_matches_arr;
		} //end performFinalMerge()..


		/*
		   *  Method  dbStuff()  (PROTOTYPE)
		   *   @Purpose  Is used by the main instruction set
		   *             in order to insert our drill-down results
		   *             into the database..
		   *    @param  array  $onetcode_matches_arr  Represents the array to dissect
		   *                                          and insert its parts into the db
		   *   @return  void
		   *
		   */
		public function dbStuff($onetcode_matches_arr)
		{

			$db_stuff_arr = array();

			foreach ($onetcode_matches_arr as $gk1 => $gv1) {
				if (!is_numeric($gk1)) {
					if ($gk1 == 'TimeResponseSent')
						$TimeResponseSent = time();
					if ($gk1 == 'City')
						$City = $gv1;
					if ($gk1 == 'StateCode')
						$StateCode = $gv1;
				}
			}


			//Find the # of numeric indices..
			$k = 0;
			foreach ($onetcode_matches_arr as $k1 => $v1) {
				if (is_numeric($k1))
					++$k;
			}


			for ($i = 0; $i < $k; ++$i) {
				foreach ($onetcode_matches_arr[$i] as $k1 => $v1) {
					if ($k1 == 'Company') {
						$Company = $v1;
					}
					if ($k1 == 'DID') {
						$DID = $v1;
					}
					if ($k1 == 'OnetCode') {
						$OnetCode = $v1;
					}
					if ($k1 == 'ONetFriendlyTitle') {
						$ONetFriendlyTitle = $v1;
					}
					if ($k1 == 'Distance') {
						$Distance = $v1;
					}
					if ($k1 == 'EmploymentType') {
						$EmploymentType = $v1;
					}
					if ($k1 == 'LocationLatitude') {
						$LocationLatitude = $v1;
					}
					if ($k1 == 'LocationLongitude') {
						$LocationLongitude = $v1;
					}
					if ($k1 == 'PostedDate') {
						$PostedDate = strtotime($v1);
					}
					if ($k1 == 'JobTitle') {
						$JobTitle = $v1;
					}
					if ($k1 == 'BlankApplicationServiceURL') {
						$BlankApplicationServiceURL = $v1;
					}
					if ($k1 == 'LocationCity') {
						$LocationCity = $v1;
					}
					if ($k1 == 'RelocationCovered') {
						$RelocationCovered = $v1;
					}
				}
				$query_sav = "INSERT INTO cbapi.listed_jobs
                    (job_id, parent_city, state_fk, company, relocation, job_document_identifier, onet_code, oNet_friendly_title, distance, employment_type, blank_application_service_url, location_city, location_latitude, location_longitude, job_title, posted_date, time_response_sent )
                     VALUES(NOT NULL, '" . $City . "', '" . $StateCode . "', '" . $Company . "', '" . $RelocationCovered . "', '" . $DID . "', '" . $OnetCode . "', '" . $ONetFriendlyTitle . "', '" . $Distance . "', '" . $EmploymentType . "', '" . $BlankApplicationServiceURL . "', '" . $LocationCity . "', " . $LocationLatitude . "," . $LocationLongitude . ",'" . $JobTitle . "'," . $PostedDate . "," . $TimeResponseSent . ")";


				$rs_sav = mysql_query($query_sav);
				if (mysql_affected_rows() > 0) {
					echo '</br><b>Successful insertion to database</b>.</br></br>';
				}

			}

		}
		//end dbStuff() ..

	} //end Cbapi class..


	/*
	   //Testing the public api..
	  $db_columns = array();
	  $cbapi_A = new Cbapi();
	   $_key = 'WDTZ14P67NZKL453DBTN';
	   $_keyword = 'php';     //php
	   $_location = 'Fort Collins';
	   $_since_arr = array(1,3,7,30);
	   $_specificJobDetails_arr = array("BlankApplicationServiceURL", "LocationCity", "RelocationCovered");
	   $rawResults = $cbapi_A->getKeyKeywordLocationSinceRaw( $_key, $_keyword, $_location, $_since_arr[2] );

	   $parsed_output = $cbapi_A->mapToArray($rawResults);
	   //print_r($parsed_output);

	   $onetcode_arr = array('15-1099.04', '15-1031.00');
	   $specified_arr = $cbapi_A->filterJobsOnetCode( $parsed_output, $onetcode_arr);
	   //print_r($specified_arr);


	   $jobDetails_container = $cbapi_A->getJobDetails( $_key, $specified_arr );
		$jobDetails_arr = $cbapi_A->mapToArray( $jobDetails_container );
		//print_r($jobDetails_arr);


	   //Now combine the $jobDetails_arr with our $specified array..
		$onetcode_matches_arr = $cbapi_A->performFinalMerge( $jobDetails_arr, $specified_arr, $_specificJobDetails_arr );
		 //print_r($onetcode_matches_arr);


		$cbapi_A->dbStuff( $onetcode_matches_arr );
		*/

?>