<?php

	/**
	 *  Restores the intended mysql database using (.sql) files one-by-one.
	 *  The default location of these files is in the 'sql' directory.
	 *
	 * @examples
	 *  i.) php loader.php
	 *   or
	 *  ii.) open a browser and navigate to 'this_path/loader.php'
	 *
	 * @Verify_It_Worked
	 *  i.) On the virtual machine, type in the following commands:
	 *      mysql -uroot -proot
	 *      show databases;
	 */

	/**
	 * Setup the default configuration prior to the sql build
	 */
	$config = array(
		'db_user' => "root",
		'db_pass' => "root",
		'script_path' => "sql"
	);

	$script_path = $config['script_path'];
	$sqlListArr = array_diff(scandir($script_path), array('..', '.'));

	/**
	 * Build the (.sql) scripts one-by-one
	 */
	foreach($sqlListArr as $file_name){
		$ext = pathinfo("{$script_path}/{$file_name}", PATHINFO_EXTENSION);
		switch($ext){
			case 'sql':
				$command = "mysql -u{$config['db_user']} -p{$config['db_pass']} < {$script_path}";
				shell_exec($command . "/{$file_name}");
			break;
		}
	}