<?php
/**********************************************************************************/


				/**
				*
				* @file Controller for Header-PageElement (include/pageElements/header.php)
				* @author Martin Goller <mail.mgoller@gmail.com>
				* @copyright Martin Goller 2019
				*
				*/


/**************************************************************************************************/


				/***********************************/
				/********** CONFIGURATION **********/
				/***********************************/

				// include(file path): If an error occurs, the script will continue to run. Problem with double inclusion of the same file
				// require(filepath): If an error occurs, the script is stopped. Problem with double inclusion of the same file
				// include_once(filepath): If an error occurs, the script will be executed further. No problem with double inclusion of the same file.
				// require_once(filepath): If an error occurs, the script is stopped. No problem with double inclusion of the same file.

				require_once("include/classLoader.inc.php");
				require_once("include/config.inc.php");
				require_once("include/dateTime.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");

				/********** INCLUDE TRAITS **********/
				require_once("traits/autoConstruct.trait.php");

				/********** INCLUDE CLASSLOADER **********/
				spl_autoload_register("autoloadClasses");


/**************************************************************************************************/



				/******************************************/
				/********** INITIALIZE VARIABLES **********/
				/******************************************/


				$loginMessage	= NULL;


				/********** ESTABLISH DB CONNECTION **********/
				// if not already established by another controller
				if(!$pdo) $pdo				= dbConnect();


/**************************************************************************************************/


				/********************************************/
				/********** PROCESS URL PARAMETERS **********/
				/********************************************/

				// Step 1 URL: Check if parameters were transferred
				if( isset($_GET['action']) ) {

					// Step 2 URL: Read values, defuse, DEBUG output
					$action = cleanString($_GET['action']);

					// Step 3 URL: Branch if necessary

					/********** LOGOUT **********/
					if( $_GET['action'] == "logout" ) {
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: 'Logout' (This message should not be visible if the forwarding works.) <i>(" . basename(__FILE__) . ")</i></p>";

						session_destroy();
						header("Location: index.php#pageHead");
						exit();

					}

				} // PROCESS URL PARAMETERS END


/**************************************************************************************************/


				/**********************************/
				/********** PROCESS FORM **********/
				/**********************************/

				/*
if(DEBUG)	echo "<pre class='debug'>\r\n";
if(DEBUG)	print_r($_POST);
if(DEBUG)	echo "</pre>\r\n";
				*/

				/********** LOGIN **********/

				// Step 1 FORM: Check whether form was sent or not
				if( isset($_POST['formsentLogin']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Form:'Login' was sent... <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 2 FORM: Read values, defuse, DEBUG output
					// Creating a new User-Object with the User Input Email
					$loginUser = new User(['usr_email'=>$_POST['loginName']]);

					// Cache user input into $loginPassword for later comparison
					$loginPassword			=cleanString($_POST['loginPassword']);

					// Step 3 FORM: Validate values if necessary
					$errorLoginName 		= checkEmail($loginUser->getUsr_email());
					$errorLoginPassword 	= checkInputString($loginPassword,4);

					/********** FINAL FORM CHECK **********/
					if( $errorLoginName OR $errorLoginPassword ) {
						// Error case
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: The form still contains errors! <i>(" . basename(__FILE__) . ")</i></p>";
						$loginMessage = "Benutzername oder Passwort falsch!";

					} else {
						// Success case
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Form is formally error-free and is now being processed... <i>(" . basename(__FILE__) . ")</i></p>";

						// Step 4 FORM: Further processing of data


						/********** FETCH USERDATA FROM DB **********/


						/********** VERIFY LOGIN *********/
						// Check whether a data record was delivered or not
						// If a record was delivered, the email address must be correct

						if( !$loginUser->getFromDb($pdo) ) {
							// Error case:
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: ERROR: Username not found in DB! <i>(" . basename(__FILE__) . ")</i></p>";
							$loginMessage = "Benutzername oder Passwort falsch!";

						} else {
							// Success case
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Username was found in DB. <i>(" . basename(__FILE__) . ")</i></p>";

							/********** VERIFY PASSWORD **********/

							if( !password_verify( $loginPassword,$loginUser->getUsr_password()) ) {
								// Error case
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: ERROR: Password does not match PW in DB! <i>(" . basename(__FILE__) . ")</i></p>";
								$loginMessage = "Benutzername oder Passwort falsch!";

							} else {
								// Success case
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Password matches DB entry. LOGIN OK. <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)					echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Writing user data into the session... <i>(" . basename(__FILE__) . ")</i></p>";

								/********** WRITE USEROBJECT INTO SESSION **********/
								session_name("blogProject");
								session_start();

								// Write USER OBJECT into the SESSION
								// When reading, the classes must exist before reading.
								$_SESSION['userObject']		= clone $loginUser;

								/*
if(DEBUG)					echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
if(DEBUG)					print_r($_SESSION);
if(DEBUG)					echo "</pre>";
								*/

								/********** REDIRECT TO DASHBOARD **********/

								header("Location: dashboard.php#pageHead");

							} // VERIFY PASSWORD END

						} // VERIFY LOGIN END

					} // FINAL FORM CHECK END

				} // PROCESS FORM END


/***************************************************************************************/

?>
