<?php
/******************************************************************************************************/


				/**
				*
				*	Establishes a connection to a database via PDO
				*
				*	@param [String $dbname		Name of the database to be connected with]
				*
				*	@return Object					DB-Connection Object
				*
				*/
				function dbConnect($dbname=DB_NAME) {
if(DEBUG_DB)	echo "<p class='debugDb'><b>Line " . __LINE__ . ":</b> Connecting to <b>$dbname</b> ... <i>(" . basename(__FILE__) . ")</i></p>\r\n";					
					
					// EXCEPTION-HANDLING
					// Try, to connect with the Database
					try {
						// throws an error message into the void if it fails
						
						// $pdo = new PDO("mysql:host=localhost; dbname=market; charset=utf8mb4", "root", "");
						$pdo = new PDO(DB_SYSTEM . ":host=" . DB_HOST . "; dbname=$dbname; charset=utf8mb4", DB_USER, DB_PWD);
					
					// if an error message was thrown, it is caught here					
					} catch(PDOException $error) {
						// Error output
if(DEBUG_DB)		echo "<p class='error'><b>Line " . __LINE__ . ":</b> <i>ERROR: " . $error->GetMessage() . " </i> <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						// exit Script
						exit;
					}
					// If the script was not aborted (no error), continue here
if(DEBUG_DB)	echo "<p class='debugDb ok'><b>Line " . __LINE__ . ":</b> Successfully established a connection to <b>$dbname</b>. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					// return a DB-Connection Object
					return $pdo;
				}
				
				
/******************************************************************************************************/
?>