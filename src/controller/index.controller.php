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
					// 'User-Object' in $User is only used for the login
					// Write 'userObject-Data' from session to new 'User-Object'
				} elseif ($user = clone $_SESSION['userObject'] ) {
if(DEBUG) 		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: User ". $user->getFullname() ." is logged in. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				} else {
if(DEBUG) 		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR while creating User with \$_SESSION['userObject'].  <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				}



/**************************************************************************************************/


				/******************************************/
				/********** INITIALIZE VARIABLES **********/
				/******************************************/


				// Initialize empty instance of a blog object
				$showBlog				= new Blog();
				// Initialize empty instance of a Category object
				$showCategory			= new Category();

				$loginMessage	= NULL;

				$pageTitle		= "Spartan CMS - Startseite";

				/********** ESTABLISH DB CONNECTION **********/

				$pdo				= dbConnect();


/**************************************************************************************************/


				/********************************************/
				/********** PROCESS URL PARAMETERS **********/
				/********************************************/

				// Step 1 URL: Check if parameters were transferred
				if( isset($_GET['action']) ) {
if(DEBUG)		echo "<p class='debug hint'>Line <b>" . __LINE__ . "</b>: URL parameter 'action' was passed... <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 2 URL: Read values, defuse, DEBUG output
					$action = cleanString($_GET['action']);
if(DEBUG)		echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: \$action = $action <i>(" . basename(__FILE__) . ")</i></p>";

					// Step 3 URL: Branch if necessary

					/********** CATEGORY FILTER **********/
					if( $action == "showCategory" ) {
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Category filter is active... <i>(" . basename(__FILE__) . ")</i></p>";

						// Write cat_id in category object
						// If 'Category' contains a cat_id, only blogs from this category are fetched
						$showCategory->setCat_id($_GET['id']);
if(DEBUG)			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: ID in Category-Object: ". $showCategory->getCat_id() ." <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						// STEP 4 URL: Further processing of Data after fetching Blog(s)


					} // CATEGORY FILTER END

					/********** BLOG FILTER **********/
					elseif( $action == "showBlog" ) {
if(DEBUG)			echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Blog filter is active... <i>(" . basename(__FILE__) . ")</i></p>";

						// Write blog_id in blog object
						// If 'Category' contains an ID, only this category is fetched
						$showBlog->setBlog_id($_GET['id']);
if(DEBUG)			echo "<p class='debug'><b>Line " . __LINE__ . "</b>: ID in Blog-Object: ". $showBlog->getBlog_id() ." <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						// STEP 4 URL: Further processing of Data after fetching Blog(s)

					} // BLOG FILTER END

				} // PROCESS URL PARAMETERS END


/***************************************************************************************/

				/************************************************/
				/************ FETCH BLOG(S) FORM DB *************/
				/************************************************/

if(DEBUG)	echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Loading Blogs... <i>(" . basename(__FILE__) . ")</i></p>";

				// If $blog->getCategoryId() returns a cat_id, only corresponding blogs are fetched from the DB
				// If $blog->getBlog_id() returns a blog_id, only that blog will be fetched
				if(!$blogEntriesArray = Blog::getFromDb($pdo, $showCategory->getCat_id(), $showBlog->getBlog_id() )) {
					// Error case
if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: No blogs were fetched! <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				} else {
					// Success case
if(DEBUG)		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Fetching the blogs was performed successfully.  <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					/**********************************************/
					/************ DYNAMIC PAGE TITLES *************/
					/**********************************************/

					// check if $showCategory contains a cat_id
					if( $showCategory->getCat_id() ) {
						// set page Title to selected Categorys Title
						$pageTitle = $blogEntriesArray[0]->getCategory()->getCat_title();
					}

					// check if $showBlog contains a blog_id
					elseif ( $showBlog->getBlog_id() ) {
						// set page Title to selected Blogs blog_headline – cat_title
						$pageTitle = $blogEntriesArray[0]->getBlog_headline() ." – ". $blogEntriesArray[0]->getCategory()->getCat_title() ;
					}

				}


/***************************************************************************************/


				/**********************************************/
				/********** FETCH CATEGORIES FROM DB **********/
				/**********************************************/

				// those Categories are used to create the main nav
if(DEBUG)	echo "<p class='debug'>Line <b>" . __LINE__ . "</b>: Loading categories from DB... <i>(" . basename(__FILE__) . ")</i></p>";

				if(!$categoriesArray = Category::getFromDb($pdo)){
					// Error case
if(DEBUG)		echo "<p class='debug err'><b>Line " . __LINE__ . "</b>: ERROR: No category was fetched from DB. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

				} else {
					// Success case
if(DEBUG)		echo "<p class='debug ok'><b>Line " . __LINE__ . "</b>: Successfully loaded Categories from database. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
				}




/***************************************************************************************/







?>
