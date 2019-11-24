<?php
/*******************************************************************************************/


				/************************************/
				/********** CLASS CATEGORY **********/
				/************************************/

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
				*	Class represents a Blog-Category with 'id' and 'name'
				*
				*
				*/
				class Category {

					/********************************/
					/********** ATTRIBUTES **********/
					/********************************/

					// Attributes do not have to be initialized within the class definition.

					private $cat_id;
					private $cat_name;
					private $cat_title;
					private $cat_description;
					// $user is an object of the class User
					private $user;


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

					/********** CAT_ID **********/
					public function getCat_id() {
						return $this->cat_id;
					}
					public function setCat_id($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClassClass err'><b>Line " . __LINE__ . "</b>: cat_id: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->cat_id = cleanString($value);
						}
					}

					/********** CAT_NAME **********/
					public function getCat_name() {
						return $this->cat_name;
					}
					public function setCat_name($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClassClass err'><b>Line " . __LINE__ . "</b>: cat_name: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->cat_name = cleanString($value);
						}
					}

					/********** CAT_TITLE **********/
					public function getCat_title() {
						return $this->cat_title;
					}
					public function setCat_title($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClassClass err'><b>Line " . __LINE__ . "</b>: cat_title: Must be DATATYPE 'String'! <i>(" . basetitle(__FILE__) . ")</i></p>";
						} else {
							$this->cat_title = cleanString($value);
						}
					}

					/********** CAT_DESCRIPTION **********/
					public function getCat_description() {
						return $this->cat_description;
					}
					public function setCat_description($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClassClass err'><b>Line " . __LINE__ . "</b>: cat_description: Must be DATATYPE 'String'! <i>(" . basedescription(__FILE__) . ")</i></p>";
						} else {
							$this->cat_description = cleanString($value);
						}
					}

					/********** USER **********/
					public function getUser() {
						return $this->user;
					}
					public function setUser($value) {
						// Check if an object of class 'User' has been passed
						if( !$value instanceof User ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: user: MUST BE AN INSTANCE OF CLASS 'User'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->user = ($value);
						}
					}


					/*************************************/
					/********* VIRTUAL ATTRIBUTES ********/
					/*************************************/

					/********* GET CATUSERFULLNAME **********/
					public function getUserFullname() {
						// Delegation to included 'User object' firstname, lastname
						return $this->getUser()->getFullname();
					}


					/***********************************************************/


					/*****************************/
					/********** METHODS **********/
					/*****************************/

					/*****************************************************/
					/******* FETCH CATEGORY DATASETS FROM DB *******/
					/**
					*
					*	Fetches Category object data from DB
					*	Returns ALL Categories if no restriction ($cat_id) is given.
					*
					*	@param	PDO		$pdo		DB-connection object
					*	@param	[String	$cat_id]		Optional to restrict the query to SELECT 1 category
					*
					*	@return	Array					Array with Category Objects if dataset(s) where found, else false
					*
					*/
					public static function getFromDb($pdo, $cat_id=NULL) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "(\$pdo) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						// Default: Fetch all Categories from DB WITHOUT CATUSER
						$sql 		= 	"SELECT * FROM category";

						$params 	= NULL;

						// Check if an ID was transmitted
						if( $cat_id )  {
							// If an Category-ID was passed, add it to the SQL statement
							// Selects only 1 specific Category with that id and included CATUSER DATA
							$sql 		.= " INNER JOIN user USING(usr_id)
												INNER JOIN role USING(rol_id) WHERE cat_id = ?";
							$params	= array($cat_id);
						}

						// Parameters for ordering the categories
						$sql 			.=	" ORDER BY cat_name ASC";


						// Step 2 DB: Prepare SQL Statement
						$statement = $pdo->prepare($sql);

						// Step 3 DB: Execute SQL statement and fill placeholder if necessary
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

						// Check if at least one category has been fetched from DB
						$rowCount	= $statement->rowCount();

						if(!$rowCount) {
							// Error case
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: No category could be selected from the DB! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							return false;

						} else {
							// Success case
if(DEBUG_C)				echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: $rowCount Categories read from DB. Creating Objects... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

							// WRITE OBJECTS IN ARRAY
							$catObjectsArray = array();

							/******** FETCH ONE CATEGORY *********/
							// if $cat_id: include the User Object and return 1 categoryObject in an array
							if($cat_id) {

								$catObjectData = $statement->fetch(PDO::FETCH_ASSOC);

								$catObjectsArray [] = new Category( [
						  			'cat_id'=>$catObjectData['cat_id'],
						  			'cat_name'=>$catObjectData['cat_name'],
						  			'cat_title'=>$catObjectData['cat_title'],
						  			'cat_description'=>$catObjectData['cat_description'],

									// the included User Object
									'User' 		=> new User( [
		 							  	'usr_id'=>$catObjectData['usr_id'],
		 							  	'usr_firstname'=>$catObjectData['usr_firstname'],
		 							  	'usr_lastname'=>$catObjectData['usr_lastname'],
		 							  	'usr_city'=>$catObjectData['usr_city'],

		 							  	// the Role Object inside the User Object
		 							  	'Role'	=>	new Role([
		 							  		'rol_id'=>$catObjectData['rol_id'],
		 							  		'rol_name'=>$catObjectData['rol_name'],
		 						  		] ) // new Role end
						  			] ) // new User end
								] ); // new Category end

							} // FETCH ONE CATEGORY END

							/******** FETCH ALL CATEGORIES *********/
							else {
								// autoload all categories into the array without userObject
								// Create objects with found datasets and return them in an array
								foreach($statement->fetchAll(PDO::FETCH_ASSOC) AS $catObjectData){
									// lazy autoconstruct solution for the Category without userObject:
									$catObjectsArray [] = new Category($catObjectData);
								} // foreach end

						} // WRITE OBJECTS IN ARRAY END
						// return 1 or all categories in an array
						return $catObjectsArray;
					}
				} // FETCH CATEGORY DATASETS FROM DB END
				/**********************************************/







					/*****************************************************/
					/********** CHECKS IF CATEGORY EXISTS IN DB **********/
					/**
					*
					*	Checks if the name of the calling Category Object already exists in the DB
					*	If the calling Category Object has a ID, this Category is excluded from the search
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if dataset was found, else false
					*
					*/
					public function checkIfExistsIn($pdo) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "(\$pdo) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						// check if the calling object has included a name or not
						if(!$this->getCat_name()){
							// Error case
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: ERROR: Calling Object contains no 'cat_name' <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						} else {
							// Success case
if(DEBUG_C)				echo "<p class='debugClass hint'><b>Line " . __LINE__ . "</b>: Checking: Does ".$this->getCat_name()." already exist in DB... <i>(" . basename(__FILE__) . ")</i></p>\r\n";

							// DB query: chek if 'Category' with cat_name already exists in DB
							$sql = "SELECT COUNT(*) FROM category WHERE cat_name = ?";
							$params = array( $this->getCat_name() );

							if($this->getCat_id()) {
								// ADD "ID != ID" as restriction
								$sql .= " AND cat_id != ?";
								$params[] = $this->getCat_id();
							}
if(DEBUG_C)				echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: SQL: " . $sql . " <i>(" . basename(__FILE__) . ")</i></p>";


							// Step 2 DB: Prepare SQL Statement
							$statement = $pdo->prepare($sql);
							// Step 3 DB: Execute SQL statement and fill placeholder if necessary
							$statement->execute($params);
if(DEBUG_C)				if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

							if(	$statement->fetchColumn()	) {
if(DEBUG_C)					echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: CATEGORY ".$this->getCat_name()." exists in DB. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								// Category already exists in DB
								return true;
							} else {
								// Category name was not found in DB
if(DEBUG_C)					echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: NO OTHER CATEGORY ".$this->getCat_name()." does exist in DB. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								return false;
							}

						}

					} // CHECKS IF CATEGORY EXISTS IN DB END
					/***************************************/


					/********** SAVES CATEGORY DATASET TO DB **********/
					/**
					*
					*	Saves Category object data to DB
					*	If successfull: Sets the NEW ID into the calling Object
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if dataset was successfully saved, else false
					*
					*/
					public function saveToDb($pdo) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "(\$pdo) <i>(" . basename(__FILE__) . ")</i></p>\r\n";


						if( $this->getCat_id() ){
							// UPDATE
if(DEBUG_C)				echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: UPDATING CAT ".$this->getCat_name()." in Database... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							$sql = "UPDATE category SET cat_name = ?, cat_title =?, cat_description = ?, usr_id = ? WHERE cat_id = ?";
							$params = array(	$this->getCat_name(), $this->getCat_title(), $this->getCat_description(), $this->getUser()->getUsr_id(), $this->getCat_id() );
						} else {
							// NEW CATEGORY, INSERT INTO
if(DEBUG_C)				echo "<p class='debugClass'><b>Line " . __LINE__ . "</b>: Saving NEW CAT ".$this->getCat_name()." to Database... <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							$sql = "INSERT INTO category (cat_name, cat_title, cat_description, usr_id) VALUES (?,?,?,?)";
							$params = array(	$this->getCat_name(), $this->getCat_title(), $this->getCat_description(), $this->getUser()->getUsr_id() );
						}

						// Step 2 DB: Prepare SQL Statement
						$statement = $pdo->prepare($sql);
						// Step 3 DB: Execute SQL statement and fill placeholder if necessary
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

						// Step 4 DB: Check write success
						$rowCount = $statement->rowCount();
if(DEBUG_C)			echo "<p class='debugClass'>Line <b>" . __LINE__ . "</b>: Number of saved Datasets: $rowCount <i>(" . basename(__FILE__) . ")</i></p>";

						if( !$rowCount ) {
							// Error case
if(DEBUG_C)				echo "<p class='debugClass err'>Line <b>" . __LINE__ . "</b>: ERROR occured while saving the Category! <i>(" . basename(__FILE__) . ")</i></p>";
							return false;

						} else {
							// Success case
							// Check wheter saved CAT was NEW or had an ID
							if(!$this->getCat_id()) {
								$this->setCat_id(	$pdo->lastInsertId()	);
if(DEBUG_C)					echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: New Category with ID: '".$this->getCat_id()."' successfully saved to Database. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								return true;
							} else {
								// Updated CAT sucessfully
if(DEBUG_C)					echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: Category with ID: '".$this->getCat_id()."' - saved changes in Database. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								return true;
							}

						}

					} // SAVES CATEGORY DATASETS TO DB END
					/**********************************************/


					/********** DELETES CATEGORY DATASET FROM DB **********/
					/**
					*
					*	DELETES Category object data from DB
					*	If successfull: returns true
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if dataset was successfully deleted, else false
					*
					*/
					public function deleteFromDb($pdo) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "(\$pdo) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						if( !$this->getCat_id() ){
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: CANNOT DELETE CATEGORY WITHOUT ID! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						} else {
							// DELETE FROM
							$sql = "DELETE FROM category WHERE cat_id = ?";
							$params = array( $this->getCat_id() );
							// Step 2 DB: Prepare SQL Statement
							$statement = $pdo->prepare($sql);
							// Step 3 DB: Execute SQL statement and fill placeholder if necessary
							$statement->execute($params);
if(DEBUG_C)				if($statement->errorInfo()[2]) echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

							// Step 4 DB: Check write success
							$rowCount = $statement->rowCount();
if(DEBUG_C)				echo "<p class='debugClass'>Line <b>" . __LINE__ . "</b>: Number of deleted Datasets: $rowCount <i>(" . basename(__FILE__) . ")</i></p>";

							if( !$rowCount ) {
								// Error case
if(DEBUG_C)					echo "<p class='debugClass err'>Line <b>" . __LINE__ . "</b>: ERROR occured while deleting the Category! <i>(" . basename(__FILE__) . ")</i></p>";
								return false;

							} else {
								// Success case
if(DEBUG_C)					echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: Successfully deleted Category from Database. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
								return true;
							}
						}

					} // deleteFromDb() END
					/********** DELETES CATEGORY DATASET FROM DB END **********/




				} // CLASS CATEGORY END
/*******************************************************************************************/
?>
