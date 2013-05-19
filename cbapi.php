<?php
  require_once('includes/db_conn.php');
  class Cbapi{
   public function __construct(){}
   
   /*
   *   Method:  getKeyKeywordLocationSinceRaw()
   *   @Purpose: Fetch raw results given a key, keyword, location and date   
   *    @param $key
   *    @param $keyword
   *    @param $location
   *    @param $since   
   *
   *   @return SimpleXmlElement Object      
   */      
   public function getKeyKeywordLocationSinceRaw($key, $keyword, $location, $since){
    $url = "http://api.careerbuilder.com/V1/jobsearch?DeveloperKey=$key&Keywords=$keyword&Location=$location&PostedWithin=$since";
   
    try{
			$xml = simplexml_load_file($url);
		}catch(Exception $e){
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
   public function mapToArray($rawResults){
    $parsed_arr = @json_decode(@json_encode($rawResults),1);
    return $parsed_arr;
   }
   
   
  
   /*
   *  Method  filterJobsOnetCode()
   *  @Purpose Search the haystack for jobs matching specific 'onetcode' inputs.. 
   *   @param  array  $parsed_output    The haystack from which to search     
   *   @param  array  $onetcode_arr     A list of options specifying the onetcode to search for
   *      
   *  @return array  $matches       Represents an array of matching jobs with their details, including
   *                                 global details necessary for db insert later on..                 
   */      
   public function filterJobsOnetCode( $parsed_output, $onetcode_arr){
    $matches = array();
    foreach($parsed_output as $k1 => $v1){
     if( ($k1 != 'Results') && ($k1 != 'SearchMetaData') )
     {
      $matches[$k1] = $v1;
     }
     if($k1 == 'Results')
     {
      $container_arr = $v1['JobSearchResult'];
      for($i=0; $i<count($container_arr); ++$i){
       if( in_array($container_arr[$i]['OnetCode'], $onetcode_arr) )
       {
        $matches[] = $container_arr[$i];
       }
      } //end for..
     }
     if($k1 == 'SearchMetaData')
     {
      $metadata_container_arr = $v1['SearchLocations']['SearchLocation'];
      foreach( $metadata_container_arr as $k2 => $v2 ){
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
   *             for each job listed in $jobdid_arr).            
   *   @param string  $key         The developer key provided
   *   @param array   $jobdid_arr  An array consisting of generic job details
   *   
   *  @return  An array of SimpleXmlElement Objects  (each object has drill-down job details)       
   */      
   public function getJobDetails( $key, $jobdid_arr ) {
    if( (is_array($jobdid_arr)) && (is_string($key)) )
    {
     $temp_arr = array();
     foreach($jobdid_arr as $k1 => $v1)
     {
      if( is_numeric($k1) )
      {
       foreach($v1 as $k2 => $v2){
        if($k2 == 'DID')
        {
         $url = "http://api.careerbuilder.com/v1/job?DeveloperKey=$key&DID=$v2";
         $xml = simplexml_load_file($url); 
        
         try{
	 		    $xml = simplexml_load_file($url);
	 	     }catch(Exception $e){
	 		    print_r($e);
	 	     }
    
         $temp_arr[$k1] = $xml;
        }
       }
      }
     } //end top foreach..
     return $temp_arr;  //An array of objects..
    }
    
   } //end getJobDetails()..
   
   
   /*
   *  Method  performFinalMerge()
   *   @Purpose:
   *    @param  array  $jobDetails_arr          Represents the array to pluck specific
   *                                            information from            (pluck from)   
   *    @param  array  $onetcode_matches_arr    The existing array to stuff (turkey)
   *    @param  array  $specificJobDetails_arr  The specific information we wish to pluck..    
   *       
   *   @return  array  $onetcode_matches_arr  An updated, more specific-like version of
   *                                          the original argument                 
   */      
   public function performFinalMerge( $jobDetails_arr, $onetcode_matches_arr, $specificJobDetails_arr ){
    for( $i=0; $i<count($jobDetails_arr); ++$i )
    {
     foreach($jobDetails_arr[$i] as $k1 => $v1){
      if($k1 == 'Job')
      {
       foreach($v1 as $k2 => $v2){
        for( $j=0; $j<count($specificJobDetails_arr); ++$j )
        {
         if( in_array($k2, $specificJobDetails_arr) )
         {
          $onetcode_matches_arr[$i][$k2] = $v2;
         }
        }
       } 
      }  //end if Job..
     }  //end top  foreach..
    }
    return $onetcode_matches_arr;
   }
   
  } //end Cbapi class..
  
  
   /*
   //Testing the public api..
  $db_columns = array(); 
  $cbapi_A = new Cbapi();
   $_key = 'WDTZ14P67NZKL453DBTN';
   $_keyword = 'php';
   $_location = 'san diego';
   $_since_arr = array(1,3,7,30);
   $_specificJobDetails_arr = array("BlankApplicationServiceURL", "LocationCity", "RelocationCovered");
   $rawResults = $cbapi_A->getKeyKeywordLocationSinceRaw( $_key, $_keyword, $_location, $_since_arr[2] ); 
                       
   $parsed_output = $cbapi_A->mapToArray($rawResults);
    
   $onetcode_arr = array('15-1099.04', '15-1031.00');
   $specified_arr = $cbapi_A->filterJobsOnetCode( $parsed_output, $onetcode_arr);  
   //print_r($specified_arr);
   
   
   $jobDetails_container = $cbapi_A->getJobDetails( $_key, $specified_arr ); 
    $jobDetails_arr = $cbapi_A->mapToArray( $jobDetails_container );   
    //print_r($jobDetails_arr);
  
  
   //Now combine the $jobDetails_arr with our $specified array..
    $onetcode_matches_arr = $cbapi_A->performFinalMerge( $jobDetails_arr, $specified_arr, $_specificJobDetails_arr );
     print_r($onetcode_matches_arr);   
    */
    
   
?>