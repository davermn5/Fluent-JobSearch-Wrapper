<?php
	$db_server = "localhost";   //server name
	$db_user = "";              //user name
	$db_password = "";          //user password
	$db_database = "";          //database name


	//Creating a database connection
	if (($db_conn = mysql_connect($db_server, $db_user, $db_password)) === FALSE) {
		die("Database connection failed:" . mysql_error());
	}

	// Select a database to use
	if (($db_select = mysql_select_db($db_database, $db_conn)) === FALSE) {
		die("Database selection failed:" . mysql_error());
	}
