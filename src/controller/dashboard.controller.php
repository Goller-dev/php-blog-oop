<?php
/***************************************************************************************/



				/***********************************/
				/********** CONFIGURATION **********/
				/***********************************/

				require_once("include/classLoader.inc.php");
				require_once("include/config.inc.php");
				require_once("include/dateTime.inc.php");
				require_once("include/db.inc.php");
				require_once("include/form.inc.php");

				/********** INCLUDE TRAITS **********/
				require_once("traits/autoConstruct.trait.php");

				/********** INCLUDE CLASSLOADER **********/
				spl_autoload_register("autoloadClasses");


/***************************************************************************************/


				/***********************************/
				/******** OUTPUT BUFFERING *********/
				/***********************************/

				// ob_start() - Enable output buffering

				if( !ob_start() ) {
					// Error case
if(DEBUG) 		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR while activating AUTO BUFFERING! <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				} else {
				// Success case
if(DEBUG) 		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Output Buffering is activated. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
				}




/***************************************************************************************/

				/*********************************/
				/********** SECURE PAGE **********/
				/*********************************/

				/********** INITIALIZE SESSION **********/
				session_name("blogProject");
				session_start();

				/********** CHECK FOR USER_OBJECT INDEX IN SESSION **********/
				if( !isset($_SESSION['userObject']) ) {
					// Error case
					session_destroy();
					header("Location: index.php#pageHead");
					exit();
					// write User-Object into Session
				} elseif ($user = clone $_SESSION['userObject'] ) {
if(DEBUG) 		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: User ". $user->getFullname() ." is logged in. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				} else {
if(DEBUG) 		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR while creating User with \$_SESSION['userObject'].  <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				}


/***************************************************************************************/


				/******************************************/
				/********** INITIALIZE VARIABLES **********/
				/******************************************/

				/** $category represents the Category-Object in the Category FORM #categoryform  **/
				$category = new Category(
													/** $user represents the User-Object of the logged in User **/
													['user' 		=> $user]
												);

				/** $blog represents the Blog-Object in the Blog FORM #blogform  **/
				// Empty instance of: 'Blog' with empty instance of 'Category' and filled 'User' object
				$blog				=	new Blog( [
														'Category'	=> new Category(),
														/** $user represents the User-Object of the logged in User **/
														'User' 		=> $user
														] );

				$action						= NULL;
				$errorCatName 				= NULL;
				$errorCatTitle				= NULL;
				$errorCatDescription		= NULL;
				$errorHeadline 			= NULL;
				$errorContent 				= NULL;
				$errorCategory				= NULL;
				$errorImageUpload 		= NULL;

				$catErrorMessage			= NULL;
				$catSuccessMessage		= NULL;
				$blogErrorMessage 		= NULL;
				$blogSuccessMessage 		= NULL;

				$blogFilter					= NULL;
				$catFilter					= NULL;

				$deleteCheckMessage		= NULL;
				$deleteCheckHeadline		= NULL;
				$noPermissionMessage		= NULL;

				// default dashboard.php headlines
				$pageMainHeadline		=	"Spartan CMS - Mach was neues!";
				$blogArticleHeadline	=	"Neuen Blog erstellen";
				$catArticleHeadline	=	"Neue Kategorie erstellen";

				$blogFormInputValue		=	"Neu Veröffentlichen";
				$catFormInputValue		=	"Neue Kategorie erstellen";



				/********** ESTABLISH DB CONNECTION **********/

				// DB Connection is set in config.inc.php
				$pdo = dbConnect();





/***************************************************************************************/


				/********************************************/
				/********** PROCESS URL PARAMETERS **********/
				/********************************************/

				/********** URL PARAM: PERMISSION_DENIED **********/
				// Step 1 URL: Check if parameters were transferred
				if( isset($_GET['permission_denied']) ) {
					// display error Message from $_SESSION
					$noPermissionMessage = $_SESSION['permission_denied'];
				}

				/********** URL PARAM: ACTION **********/
				// Step 1 URL: Check if parameters were transferred
				if( isset($_GET['action']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL parameter 'action' was passed...  <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 2 URL Action: Read values, defuse, DEBUG output
					$action = cleanString($_GET['action']);
if(DEBUG)		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$action = $action <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 3 URL: Branch if necessary

					/********** LOGOUT **********/
					if( $action == "logout" ) {
						// Step 4 URL Action=logout: further processing of data
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>:  'Logout' (This message should not be visible if the forwarding works.)<i>(" . basename(__FILE__) . ")</i></p>";
						// delete session
						session_destroy();
						header("Location: index.php#pageHead");
						exit();
					} // LOGOUT END

					/********** SHOW CATEGORY FILTER **********/
					elseif( $action == "showCategory" ) {

						// read additional parameter: 'id'
						if( isset($_GET['id']) ) {
							$id = cleanstring($_GET['id']);
						} else $id = NULL;

						// display edit headline
						$pageMainHeadline		=	"Kategorien und Beiträge verwalten";

	if(DEBUG)		echo "<p class='debug'><b>Line " . __LINE__ . "</b>: ID for showCategory: ( ". $id ." ) <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						// Blogs with id in $id are fetched from the DB
						if(!$blogEntriesArray = Blog::getFromDb($pdo, $id )) {
							// Error case
	if(DEBUG)			echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: No blogs were fetched! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						} else {
							// Success case
	if(DEBUG)			echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Fetched all Blogs from Category ". $blogEntriesArray[0]->getCategoryname() ." successfully.  <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						} // Blogs with id in $showCategoryId are fetched from the DB END
					} // SHOW CATEGORY FILTER END
					/****************************/

					/********** ADDITIONAL PARAMETERS - ONLY FOR EDIT & DELETE **********/
					/********** cat_id **********/
					elseif( isset($_GET['cat_id']) ) {
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: URL filter 'Category' is active... <i>(" . basename(__FILE__) . ")</i></p>";
						// Step 2 URL catFilter: read values, defuse, debug output:
						/** $catFilter represents the URL-PARAM 'cat_id'  **/
						$catFilter = new Category($_GET);
if(DEBUG)			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: ID: ". $catFilter->getCat_id() ." ( in Object \$catFilter) <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						// Step 3 URL catFilter: branching if neccessary

						// check if $catFilter has a cat_id
						if( !$catFilter->getCat_id() ) {
							// error case
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: NO ID in \$catFilter!! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							// empty $catFilter
							$catFilter = NULL;
						} else {


							// Step 4 URL catFilter: further processing of data
	if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Loading Blogs from Category with ID ". $catFilter->getCat_id() ." ... <i>(" . basename(__FILE__) . ")</i></p>";

							/***** FETCH CATEGORY WITH CAT ID *****/
							// Category Object with cat_id in $catFilter is fetched from the DB
							if(!$selectedCategory = Category::getFromDb($pdo, $catFilter->getCat_id() )) {
								// Error case
	if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: No Category was fetched! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							} else {
								// Success case

								/*** CHECK USER ROLE ***/
								// if $user created the fetched Category or is 'Role: Admin': allow edit / delete
								if( $user->getRole()->getRol_name() != "Administrator" AND $user->getUsr_id() != $selectedCategory[0]->getUser()->getUsr_id() ) {
									// error
	if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: User is not creator, Role is not Admin. No permission to edit or delete this category! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
									// save 'permission denied' Message in Session:
									$_SESSION['permission_denied'] = "Kategorien dürfen nur vom Ersteller oder einem Administrator geändert werden.
																				'". $selectedCategory[0]->getUser()->getFullname() ."' darf '". $selectedCategory[0]->getCat_name() ."' löschen oder bearbeiten.";
									// redirect to dashboard.php?action=showCategory&permission_denied
									header("Location: dashboard.php?action=showCategory&permission_denied");
									// reset $catFilter
									$catFilter = NULL;

								} else {
									// success
	if(DEBUG)					echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Fetched Category '". $category->getCat_name() ."' successfully.  <i>(" . basename(__FILE__) . ")</i></p>\r\n";
									// Clone fetched category into $category
									$category = clone $selectedCategory[0];

								} // CHECK USER ROLE END
							} // FETCH CATEGORY WITH CAT ID
						} // check if $catFilter has a cat_id END
					} // cat_id  END

					/********** blog_id **********/
					elseif( isset($_GET['blog_id']) ) {
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: URL filter 'Blog' is active... <i>(" . basename(__FILE__) . ")</i></p>";
						// Step 2 URL blogFilter: read values, defuse, debug output:

						/** $blogFilter represents the URL-PARAM 'blog_id' **/
						$blogFilter = new Blog($_GET);
if(DEBUG)			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: ID: ". $blogFilter->getBlog_id() ." (\$blogFilter) <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						// Step 3 URL blogFilter: branching if necessary

						// check if $blogFilter has a blog_id
						if( !$blogFilter->getBlog_id() ) {
							// error case
if(DEBUG)				echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: NO ID in \$blogFilter!! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							// empty $blogFilter
							$blogFilter = NULL;
						} else {
							// success
							// Step 4 URL blogFilter: further processing of data
							/***** FETCH ONE BLOG WITH BLOG_ID *****/
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Loading the Blog with ID ". $blogFilter->getBlog_id() ." ... <i>(" . basename(__FILE__) . ")</i></p>";
							// fetch Blog with blog_id in $blogFilter from the DB
							if(!$blogEntriesArray = Blog::getFromDb($pdo, NULL, $blogFilter->getBlog_id() )) {
								// Error case
if(DEBUG)					echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: No blog was fetched! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							} else {
								// Success case

								/*** CHECK USER ROLE ***/
								// if $user created the fetched Category or is 'Role: Admin': allow edit / delete
								if( $user->getRole()->getRol_name() != "Administrator" AND $user->getUsr_id() != $blogEntriesArray[0]->getUser()->getUsr_id() ) {
									// error
if(DEBUG)						echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: User is not creator, Role is not Admin. No permission to edit or delete this blog! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
									// save 'permission denied' Message in Session:
									$_SESSION['permission_denied'] = "Beiträge dürfen nur vom Ersteller oder einem Administrator geändert werden.
																				'". $blogEntriesArray[0]->getUser()->getFullname() ."' darf '". $blogEntriesArray[0]->getBlog_headline() ."' löschen oder bearbeiten.";
									// redirect to dashboard.php?action=showCategory&permission_denied
									header("Location: dashboard.php?action=showCategory&permission_denied");
									// reset $catFilter
									$blogFilter = NULL;
								} else {
									// success
									// Clone fetched blog into $blog
									$blog = clone $blogEntriesArray[0];
if(DEBUG)						echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Blog: <b>". $blog->getBlog_headline() ."</b> successfully fetched.  <i>(" . basename(__FILE__) . ")</i></p>\r\n";

								} // CHECK USER ROLE END

							} // FETCH ONE BLOG WITH BLOG_ID END
						} // check if $blogFilter has a blog_id END
					} // URL PARAM: 'BLOG_ID' END


					/********** EDIT **********/
					if( $action == "edit" ) {
						// Step 4 URL Action=edit: further processing of data
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>:  'Edit' is active.<i>(" . basename(__FILE__) . ")</i></p>";
						$pageMainHeadline		=	"Spartan CMS - Änder was!";
						$blogArticleHeadline	=	"Beitrag bearbeiten";
						$catArticleHeadline	=	"Kategorie bearbeiten";
						$blogFormInputValue	=	"Speichern";
						$catFormInputValue	=	$blogFormInputValue;
						// extract old image(File-Path) from Object fetched with $blogFilter
						$oldImageFilepath = $blog->getBlog_image();
						/*** further processing of EDIT in CATEGORY & BLOG FORMS ***/

					} // EDIT END

					/********** DELETE **********/
					 elseif( $action == "delete" ) {

						// Step 4 URL Action=delete: further processing of data
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>:  'Delete' is active.<i>(" . basename(__FILE__) . ")</i></p>";
						$pageMainHeadline		="Spartan CMS - Räum auf!";
						$blogArticleHeadline	="Beitrag löschen";
						$catArticleHeadline	="Kategorie löschen";

						/**** DELETE CATEGORY ****/
						if( $catFilter ){
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Category: ". $category->getCat_name() .", Showing delete check message... <i>(" . basename(__FILE__) . ")</i></p>";

							// show deletecheck
							$deleteCheckHeadline = "Kategorie '".$category->getCat_name()."' und <b>alle enthaltenen Beiträge</b> wirklich löschen?";
							$deleteCheckMessage 	= '<a class="deletelink" href="?action=delete&cat_id='.$category->getCat_id().'&deleteCheck=yes#delcat">Kategorie mit Beiträgen löschen!</a>';

							/*** CATEGORY DELETE CHECK ***/
							//  Step 1 URL: Check for deletecheck
							if( isset($_GET['deleteCheck']) ) {
if(DEBUG)					echo "<p class='debug'> URL: 'deleteCheck' was passed.</p>\r\n";

								// Step 2 URL: read values, defuse, Debug-Output
								$deleteCheck = cleanString($_GET['deleteCheck']);
if(DEBUG)					echo "<p class='debug'>\$deleteCheck: $deleteCheck</p>\r\n";

								// Step 3 URL: branching if necessary
								if( $deleteCheck == "yes" ) {
									// delete Category
if(DEBUG)						echo "<p class='debug'> 'deleteCheck' = 'yes', deleting ".$category->getCat_name()."...</p>\r\n";
									// delete Category
									if( !$category->deletefromDB($pdo) ) {
										// error
if(DEBUG)							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: ERROR while deleting Category from DB. <i>(" . basename(__FILE__) . ")</i></p>";
										$deleteCheckHeadline = 'Es ist ein Fehler beim Löschen aufgetreten.';
										$deleteCheckMessage 	= "Bitte versuchen Sie es später erneut.";
									} else {
										// succesfully deleted category
if(DEBUG)							echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Successfully deleted Category ". $category->getCat_name() ." from DB. <i>(" . basename(__FILE__) . ")</i></p>";
										$deleteCheckHeadline = '<p>Kategorie '. $category->getCat_name() .' erfolgreich gelöscht.</p>';
										$category = new Category();
									} // DELETE FROM DB END
								} // Delete Check = YES ? END
							} // CATEGORY DELETE CHECK END
						} // DELETE CATEGORY END
						/***********************/

						/**** DELETE BLOG ****/
						elseif( $blogFilter ) {
							// show deletecheck
							$deleteCheckHeadline = "Beitrag '".$blog->getBlog_headline()."' löschen?";
							$deleteCheckMessage 	= '<a class="deletelink" href="?action=delete&blog_id='.$blog->getBlog_id().'&deleteCheck=yes#delblog">Löschen bestätigen!</a>';

							/*** BLOG DELETE CHECK ***/
							//  Step 1 URL: Check for deletecheck
							if( isset($_GET['deleteCheck']) ) {
if(DEBUG)					echo "<p class='debug'> URL: 'deleteCheck' was passed.</p>\r\n";

								// Step 2 URL: read values, defuse, Debug-Output
								$deleteCheck = cleanString($_GET['deleteCheck']);
if(DEBUG)					echo "<p class='debug'>\$deleteCheck: $deleteCheck</p>\r\n";

								// Step 3 URL: branching if necessary
								if( $deleteCheck == "yes" ) {
if(DEBUG)						echo "<p class='debug'> 'deleteCheck' = 'yes', deleting ".$blog->getBlog_headline()."...</p>\r\n";
									// delete blog
									if( !$blog->deletefromDB($pdo) ) {
										// error
if(DEBUG)							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: ERROR while deleting Blog from DB. <i>(" . basename(__FILE__) . ")</i></p>";
										$deleteCheckHeadline = 'Es ist ein Fehler beim Löschen aufgetreten.';
										$deleteCheckMessage 	= "Bitte versuchen Sie es später erneut.";
									} else {
										// succesfully deleted blog
										// now delete the picture from the server.
										//if the Blog had a picture, delete it from server.
										if( $blog->getBlog_image() ) {
											if(!@unlink( $blog->getBlog_image() ) ){
												// error
												if(DEBUG)								echo "<p class='debug err'>ERROR while deleting IMAGE from Server: '". $blog->getBlog_image() ."'.</p>\r\n";
												// TODO: write information in adminlog-file
											} else {
												// successfully deleted
												if(DEBUG)								echo "<p class='debug ok'>Picture file successfully deleted.</p>\r\n";
											}

										} // delete picture from server END

if(DEBUG)							echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Successfully deleted Blog ". $blog->getBlog_headline() ." from DB. <i>(" . basename(__FILE__) . ")</i></p>";
										$deleteCheckHeadline = 'Beitrag '. $blog->getBlog_headline() .' erfolgreich gelöscht.';
										$deleteCheckMessage = "";
										// fill blog Object with an empty Blog.
										$blog = new Blog();

									} // DELETE BLOG FROM DB END
								} // Delete Check = YES ? END
							} // BLOG DELETE CHECK END
						} // DELETE BLOG END
						/***********************/

					} // ACTION = DELETE END

				}// URL PARAM: ACTION END
				/********** URL PARAM: ACTION END**********/


/***************************************************************************************/


				/*******************************************************/
				/************** FORM PROCESSING CATEGORY ***************/
				/************** FOR CREATE AND EDIT **************/
				/*******************************************************/
				// Step 1 FORM: Check whether form was sent or not
				if( isset($_POST['formsentCategory']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Form 'Category' was sent... <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 2 FORM: Read values, defuse, DEBUG output
					$category->setAttributes($_POST);
if(DEBUG)		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Name of the Category: ".	$category->getCat_name()	." <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 3 FORM: Validate values if necessary
					$errorCatName 			= checkInputString(	$category->getCat_name()	);
					$errorCatTitle 		= checkInputString(	$category->getCat_title()	);
					$errorCatDescription	= checkInputString(	$category->getCat_description(),2,16000000	);

					/********** FINAL FORM CHECK **********/
					if( $errorCatName OR $errorCatTitle OR $errorCatDescription ) {
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: The form still contains errors! <i>(" . basename(__FILE__) . ")</i></p>";

					} else {
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Form is formally error-free and is now being processed... <i>(" . basename(__FILE__) . ")</i></p>";

						// Step 4 FORM: Further processing of data

						/********** CHECK WHETHER CATEGORY ALREADY EXISTS **********/
						// If the category object has a cat_id at this point,
						// the name of the category is excluded from the comparison.
						if( $category->checkIfExistsIn($pdo) ) {
							// Error case
							echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Category <b>'".	$category->getCat_name()	."'</b> already exists! <i>(" . basename(__FILE__) . ")</i></p>";
							$catErrorMessage = "Es existiert bereits eine Kategorie mit dem Namen ".	$category->getCat_name()	."!";
							$catErrorMessage .= "Erstelle eine neue Kategorie oder schreibe einen Beitrag in ".	$category->getCat_name().".";

						} else {
							// Success case
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Saving Category <b>".	$category->getCat_name()	."</b> to database... <i>(" . basename(__FILE__) . ")</i></p>";

							/********** SAVE CATEGORY TO DB **********/
							// If the category object has a cat_id at this point, saveToDb()
							// will EDIT the Dataset with that cat_id in the DB.
							if( !$category->saveToDb($pdo) ) {
								// Error case
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: ERROR while saving ".	$category->getCat_name()	." to Database! <i>(" . basename(__FILE__) . ")</i></p>";
								$catErrorMessage = "Fehler beim Speichern von ".	$category->getCat_name()	."!";

							} else {
								// Success case
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Category <b>'".	$category->getCat_name()	."'</b> successfully saved to DB with ID <b>'".	$blog->getCategoryId()	."'</b>.<i>(" . basename(__FILE__) . ")</i></p>";
								$catSuccessMessage = "Die Kategorie <b>".	$category->getCat_name()	."</b> wurde erfolgreich gespeichert.";

								// if action is not "edit":
								if( $action != "edit" ){
									// delete Field presetting
									$category = new Category();
								}

							} // SAVE CATEGORY TO DB END

						} // CHECK WHETHER CATEGORY ALREADY EXISTS END

					} // FINAL FORM CHECK END

				} //  FORM PROCESSING NEW CATEGORY END


/***************************************************************************************/


				/*********************************************************/
				/************** FORM PROCESSING BLOG ENTRY ***************/
				/*********************************************************/
				/************** FOR CREATE AND EDIT BLOGS **************/
				/*******************************************************/

				// Step 1 FORM: Check whether form was sent or not
				if( isset($_POST['formsentBlogEntry']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Form 'Blog Entry' was sent... <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 2 FORM: Read values, defuse, DEBUG output
					$blog->getCategory()->setCat_id($_POST['cat_id']);
					$blog->setAttributes($_POST);

					// Step 3 FORM: Validate values if necessary
					$errorHeadline	=	checkInputString(	$blog->getBlog_headline(),1	);
					$errorContent	=	checkInputString(	$blog->getBlog_content(), 5, 16000000);
					$errorCategory	=	checkInputString(	$blog->getCategoryId(), 1, 50);

					/********** FINAL FORM CHECK PART 1 (FORM FIELDS) **********/

					if( $errorHeadline OR $errorContent OR $errorCategory) {
						// Error case
if(DEBUG)			echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Form still contains errors! <i>(" . basename(__FILE__) . ")</i></p>";

					} else {
if(DEBUG)			echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Form is formally error free. Checking for picture upload... <i>(" . basename(__FILE__) . ")</i></p>";


						/********** FILE UPLOAD **********/

						// Check wheter a file was uploaded or not
						if( $_FILES['blog_image']['tmp_name'] !=  "") {
if(DEBUG)				echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: Picture upload is active... <i>(" . basename(__FILE__) . ")</i></p>";

							// imageUpload() returns an array containing an error message (string or NULL)
							// and the path to the saved image
							$imageUploadResultArray = imageUpload($_FILES['blog_image'], $blog->getBlog_headline() );

							// If an error occured:
							if( $imageUploadResultArray['imageError'] ) {
								$errorImageUpload = $imageUploadResultArray['imageError'];

							// If no error:
							} else {
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Image was successfully saved to <i>" . $imageUploadResultArray['imagePath'] . "</i>. <i>(" . basename(__FILE__) . ")</i></p>";
								// Write path to image in blog object
								$blog->setBlog_image(	$imageUploadResultArray['imagePath']	);
							}
						} else {
if(DEBUG)				echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: No picture file upload. <i>(" . basename(__FILE__) . ")</i></p>";

						} // FILE UPLOAD END


						/********** FINAL FORM CHECK PART 2 (PICTURE UPLOAD) **********/

						if( $errorImageUpload) {
							// Error case
if(DEBUG)				echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: Form still contains errors (Picture upload)! <i>(" . basename(__FILE__) . ")</i></p>";

						} else {
if(DEBUG)				echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Form is free of errors. Saving to Database... <i>(" . basename(__FILE__) . ")</i></p>";


							/********** EDIT: PICTURE DELETE **********/
							/********** IF EDIT AND (delete_blog_image OR successfull imageUpload) **********/
							if( $action == "edit" AND ( isset( $_POST['delete_blog_image']) OR isset( $imageUploadResultArray['imagePath'] ) ) ) {
								// delete the old picture file from the server
if(DEBUG)					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>:Filepath in \$blog:'". $blog->getBlog_image() ."' 'Blog Object' <i>(" . basename(__FILE__) . ")</i></p>";
if(DEBUG)					echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>:Deleting old Image Filepath:'". $oldImageFilepath ."' now...' <i>(" . basename(__FILE__) . ")</i></p>";

								if(!@unlink( $oldImageFilepath ) ){
									// error
if(DEBUG)						echo "<p class='debug err'>ERROR while deleting: '". $oldImageFilepath ."'.</p>\r\n";
								} else {
									// successfully deleted
if(DEBUG)						echo "<p class='debug ok'>Picture file successfully deleted.</p>\r\n";
								}
								// check if no new picture was uploaded
								if( !isset( $imageUploadResultArray['imagePath'] ) ) {
									// fill $blog with empty imagepath before saving to DB
if(DEBUG)						echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>:Removing Path:'". $blog->getBlog_image()."' from 'Blog Object' <i>(" . basename(__FILE__) . ")</i></p>";
									$blog->setBlog_image("");
								}
							} // EDIT: PICTURE DELETE END

							/********** SAVE BLOG TO DB **********/
							// If the blog-object has a blog_id at this point,
							// saveToDb() will EDIT the Dataset with that blog_id in the DB.
							if( !$blog->saveToDb($pdo) ) {
								// Error case
if(DEBUG)					echo "<p class='debug err'>Line <b>" . __LINE__ . "</b>: ERROR when saving the new blog! <i>(" . basename(__FILE__) . ")</i></p>";
								$blogErrorMessage = "Fehler beim Speichern des Beitrags!";

							} else {
								// Success case
if(DEBUG)					echo "<p class='debug ok'>Line <b>" . __LINE__ . "</b>: Blog with ID <b>'".	$blog->getBlog_id()	."'</b>. successfully saved to DB. <i>(" . basename(__FILE__) . ")</i></p>";
								$blogSuccessMessage = "Der Beitrag '".	$blog->getBlog_headline()	."' wurde erfolgreich gespeichert.";

								// if action is not "edit":
								if( $action != "edit" ){
									// fill $blog with new Blog Object
									// to empty the field presetting in Blog-Form
									// keep user data.
									$blog				=	new Blog( ['Category' => new Category(), 'User' => $blog->getUser()] );
								}


							} // BLOGEINTRAG IN DB SPEICHERN END

						} // FINAL FORM CHECK PART 2 (PICTURE UPLOAD) END

					} // FINAL FORM CHECK PART 1 (FORM FIELDS) END

				} // FORM PROCESSING NEW BLOG ENTRY END




/***************************************************************************************/


				/**************************************************/
				/********** FETCH ALL CATEGORIES FROM DB **********/
				/**************************************************/

if(DEBUG)	echo "<p class='debug'><b>Line " . __LINE__ . "</b>: Loading ALL categories from DB... <i>(" . basename(__FILE__) . ")</i></p>";

				// FETCH ALL CATEGORIES TO SHOW THEM IN THE CATEGORY SELECT FOR NEW BLOGS / EDIT BLOG
				if(!$categoriesArray = Category::getFromDb($pdo)){
					// Error case
if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: No category was fetched from DB.  <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				} else {
					// Success case
if(DEBUG)		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Successfully loaded Categories from database. <i>(" . basename(__FILE__) . ")</i></p>";
				}

/***************************************************************************************/

?>
