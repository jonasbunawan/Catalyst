<?php
// Statement to hide unnecessary MySQL Error Message. If System Error Message is preferred to be displayed, simply comment out the statement by placing // at the front of statement just like this comment line.
error_reporting(0);

	// Command Line Directives in short form
	$shortcmdirective = "u:";
	$shortcmdirective .= "p:";
	$shortcmdirective .= "h:";
	
	// Command Line Directives in complete form
	$longcmdirective = array(
		"file:",
		"create_table",
		"dry_run",
		"help",
	);
	
	// Declaring $count variable to count number of rows processed, $user variable to store users' data onto array, & $rows variable to count number of affected rows
	$count = 0;
	$rows = 0;
	$user = array(array());
	
	// Storing Value of Command Line Directives by using getopt() function for both short and complete options.
	$options = getopt($shortcmdirective, $longcmdirective);
	
	// First if conditional statement contains function to display help message when --help is called within application execution.
	if(isset($options['help'])) {
		echo "\n" . "Please find below for details of available command line directives that are built within this application (case sensitive)" . "\n"; 
		echo "\n" . "-u : MySQL username" . "\n";
		echo "-p : MySQL password" . "\n";
		echo "-h : MySQL host" . "\n";
		echo "--file csvfilename : this is the name of the CSV to be parsed" . "\n";
		echo "--create_table : this will cause the MySQL users table to be built (and no further action will be taken)" . "\n";
		echo "--dry_run : this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered." . "\n";
		echo "--help : which will output the above list of directives with details." . "\n";
	} else {
		if(isset($options['u']) and isset($options['p']) and isset($options['h'])) {
			$connection = new mysqli($options['h'], $options['u'], $options['p']);
		
			if($connection -> connect_error){
				die("\n" . "Connection failed: " . $connection -> connect_error . "\n");
			} else {
				echo "\n" . "Successfully Connected" . "\n";
				
				$DB_selection = mysqli_select_db( $connection,'Catalyst');
				
				if(!$DB_selection) {
					$SQL = 'CREATE DATABASE Catalyst';
					
					if(mysqli_query($connection, $SQL)) {
						$SQL = "GRANT ALL ON Catalyst.* TO" . $options['u'] . "@" . $options['h'];
						$DB_selection = mysqli_select_db($connection, 'Catalyst');
						echo "\n" . "Catalyst Database was created successfully and is used" . "\n";
					} else {
						echo "\n" . "Error creating database: " . mysqli_error($connection) . "\n";
					}
				}else {
					echo "\n" . "Catalyst Database is currently being used" . "\n";
				}
			}
			
			if(isset($options['create_table'])) {
				$result = mysqli_query($connection, "SHOW TABLES LIKE 'USERS'");
				
				$SQL = "CREATE TABLE IF NOT EXISTS Users (
						name VARCHAR(30) NOT NULL,
						surname VARCHAR(30),
						email	VARCHAR(50) UNIQUE
						)";
				
				if(mysqli_num_rows($result) > 0) {
					mysqli_query($connection, "DROP TABLE Users");
					echo "\n" . "Users table exists and will be rebuilt shortly" . "\n";
				}
				
				if($connection->query($SQL) == TRUE) {
					echo "\n" . "Table Users was created successfully" . "\n";
				} else {
					die( "\n" . "Error creating table: " . $connection->error . "\n");
				}
			}

			if(isset($options['file'])){
				if(file_exists($options['file'])){
					$fp = fopen($options['file'], "r");
					$fw = fopen("php://stdout", "w");
					$result = mysqli_query($connection, "SHOW TABLES LIKE 'Users'");
					
					// Skipping csv file Header. Simply comment out below fgetcsv() function if the imported file does not have any header.
					fgetcsv($fp);
					
					while($data = fgetcsv($fp,1000,",")){
						if($data[0] and !filter_var($data[2], FILTER_VALIDATE_EMAIL) === false) {

							$user[$count]['name'] = trim(ucfirst(strtolower($data[0])));
							$user[$count]['surname'] = trim(ucfirst(strtolower($data[1])));
							$user[$count]['e-mail address'] = trim(ucfirst(strtolower($data[2])));
							
							$count ++;
						} elseif (!filter_var($data[2], FILTER_VALIDATE_EMAIL) === true){
							fprintf($fw, "\n" . $data[2] . " is in invalid e-mail format" . "\n");
						}
					}
					
					fclose($fp);
					fclose($fw);
					
					if (mysqli_num_rows($result) > 0) {
						if(!isset($options['dry_run'])){
							
							foreach($user as $value){
								if($connection->query("INSERT INTO Users (name, surname, email) VALUES
										(
											'".addslashes($value['name'])."',
											'".addslashes($value['surname'])."',
											'".addslashes($value['e-mail address'])."'
										)
									") == TRUE){
								
									$rows ++;
								}else{
									echo("\n" . "Data Insertion Failed: " . $connection->error . "\n");
								}
							}
							
						}else{
							echo "\n" . "Application is running in dry run mode. There is no data that will be imported to database" . "\n";
						}
					}else{
						echo "\n" . "Users Table does not exist, please refer to --help function on how to create the table. Thanks" . "\n";
					}
					
				}else{
					die("\n" . "File is invalid" . "\n");
				}
				echo "\n" . $rows . " row(s) have been succesfully inserted to database" . "\n";
			}
			
			$connection->close();
		}else {
			echo "\n" . "Please define MySQL database Host, Username, and Password. For more information on how to specify them please use pre-user-defined CLI --help function that is built within this application. Thank you" . "\n";
		}
	}
	
?>
