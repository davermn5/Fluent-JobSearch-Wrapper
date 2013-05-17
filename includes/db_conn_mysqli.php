<?php
 //db_conn_mysqli.php
 
  class dbConnectionMySQLi{
  
   public $_mysqli = null;
   private $_token  = "";
  
   public function __construct(){
    $mysqli = new mysqli('localhost', 'root', $this->_token, 'crawler');
    if( mysqli_connect_error() ){
     die( 'Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error() );
    }
    else{
     $this->_mysqli = $mysqli;
    }
     
   }
   
  } //end dbConnection class..
  
 

  //Go ahead and place the  $mysqli->close();  inside of the actual main code..

?>
