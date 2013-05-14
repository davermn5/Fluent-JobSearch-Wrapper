<?php
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
   *
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
   *  
   *   @param  SimpleXmlElement Object  $rawResults  The object we wish to parse   
   *
   *  @return array  $parsed_arr  The arrayified format that is returned        
   *
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
   *  @return array  $matches       Represents an array of matching jobs with their
   *                                 relevant details               
   *
   */      
   public function filterJobsOnetCode( $parsed_output, $onetcode_arr){
    $matches = array();
    $container_arr = $parsed_output['Results']['JobSearchResult'];
    for($i=0; $i<count($container_arr); ++$i){
     if( in_array($container_arr[$i]['OnetCode'], $onetcode_arr) )
     {
      $matches[] = $container_arr[$i];
     }
    } //end for..
    return $matches;  
   } //filterJobsOnetCode()..
   
  } //end Cbapi class..
  
   //Testing the public api..
   /*
  $cbapi_A = new Cbapi();
   $_key = 'WDTZ14P67NZKL453DBTN';
   $_keyword = 'php';
   $_location = 'san diego';
   $_since_arr = array(1,3,7,30);
   $rawResults = $cbapi_A->getKeyKeywordLocationSinceRaw( $_key, $_keyword, $_location, $_since_arr[2] ); 
   
    $temp_arr = array();                    
    $parsed_output = $cbapi_A->mapToArray($rawResults);
    
    $onetcode_arr = array('15-1099.04', '15-1031.00');
    $matches = array();
   $specified = $cbapi_A->filterJobsOnetCode( $parsed_output, $onetcode_arr);
   print_r($specified);
   */
  
?>
