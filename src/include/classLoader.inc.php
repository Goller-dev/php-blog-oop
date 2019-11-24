<?php
/**********************************************************************************/


				function autoloadClasses($classname){
if(DEBUG_CL)		echo "<p class='debugClassloader'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "('$classname') <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					// Generate complete path for class files
					// 'class/Classname.class.php'
					$classPath = "class/$classname.class.php";
if(DEBUG_CL)		echo "<p class='debugClassloader'><b>Line " . __LINE__ . "</b>: \$classPath: $classPath <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					// Check whether the class file exists under the generated path $classPath.
					if( !file_exists($classPath) ) {
						// error case
if(DEBUG_CL)		echo "<p class='debugClassloader err'><b>Line " . __LINE__ . "</b>: ERROR: File $classPath could not be found. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					} else {
						// success case
						// Include class file
						if( require_once($classPath) ){
if(DEBUG_CL)			echo "<p class='debugClassloader ok'><b>Line " . __LINE__ . "</b>: File $classPath successfully included. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						}

					}

				}

/**********************************************************************************/
?>
