<?php
/*******************************************************************************************/


				/********************************/
				/********** CLASS USER **********/
				/********************************/

				/*
					The class is the blueprint/template for all objects created from it.
					It specifies the properties/attributes of a later object (variables) as well as
					the "actions" (methods/functions) that the later object can perform.

					Each object of a class is structured according to the same scheme (same properties and methods),
					usually has different values (variable values).
				*/


/*******************************************************************************************/


				/**
				*
				*	Class represents a User-(Account)
				*
				*
				*/
				class User {

					/********************************/
					/********** ATTRIBUTES **********/
					/********************************/

					// Attributes do not have to be initialized within the class definition.

					private $usr_id;
					private $usr_firstname;
					private $usr_lastname;
					private $usr_email;
					private $usr_city;
					private $usr_password;
					// $role is an object of the class Role
					private $role;

					/***********************************************************/


					/*********************************/
					/********** CONSTRUCTOR **********/
					/*********************************/

					/*
						If you want to assign attribute values to an object when you create it,
						you must write your own constructor. This constructor takes the values in
						form of parameters (just like with functions) and calls in its turn
						to assign the values to the corresponding setters.
					*/

					/*
						With the command 'use' the trait 'autoConstruct' is included and behaves
						as if its contents were noted directly in the class including it.
						(filepath: traits/autoConstruct.trait.php)
					*/
					use autoConstruct;



					/***********************************************************/


					/*************************************/
					/********** GETTER & SETTER **********/
					/*************************************/

					/********** USR_ID **********/
					public function getUsr_id() {
						return $this->usr_id;
					}
					public function setUsr_id($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: usr_id: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->usr_id = cleanString($value);
						}
					}

					/********** USR_FIRSTNAME **********/
					public function getUsr_firstname() {
						return $this->usr_firstname;
					}
					public function setUsr_firstname($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: usr_firstname: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->usr_firstname = cleanString($value);
						}
					}

					/********** USR_LASTNAME **********/
					public function getUsr_lastname() {
						return $this->usr_lastname;
					}
					public function setUsr_lastname($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: usr_lastname: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->usr_lastname = cleanString($value);
						}
					}

					/********** USR_EMAIL **********/
					public function getUsr_email() {
						return $this->usr_email;
					}
					public function setUsr_email($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: usr_email: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->usr_email = cleanString($value);
						}
					}

					/********** USR_CITY **********/
					public function getUsr_city() {
						return $this->usr_city;
					}
					public function setUsr_city($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: usr_city: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->usr_city = cleanString($value);
						}
					}

					/********** USR_PASSWORD **********/
					public function getUsr_password() {
						return $this->usr_password;
					}
					public function setUsr_password($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: usr_password: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->usr_password = cleanString($value);
						}
					}

					/********** ROLE **********/
					public function getRole() {
						return $this->role;
					}
					public function setRole($value) {
						// Check if an object of class 'Role' has been passed
						if( !$value instanceof Role ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: role: MUST BE AN INSTANCE OF CLASS 'Role'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->role = ($value);
						}
					}



					/*************************************/
					/********* VIRTUAL ATTRIBUTES ********/
					/*************************************/

					/********* GET USERFULLNAME **********/
					public function getFullname() {
						// Virtual Attribute for  'Firstname, Lastname'
						return $this->getUsr_firstname() ." ". $this->getUsr_lastname();
					}

					/********* GET ROLENAME **********/
					public function getRoleName() {
						// delegation into Role->getRol_name()
						return $this->getRole()->getRol_name();
					}


					/***********************************************************/


					/*****************************/
					/********** METHODS **********/
					/*****************************/


					/********** FETCH USER DATA FROM DB **********/
					/**
					*
					*	Fetches user object data from DB by email
					*	Writes object data into corresponding object
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if dataset was found, else false
					*
					*/
					public function getFromDb($pdo) {
if(DEBUG_C)			echo "<p class='debug'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "( '".$this->getUsr_email()."' ) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						if(	!$this->getUsr_email()	){
							// Error case, aufrufendes Objekt enthält keine Email
if(DEBUG_C)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: Das aufrufende Objekt muss eine E-Mail Adress enthalten ! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							return false;

						} else {
							$sql 		= 	"SELECT * FROM user
											INNER JOIN role USING(rol_id)
											WHERE usr_email = ?	";

							$params 	= array( $this->getUsr_email() );

							// Step 2 DB: Prepare SQL Statement
							$statement = $pdo->prepare($sql);

							// Step 3 DB: Execute SQL statement and fill placeholder if necessary
							$statement->execute($params);
if(DEBUG_C)				if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

							// bei lesendem Zugriff: prüfen, ob Datensatz gefunden wurde.
							if( !$row = $statement->fetch() ) {
								// Error case:
if(DEBUG_C)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: FEHLER: Benutzername wurde nicht in DB gefunden! <i>(" . basename(__FILE__) . ")</i></p>";
								return false;

							} else {
								// Success case
if(DEBUG_C)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Benutzername wurde in DB gefunden. <i>(" . basename(__FILE__) . ")</i></p>";

								$this->setUsr_id(	$row['usr_id']	);
								$this->setUsr_firstname(	$row['usr_firstname']	);
								$this->setUsr_lastname(	$row['usr_lastname']	);
								$this->setUsr_city(	$row['usr_city']	);
								$this->setUsr_password(	$row['usr_password']	);

								$userRole = new Role($row);

								$this->setRole($userRole);

								return true;

							}
						}
					} // FETCH USER DATA FROM DB END

					/***********************************************************/


					/*****************************************************/
					/************ CHECK IF EMAIL EXISTS IN DB ************/
					/**
					*
					*	Checks if the email of the calling User Object already exists in the DB
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if dataset was found, else false
					*
					*/
					public function checkIfEmailExistsInDb($pdo) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "(\$pdo) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						// check if the calling object has included a usr_email or not
						if(!$this->getUsr_email()){
							// Error case
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: ERROR: Calling Object contains no 'usr_email' <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						} else {
							// Success case
if(DEBUG_C)				echo "<p class='debugClass hint'><b>Line " . __LINE__ . "</b>: Checking: Does Email ".$this->getUsr_email()." already exist in DB... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

							// DB query: chek if 'Category' with cat_name already exists in DB
							$sql = "SELECT COUNT(*) FROM user WHERE usr_email = ?";
							$params = array( $this->getUsr_email() );

if(DEBUG_C)				echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: SQL: " . $sql . " <i>(" . basename(__FILE__) . ")</i></p>";

							// Step 2 DB: Prepare SQL Statement
							$statement = $pdo->prepare($sql);
							// Step 3 DB: Execute SQL statement and fill placeholder if necessary
							$statement->execute($params);
if(DEBUG_C)				if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

							if(	$statement->fetchColumn()	) {
if(DEBUG_C)					echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: EMAIL ".$this->getUsr_email()." exists in DB. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								// Email already exists in DB
								return true;
							} else {
								// Email was not found in DB
if(DEBUG_C)					echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: EMAIL ".$this->getUsr_email()." not found in DB. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								return false;
							}

						}

					} // CHECK IF EMAIL EXISTS IN DB END
					/***************************************/


					/********** SAVE USER DATA TO DB **********/
					/**
					*
					*	Saves dataset of an User-Object to DB, returns true OR false
					* 	Writes lastInsertId into calling Object.
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if dataset was saved, else false
					*
					*/
					public function saveToDb($pdo) {
if(DEBUG_C)			echo "<p class='debug'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "( '".$this->getUsr_email()."' ) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						$sql 		= 	"INSERT INTO user
										(usr_firstname, usr_lastname, usr_email, usr_city, usr_password, rol_id)
                              VALUES ( ?, ?, ?, ?, ?, ? )";

						// hash Password before saving to DB
						$this->setUsr_password( password_hash($this->getUsr_password(), PASSWORD_DEFAULT) );

						$params 	= array( $this->getUsr_firstname(), $this->getUsr_lastname(), $this->getUsr_email(), $this->getUsr_city(), $this->getUsr_password(), 2 );

						// Step 2 DB: Prepare SQL Statement
						$statement = $pdo->prepare($sql);

						// Step 3 DB: Execute SQL statement and fill placeholder if necessary
						$statement->execute($params);
if(DEBUG_C)				if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

						// if writing in db: check, if Data was written into DB.
						if( !$rowCount = $statement->rowCount() ) {
							// Error case:
if(DEBUG_C)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: ERROR while saving User to DB! <i>(" . basename(__FILE__) . ")</i></p>";
							return false;

						} else {
							// Success case
if(DEBUG_C)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: '".$rowCount."'User sucessfully saved to DB. <i>(" . basename(__FILE__) . ")</i></p>";
							// write usr_id in calling Object
							$this->setUsr_id( $pdo->lastInsertId() );
							return true;

						} //

					} // FETCH USER DATA FROM DB END

					/***********************************************************/


				} // USER CLASS END
/*******************************************************************************************/
?>
