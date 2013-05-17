<?php
 //db_conn_mysqli.php
 
  class dbConnectionMySQLi{
  
   public $_mysqli = null;
<<<<<<< HEAD
   private $_token  = "";
=======
   private $_token  = "jJ!1910jJ!1910";
>>>>>>> dd3ecf2d70d93a4844eb6c50e5677de75c205c3d
  
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
