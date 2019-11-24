<?php
/*******************************************************************************************/


				/********************************/
				/********** CLASS BLOG **********/
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
				*	Class represents a Blog-(Posting) including a Category-Object and an User-Object
				*
				*
				*/
				class Blog {

					/********************************/
					/********** ATTRIBUTES **********/
					/********************************/

					// Attributes do not have to be initialized within the class definition.

					private $blog_id;
					private $blog_headline;
					private $blog_image;
					private $blog_imageAlignment;
					private $blog_content;
					private $blog_date;

					// $category is an object of the Category class.
					private $category;
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

					/********** BLOG_ID **********/
					public function getBlog_id() {
						return $this->blog_id;
					}
					public function setBlog_id($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: blog_id: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->blog_id = cleanString($value);
						}
					}

					/********** BLOG_HEADLINE **********/
					public function getBlog_headline() {
						return $this->blog_headline;
					}
					public function setBlog_headline($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: blog_headline: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->blog_headline = cleanString($value);
						}
					}

					/********** BLOG_IMAGE **********/
					public function getBlog_image() {
						return $this->blog_image;
					}
					public function setBlog_image($value) {
						// Check if the passed value is a string or NULL
						if( !is_string($value) AND !is_null($value)  ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: blog_image: Must be DATATYPE 'String' or 'NULL'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->blog_image = cleanString($value);
						}
					}

					/********** BLOG_IMAGEALIGNMENT **********/
					public function getBlog_imageAlignment() {
						return $this->blog_imageAlignment;
					}
					public function setBlog_imageAlignment($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: blog_imageAlignment: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->blog_imageAlignment = cleanString($value);
						}
					}

					/********** BLOG_CONTENT **********/
					public function getBlog_content() {
						return $this->blog_content;
					}
					public function setBlog_content($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: blog_content: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->blog_content = cleanString($value);
						}
					}

					/********** BLOG_DATE **********/
					public function getBlog_date() {
						return $this->blog_date;
					}
					public function setBlog_date($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: blog_date: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->blog_date = cleanString($value);
						}
					}

					/********** CATEGORY **********/
					public function getCategory() {
						return $this->category;
					}
					public function setCategory($value) {
						// Check if an object of class 'Category' has been passed
						if( !$value instanceof Category ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: category: MUST BE AN INSTANCE OF CLASS 'Category'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->category = ($value);
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

					/********* GET EU DATE TIME **********/
					public function getEuDateTime() {
						// Convert date to EU format
						$euTime = isoToEuDateTime($this->getBlog_date());

						return $euTime['date'] ." - ". $euTime['time'] ." Uhr";
					}


					/********* GET USERFULLNAME **********/
					public function getUserFullname() {
						// Delegation to included 'User object' firstname, lastname
						return $this->getUser()->getFullname();
					}


					/********* GET CATEGORYID **********/
					public function getCategoryId() {
						return $this->getCategory()->getCat_id();
					}


					/********* GET CATEGORYNAME **********/
					public function getCategoryName() {
						return $this->getCategory()->getCat_name();
					}
					/***********************************************************/


					/*****************************/
					/********** METHODS **********/
					/*****************************/


					/********** FETCHES BLOG OBJECTS FROM DB **********/
					/**
					*
					*	Static call: Fetches ALL BLOG object data from DB
					*	call with 'cat_id' in $cat_id: Fetches Only BLOG objects with matching cat_id from DB
					*	call with 'blog_id' in $blog_id: Fetches Only the BLOG with that blog_id from DB
					*
					*	@param	PDO		$pdo		DB-connection object
					*	@param	[String	$cat_id]		Optional to restrict the query to posts in a specific category
					*	@param	[String	$blog_id]		Optional to restrict the query to a specific post
					*
					*	@return	ARRAY					An array containing BLOGS as blogobjects
					*
					*/
					public static function getFromDb($pdo, $cat_id=NULL, $blog_id=NULL ) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "(\$pdo)<i>(" . basename(__FILE__) . ")</i></p>\r\n";

						// Default: Fetch all Blogs from DB
						$sql 		= 	"SELECT * FROM blog
										INNER JOIN user USING(usr_id)
										INNER JOIN category USING(cat_id)
                              ";

						$params 	= NULL;

						// Check if an ID was transmitted
						if( $blog_id )  {
							// If an BLOG-ID was passed, add it to the SQL statement
							// Selects only 1 specific Post with that id and includes 'role' in 'user'
							$sql 		.= "INNER JOIN role using(rol_id) WHERE blog_id = ?";
							$params	= array( $blog_id	);
						} elseif( $cat_id )  {
							// a cat_id leads to selection of blogs in that Category
							// If an ID was passed, add it to the SQL statement
							$sql 		.= " WHERE cat_id = ?";
							$params	= array( $cat_id	);
						}

						// Parameters for ordering the posts
						$sql 			.=	" ORDER BY blog_date DESC";

						// Step 2 DB: Prepare SQL Statement
						$statement = $pdo->prepare($sql);

						// Step 3 DB: Execute SQL statement and fill placeholder if necessary
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

						// Check if at least one post was fetched from DB
						$rowCount	= $statement->rowCount();

						if(!$rowCount) {
							// Error case
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: Error: No post was read from DB! <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						} else {
							// Success case
if(DEBUG_C)				echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: $rowCount posts selected from DB. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

							/*
if(DEBUG_C)				echo "<pre class='debugClass'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";
if(DEBUG_C)				print_r($dbBlogData['usr_id']['0']);
if(DEBUG_C)				echo "</pre>";
							*/

							/******** WRITE OBJECTS IN ARRAY *********/
							$blogObjectsArray = array();

							/******** FETCH ONE BLOG *********/
							// if a '$blog_id' was transmitted return only 1 'blog' including 'user' including 'role'
							if($blog_id) {
								$dbBlogData = $statement->fetch(PDO::FETCH_NAMED);

								// create a new blog Object with blogPost data
								$blogObjectsArray []	 = new Blog([
										'blog_id'					=> $dbBlogData['blog_id'],
										'blog_headline'			=> $dbBlogData['blog_headline'],
										'blog_image'				=> $dbBlogData['blog_image'],
										'blog_imageAlignment'	=> $dbBlogData['blog_imageAlignment'],
										'blog_content'				=> $dbBlogData['blog_content'],
										'blog_date'					=> $dbBlogData['blog_date'],
										// fill blog Object with Category Object and User Object containing a Role Object
										// filtering the data so that new Category()
										// gets only the needed data for the automatic call of the setters
										'Category'	=> new Category( [
													'cat_id'=>$dbBlogData['cat_id'],
													'cat_name'=>$dbBlogData['cat_name'],
													'cat_title'=>$dbBlogData['cat_title'],
													'cat_description'=>$dbBlogData['cat_description']
													]),
											// here i filtered manually because
											// passwords and emails are not needed for displaying blogs
											// and to make sure it gets the usr_id fetched from "blog"
										'User' 		=> new User( [
											'usr_id'=>$dbBlogData['usr_id']['0'],
											'usr_firstname'=>$dbBlogData['usr_firstname'],
											'usr_lastname'=>$dbBlogData['usr_lastname'],
											'usr_city'=>$dbBlogData['usr_city'],

											// the Role Object inside the User Object
											'Role'	=>	new Role([
												'rol_id'=>$dbBlogData['rol_id'],
												'rol_name'=>$dbBlogData['rol_name'],
											] ) // new Role end

										]) // new User end
									]); // create a new blog Object with blogPost data END

							} // FETCH ONE BLOG END *********/

							/******** FETCH ALL BLOGS(OR ALL FROM CATEGORY) *********/
							else {
								// Create Blog-objects from fetched datasets and return them as an array
								/*** WRITE OBJECTS IN ARRAY ***/
								while($dbBlogData = $statement->fetch(PDO::FETCH_NAMED) ) {

									$blogObjectsArray []	 = new Blog([
											'blog_id'					=> $dbBlogData['blog_id'],
											'blog_headline'			=> $dbBlogData['blog_headline'],
											'blog_image'				=> $dbBlogData['blog_image'],
											'blog_imageAlignment'	=> $dbBlogData['blog_imageAlignment'],
											'blog_content'				=> $dbBlogData['blog_content'],
											'blog_date'					=> $dbBlogData['blog_date'],
											// fill blog Object with Category Object and User Object containing a Role Object
											// filtering the data so that new Category()
											// gets only the needed data for the automatic call of the setters
											'Category'	=> new Category( [
														'cat_id'=>$dbBlogData['cat_id'],
														'cat_name'=>$dbBlogData['cat_name'],
														'cat_title'=>$dbBlogData['cat_title'],
														'cat_description'=>$dbBlogData['cat_description']
														]),
												// here i filtered manually because
												// passwords and emails are not needed for displaying blogs
												// and dbBlogData['usr_id']['0'] to make sure it gets the usr_id
												// fetched from the Blog-Table and not the usr_id fetched from the Category-Table
											'User' 		=> new User( [
												'usr_id'=>$dbBlogData['usr_id']['0'],
												'usr_firstname'=>$dbBlogData['usr_firstname'],
												'usr_lastname'=>$dbBlogData['usr_lastname'],
												'usr_city'=>$dbBlogData['usr_city']

											]) // new User end
										]); // create a new blog Object with blogPost data END

								} // WRITE OBJECTS IN ARRAY END

							} // FETCH ALL BLOGS(OR ALL FROM CATEGORY) END


							return $blogObjectsArray;

						}
					}

					/***********************************************************/


					/********** SAVE BLOG OBJECT TO DB **********/
					/**
					*
					*	Writes the data of the BlogObject into DB
					*	Writes new blog_id in corresponding Blog object if Object was saved to DB
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if Object was saved to DB, else false
					*
					*/
					public function saveToDb($pdo) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "() <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						/********** SAVE BLOG TO DB **********/

						//check wheter calling Blog object has a blog_id (in this case that means it exists in the DB)
						if( $this->getBlog_Id() ) {
							// success case
							// SQL Statement is UPDATE
							$sql 		= 	"UPDATE blog SET blog_headline = ?, blog_image = ?, blog_imageAlignment = ?,
							 				blog_content = ?, cat_id = ?, usr_id = ? WHERE blog_id = ?";

						} else{
							// error case
							// SQL Statement is INSERT INTO
							$sql 		= 	"INSERT INTO blog (blog_headline, blog_image, blog_imageAlignment, blog_content, cat_id, usr_id)
											VALUES (?,?,?,?,?,?) ";
						}

						$params 	= array( $this->getBlog_headline(),
												$this->getBlog_image(),
												$this->getBlog_imageAlignment(),
												$this->getBlog_content(),
												$this->getCategory()->getCat_id(),
												$this->getUser()->getUsr_id() );

						if( $this->getBlog_Id() ) {
							// success case
							// SQL Statement is UPDATE
							$params 	[]= $this->getBlog_id() ;

							}

						// Step 2 DB: Prepare SQL Statement
						$statement = $pdo->prepare($sql);
						// Step 3 DB: Execute SQL statement and fill placeholder if necessary
						$statement->execute($params);
if(DEBUG_C)			if($statement->errorInfo()[2]) echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: " . $statement->errorInfo()[2] . " <i>(" . basename(__FILE__) . ")</i></p>";

						// Step 4 DB: Check write success
						$rowCount = $statement->rowCount();
if(DEBUG_C)			echo "<p class='debugClass'>Line <b>" . __LINE__ . "</b>: Number of saved Datasets: $rowCount <i>(" . basename(__FILE__) . ")</i></p>";

						if( !$rowCount ) {
							// Error case
if(DEBUG_C)				echo "<p class='debugClass err'>Line <b>" . __LINE__ . "</b>: ERROR when saving the new post to the database! <i>(" . basename(__FILE__) . ")</i></p>";
							return false;

						} else {
							// Success case
							//check wheter calling Blog object has a blog_id (in this case that means it exists in the DB)
							if( !$this->getBlog_Id() ) {
								// read lastInserId and write it into the calling object
								$this->setBlog_Id(	$pdo->lastInsertId()	);
if(DEBUG_C)					echo "<p class='debugClass ok'>Line <b>" . __LINE__ . "</b>: New Blog successfully saved to DB with ID: <b>'".	$this->getBlog_Id()	."'</b><i>(" . basename(__FILE__) . ")</i></p>";
							}else {
if(DEBUG_C)					echo "<p class='debugClass ok'>Line <b>" . __LINE__ . "</b>: Blog with ID: <b>'".	$this->getBlog_Id()	."'</b> successfully editet in DB. <i>(" . basename(__FILE__) . ")</i></p>";
							}
							return true;

						} // Success case END
					} // SAVE BLOG TO DB END
					/***********************************************************/


					/********** DELETES BLOG DATASET FROM DB **********/
					/**
					*
					*	DELETES Blog object data from DB
					*	If successfull: returns true
					*
					*	@param	PDO		$pdo		DB-connection object
					*
					*	@return	Boolean				true if dataset was successfully deleted, else false
					*
					*/
					public function deleteFromDb($pdo) {
if(DEBUG_C)			echo "<p class='debugClass'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "(\$pdo) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						// check for id
						if( !$this->getBlog_id() ){
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: CANNOT DELETE BLOG WITHOUT ID! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						} else {
							// DELETE FROM
							$sql = "DELETE FROM blog WHERE blog_id = ?";
							$params = array( $this->getBlog_id() );
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
if(DEBUG_C)					echo "<p class='debugClass err'>Line <b>" . __LINE__ . "</b>: ERROR occured while deleting the Blog dataset! <i>(" . basename(__FILE__) . ")</i></p>";
								return false;

							} else {
								// Success case
if(DEBUG_C)						echo "<p class='debugClass ok'><b>Line " . __LINE__ . "</b>: Successfully deleted Blog dataset from Database. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
									return true;
							}
						} //  check for id END

					} // deleteFromDb() END
					/********** DELETES CATEGORY DATASET FROM DB END **********/


				} // CLASS BLOG END
/*******************************************************************************************/
?>
