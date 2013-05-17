<?php

 //db_conn.php
 $db_server="localhost";  //server name
 $db_user="";		// user name
 $db_password="";		//	user password
 $db_database="";	// database name
 $db_user="root";		// user name
 $db_password="xxxxxxx";		//	user password
 $db_database="xxxxxxx";	// database name


 //Creating a data base connection
 if( ($db_conn = mysql_connect($db_server, $db_user, $db_password)) === FALSE){
  die( "Database connection failed:" . mysql_error() );
 }
 /*
 elseif( ($db_conn = mysql_connect($db_server, $db_user, $db_password)) !== FALSE ){
  echo 'successful db connection.</br>';
 }
 */
 
	// Select a database to use
 if( ($db_select = mysql_select_db($db_database, $db_conn) ) === FALSE ){
  die("Database selection failed:" . mysql_error() );
 }
 /*
 elseif( ($db_select = mysql_select_db($db_database, $db_conn) ) !== FALSE ){
  echo 'sucessful db selection.</br>';
 }
 */

?>
