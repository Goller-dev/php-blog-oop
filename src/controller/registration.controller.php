<?php
/**********************************************************************************/


				/**
				*
				* @file Controller for Start-Page (index.php)
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


				/***********************************/
				/******** OUTPUT BUFFERING *********/
				/***********************************/

				/*
				ob_start() - Enable output buffering:

				If you have problems with the error message "headers already sent...",
				a buffering of the header dispatch helps. This will not send the
				header until the PHP script finds an explicit instruction, e.g. ob_end_flush().

				This function ob_start() activates the output buffering.
				While output buffering is active, script output (with the exception
				of header information) is not passed directly to the client,
				but collected in an internal buffer.

				When using the output buffering, you have to be aware that by using
				ob_flush() etc. you can mess up the workflow of the code
				(especially with nested buffering). You have to know exactly what you are doing.
				It is safer not to send the buffer manually. When the script is finished,
				the buffer content is automatically sent and emptied so that
				the order of the buffered output is preserved.
				*/
				if( !ob_start() ) {
					// Error case
if(DEBUG) 		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR while activating AUTO BUFFERING! <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				} else {
				// Success case
if(DEBUG) 		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Output Buffering is activated. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
				}


/**************************************************************************************************/

				/***** INITIALIZE SESSION *****/

				// the session is only initialized here.
				// SITE PROTECTION is only needed for the backend.

				session_name("blogProject");
				session_start();

				if( !isset($_SESSION['userObject']) ) {
					session_destroy();
					// Write 'userObject-Data' from session to new 'User-Object'
				} elseif ($user = clone $_SESSION['userObject'] ) {
					// if $user is instance of Class User: redirect to dashboard
					if($user instanceof User ) {
						// valid userObject is in Session: write Messeage into Session and redirect to dashboard
						// save 'permission denied' Message in Session:
						$_SESSION['permission_denied'] = $user->getFullname()." ist bereits registriert und angemeldet." ;
						// redirect to dashboard.php?permission_denied
						header("Location: dashboard.php?action=showCategory&permission_denied");

					} // redirect to dashboard END

				} else {
if(DEBUG) 		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR while creating User with \$_SESSION['userObject'].  <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				}



/**************************************************************************************************/


				/******************************************/
				/********** INITIALIZE VARIABLES **********/
				/******************************************/


				// 'User-Object' in $regUser for the registration process
				$regUser = new User();

				$loginMessage	= NULL;

				$passwordCheck	= NULL;

				$errorFirstname = NULL;
				$errorLastname	 = NULL;
				$errorEmail		 = NULL;
				$errorCity		 = NULL;
				$errorPassword	 = NULL;


				/********** ESTABLISH DB CONNECTION **********/

				$pdo				= dbConnect();


/**************************************************************************************************/


				/**********************************/
				/********** PROCESS FORM **********/
				/**********************************/


if(DEBUG)	echo "<pre class='debug'>\r\n";
if(DEBUG)	print_r($_POST);
if(DEBUG)	echo "</pre>\r\n";


				/********** REGISTRATION **********/

				// Step 1 FORM: Check whether form was sent or not
				if( isset($_POST['formsentRegistration']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Form:'Registration' was sent... <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 2 FORM: Read values, defuse, DEBUG output
					// Creating a new User-Object with the User Input Email
					$regUser = new User($_POST);

					// Cache user input into $passwordCheck
					$passwordCheck = cleanString($_POST['usr_passwordcheck']);
if(DEBUG)		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>:\$passwordCheck: '".$passwordCheck."'  <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 3 FORM: Validate values if necessary

					$errorFirstname = checkInputString( $regUser->getUsr_firstname() );
					$errorLastname	 = checkInputString( $regUser->getUsr_lastname() );
					$errorEmail		 = checkEmail( $regUser->getUsr_email() );



					// *** PASSWORD VALIDATION I *** compare password in User Object with $passwordCheck
					if( $passwordCheck != $regUser->getUsr_password() ) {
						// error Passwords don't match
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Passwords don't match! <i>(" . basename(__FILE__) . ")</i></p>";
						$errorPassword = "Passwort und Bestätigung sind nicht identisch.";

					// ** PASSWORD VALIDATION II ***
					// check if password is at least 6 characters long
					}elseif( $errorPassword	 = checkInputString( $regUser->getUsr_password(),6 )  ) {
						// error Password is to short
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Password is to short! <i>(" . basename(__FILE__) . ")</i></p>";
					} else {

						/********** FINAL FORM CHECK **********/
						if( $errorFirstname OR $errorLastname OR $errorEmail OR $errorPassword ) {
							// error case
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Form still contains errors! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							// success case
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Form is formally free of errors, processing Registration Form.... <i>(" . basename(__FILE__) . ")</i></p>";

							// Step 4 FORM: Further processing of data
							// check if the email already exists
							if( $regUser->checkIfEmailExistsInDb($pdo) ) {
								// error case
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Email already exists in DB. <i>(" . basename(__FILE__) . ")</i></p>";
								$errorEmail = "Diese Email-Adresse wurde bereits registriert.";

							} else {
								/********** SAVE NEW USER TO DB **********/
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Saving User '".$regUser->getFullname()."' to DB... <i>(" . basename(__FILE__) . ")</i></p>";

								if( !$regUser->saveToDb($pdo) ) {
if(DEBUG)						echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Error while saving User '".$regUser->getFullname()."' to DB. <i>(" . basename(__FILE__) . ")</i></p>";

								} else {
if(DEBUG)						echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Sucessfully saved User '".$regUser->getFullname()."' to DB. <i>(" . basename(__FILE__) . ")</i></p>";

									// saveToDb: reads lastinsert ID, writes it into the calling Object

									// generate a new Role Object with rol_id="2" AND rol_name="Benutzer"
									$regUser->setRole( new Role( [ 'rol_id' => "2", 'rol_name' => "Benutzer" ] ) );

									/********** WRITE USERDATA IN SESSION **********/
									session_start();
									// Write USER OBJECT into the SESSION
									// When reading, the classes must exist before reading.
									$_SESSION['userObject']		= clone $regUser;

									/*
if(DEBUG)						echo "<pre class='debug'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
if(DEBUG)						print_r($_SESSION);
if(DEBUG)						echo "</pre>";
									*/

									/********** REDIRECT TO DASHBOARD **********/

									header("Location: dashboard.php#pageHead");
									} // SAVE NEW USER TO DB END

								} // Step 4 FORM END

							} // FINAL FORM CHECK END

					} // PASSWORD VALIDATION I END

				} // PROCESS FORM END


/***************************************************************************************/

?>
