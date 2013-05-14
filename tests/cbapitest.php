<?php
 require_once('../cbapi.php');
 
 class CbapiTest extends PHPUnit_Framework_TestCase{
  protected $_cbapi = null;
  protected $_key = 'WDTZ14P67NZKL453DBTN';
  protected $_keyword = 'php';
  protected $_location = 'san diego';
  protected $_since_arr = array(1,3,7,30);
  protected $_onetcode_arr = array('15-1099.04', '15-1031.00');
  
  public function __construct(){}
  
  public function setUp(){
   $this->_cbapi = new cbapi();
  }
  
  public function tearDown(){
   unset($this->_cbapi); 
  }
  
  public function testGetKeyKeywordLocationSinceRaw(){
   //Test our inputs are proper before we invoke logic..
   $this->assertInternalType( 'string', urlencode($this->_key) );
   $this->assertInternalType( 'string', urlencode($this->_keyword) ) ;
   $this->assertInternalType( 'string', urlencode($this->_location) );
   $this->assertContainsOnly( 'int', $this->_since_arr );
   
   //Invoke the call..
   for($i=0; $i<count($this->_since_arr); ++$i)
   {
    $rawResults = $this->_cbapi->getKeyKeywordLocationSinceRaw($this->_key, $this->_keyword, $this->_location, $this->_since_arr[$i]);
    $this->assertInternalType('object', $rawResults);
   }
   return $rawResults;
  } //testGetKeyKeywordLocationSinceRaw
  
  
  /**
   *  @depends testGetKeyKeywordLocationSinceRaw
   */
   public function testMapToArray( SimpleXMLElement $rawResults ){
    $this->assertInternalType('object', $rawResults);
     $parsed_output = $this->_cbapi->mapToArray( $rawResults );
    $this->assertInternalType('array', $parsed_output);
    
    return $parsed_output;
   }
   
   
   /**
    *  @depends testMapToArray
    */       
    public function testFilterJobsOnetCode( array $parsed_output ){
     $this->assertInternalType('array', $parsed_output);
      $this->assertInternalType('array', $this->_onetcode_arr);
      $onetcode_matches_arr = $this->_cbapi->filterJobsOnetCode( $parsed_output, $this->_onetcode_arr );
      $this->assertInternalType('array', $onetcode_matches_arr);
    }
  
 }
?>
