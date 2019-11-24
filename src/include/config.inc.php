<?php
/*********************************************************************************/


				/***********************************/
				/********** CONFIGURATION **********/
				/***********************************/

				/*
					Constants are defined in PHP using the define() function.
					Unlike variables, constants do not have a $ prefix.
					Usually constants are written completely in CAPITAL_LETTERS.
				*/


				/********** DB CONNECTION **********/
				define("DB_SYSTEM",						"mysql");
				define("DB_HOST",							"localhost");
				define("DB_NAME",							"blog_oop");
				define("DB_USER",							"root");
				define("DB_PWD",							"");


				/********** FORMULAR CONFIGURATION **********/
				define("MIN_INPUT_LENGTH", 			2);
				define("MAX_INPUT_LENGTH", 			256);


				/********** IMAGE UPLOAD CONFIGURATION **********/
				define("IMAGE_MAX_WIDTH",				2056);
				define("IMAGE_MAX_HEIGHT",				2056);
				define("IMAGE_MAX_SIZE",				256*1024);
				define("IMAGE_ALLOWED_MIMETYPES",	array("image/jpg", "image/jpeg", "image/gif", "image/png"));



				/********** STANDARD PATHS CONFIGURATION **********/
				define("IMAGE_UPLOAD_PATH",			"uploads/blogimages/");


				/********** DEBUGGING **********/
				define("DEBUG", 							false);		// Debugging for main php document
				define("DEBUG_F", 						false);      // Debugging for functions
				define("DEBUG_DB", 						false);      // Debugging for db-functions
				define("DEBUG_C", 						false);      // Debugging for classes
				define("DEBUG_CL", 						false);     	// Debugging for classLoader
				define("DEBUG_T", 						false);     	// Debugging for traits


/*********************************************************************************/
?>
