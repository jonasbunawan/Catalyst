<?php
	$shortcmdirective = "u:";
	$shortcmdirective .= "p:";
	$shortcmdirective .= "h:";
	
	$longcmdirective = array(
		"file:",
		"create_table",
		"dry_run",
		"help",
	);
	
	$options = getopt($shortcmdirective, $longcmdirective);
	
	if(isset($options['help'])) {
		echo "\n" . "-u : MySQL username" . "\n";
		echo "-p : MySQL password" . "\n";
		echo "-h : MySQL host" . "\n";
		echo "--file [csv file name] : this is the name of the CSV to be parsed" . "\n";
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
						echo "\n" . "Database Catalyst was created successfully and is used" . "\n";
					} else {
						echo "\n" . "Error creating database: " . mysqli_error($connection) . "\n";
					}
				}
			}
			
			if(isset($options['create_table'])) {
				
				$SQL = "CREATE TABLE IF NOT EXISTS Users (
						name VARCHAR(30) NOT NULL,
						surname VARCHAR(30),
						email	VARCHAR(50) UNIQUE
						)";
				
				if($connection->query($SQL) == TRUE) {
					echo "\n" . "Table Users was created successfully" . "\n";
				} else {
					echo "\n" . "Error creating table: " . $connection->error . "\n";
				}
			}

			if(isset($options['file'])){
				if(file_exists($options['file'])){
					$fp = fopen($options['file'], "r");
					$fw = fopen("php://stdout", "w");
					$result = mysqli_query($connection, "SHOW TABLES LIKE 'Users'");
					
					if(!isset($options['dry_run'])){
						if (mysqli_num_rows($result) > 0) {
							while($data = fgetcsv($fp,1000,",")){
								if($data[0] and (!filter_var($data[2], FILTER_VALIDATE_EMAIL)) === false ) {

									$connection->query("INSERT INTO Users (name, surname, email) VALUES
										(
											'".addslashes(ucfirst(strtolower($data[0])))."',
											'".addslashes(ucfirst(strtolower($data[1])))."',
											'".addslashes(strtolower($data[2]))."'
										)
									");
								} elseif (!filter_var($data[2], FILTER_VALIDATE_EMAIL === true)){
									fprintf($fw, "\n" . $data[2] . " is in invalid e-mail format" . "\n");
								}
							}
						}else{
							echo "\n" . "Users Table does not exist, please refer to --help function on how to create the table. Thanks" . "\n";
						}
					}
					fclose($fp);
					fclose($fw);
				}else{
					die("\n" . "File is invalid" . "\n");
				}
			}
			
			$connection->close();
		}else {
			echo "\n" . "Please define MySQL database Host, Username, and Password. For more information on how to specify them please use pre-user-defined CLI --help function that is built within this application. Thank you" . "\n";
		}
	}
?>
