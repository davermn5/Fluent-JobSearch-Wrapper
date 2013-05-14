<?php
 //cbapi.php
 
  //Rough Prototype..
   /*
   *  Tell a story:
   *   a.) Return all job results having basic info (keyword, location, #days back) 
   *    i.) Return basic info based on keyword, location, #days back from the client API (you are building)..  done..
   *    ii.) Filter only those job details based on your supplied 'OnetCode' array..  
   *    iii.) Store those job details into the (already created) 'listed_jobs' db table..      
   *
   */      
  
 function getJobCountSinceDate( $key, $keywords, $location, $daysBackToLook ) {
		$location = urlencode($location);
		$keywords = urlencode($keywords);
		$url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=$key&ExcludeNational=True&Keywords=$keywords&PostedWithin=$daysBackToLook&Location=$location";
		
		try {
			$xml = simplexml_load_file($url);
		}catch(Exception $e){
			print_r($e);
		}
		
    
		$count = $xml->TotalCount;
		return $count;
    
	}
  
  
  function getJobDetails( $key, $jobdid, $identifier ) {
		$jobdid = urlencode($jobdid);
		$url = "http://api.careerbuilder.com/v1/job?DeveloperKey=$key&DID=$jobdid";
		$xml = simplexml_load_file($url);

    try{
			$xml = simplexml_load_file($url);
		}catch(Exception $e){
			print_r($e);
		}

    if($identifier == 'jobtitle')
    {
     $matched_identifier = $xml->Job->JobTitle;
    }
    elseif($identifier == 'company')
    {
     $matched_identifier = $xml->Job->Company;
    }
    elseif($identifier == 'employment_type')
    {
     $matched_identifier = $xml->Job->EmploymentType;
    }
    elseif($identifier == 'blank_application_service_URL')
    {
     $matched_identifier = $xml->Job->BlankApplicationServiceURL;
    }
    elseif($identifier == 'location_city')
    {
     $matched_identifier = $xml->Job->LocationCity;
    }
    elseif($identifier == 'latitude')
    {
     $matched_identifier = $xml->Job->LocationLatitude;
    }
    elseif($identifier == 'longitude')
    {
     $matched_identifier = $xml->Job->LocationLongitude;
    }
    elseif($identifier == 'state')
    {
     $matched_identifier = $xml->Job->LocationState;
    }
    elseif($identifier == 'relocation')
    {
     $matched_identifier = $xml->Job->RelocationCovered;
    }
		return $matched_identifier;
	}
  
  function determineOnlineApplyEligibility( $key, $jobdid ){
   $url = "http://api.careerbuilder.com/v1/application/blank?DeveloperKey=$key&JobDID=$jobdid";
   $xml = simplexml_load_file($url);

    try{
			$xml = simplexml_load_file($url);
		}catch(Exception $e){
			print_r($e);
		}
    
    foreach($xml as $k1 => $v1){
     if($k1 == 'Errors')
     {
      if( count($v1) < 1 )
      {
       return 1;
      }
      else{
       return 0;
      }
     }
    }
  
  }
  
  
  function getJobsKeywordLocationSince($key, $keyword, $location, $since){
   $location = urlencode($location);
	 $keyword = urlencode($keyword);
   $url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=$key&ExcludeNational=True&Keywords=$keyword&PostedWithin=$since&Location=$location";
  
   try{
			$xml = simplexml_load_file($url);
		}catch(Exception $e){
			print_r($e);
		}
    
    print_r($xml);
    
  }
  
  
  //echo getJobCountSinceDate('WDTZ14P67NZKL453DBTN', 'php', 'vancouver', 30);  //Can be 1,3,7, or 30...
    echo '</br></br>';
  echo getJobsKeywordLocationSince( 'WDTZ14P67NZKL453DBTN', 'php', 'adelaide', 7 );
  
  /*
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'jobtitle');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'company');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'employment_type');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'blank_application_service_URL');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'location_city');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'latitude');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'longitude');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'state');
   echo '</br>';
  echo getJobDetails('WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2', 'relocation');
  */
  
  //echo determineOnlineApplyEligibility( 'WDTZ14P67NZKL453DBTN', 'J3G8F577GDR6P4NH7V2' );
       
?>