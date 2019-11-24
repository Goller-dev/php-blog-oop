<?php
/**************************************************************************************/


				/**
				*
				* 	Converts ISO date/time format to European date/time format
				*	and separates date from time (without seconds)
				*
				* 	@param String The ISO Date/Time
				*
				* 	@return Array (String "date", String "time")		The EU-Date and the Time
				*
				*/
				function isoToEuDateTime($value) {
// if(DEBUG_F)		echo "<p class='debugDateTime'><b>Line " . __LINE__ . ":</b> calling " . __FUNCTION__ . "($value) <i>(" . basename(__FILE__) . ")</i></p>\r\n";					
					
					if($value) {
						
						// possible input values
						// 2018-05-17 14:17:48
						// 2018-05-17
						
						// desired output values
						// 17.05.2018	// 14:17
						// 17.05.2018
						
						// check whether $value contains time or not
						if( strpos($value, " ") ) {
// if(DEBUG_F)			echo "<p class='debugDateTime'><b>Line " . __LINE__ . ":</b> \$value contains a time. <i>(" . basename(__FILE__) . ")</i></p>\r\n";					

							// seperate date and time
							$dateTimeArray = explode(" ", $value);
							/*
if(DEBUG_F)				echo "<pre class='debugDateTime'>Line <b>" . __LINE__ . ":</b> \$dateTimeArray: <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG_F)				print_r($dateTimeArray);					
if(DEBUG_F)				echo "</pre>";
							*/
							
							$date = $dateTimeArray[0];
// if(DEBUG_F)			echo "<p class='debugDateTime'><b>Line " . __LINE__ . ":</b> \$date: $date <i>(" . basename(__FILE__) . ")</i></p>\r\n";					
							// Split date into individual parts (day, month, year)
							$dateArray 	= explode("-", $date);

							$time = $dateTimeArray[1];
							// Cut the Seconds
							$time 		= substr($time, 0, 5);
// if(DEBUG_F)			echo "<p class='debugDateTime'><b>Line " . __LINE__ . ":</b> \$time: $time <i>(" . basename(__FILE__) . ")</i></p>\r\n";					

						} else {
// if(DEBUG_F)			echo "<p class='debugDateTime'><b>Line " . __LINE__ . ":</b> \$value contains no time. <i>(" . basename(__FILE__) . ")</i></p>\r\n";					
							// Split date into individual parts (day, month, year)
							$dateArray 	= explode("-", $value);
							$time 		= NULL;
						}
						/*				
if(DEBUG_F)			echo "<pre class='debugDateTime'>Line <b>" . __LINE__ . ":</b> \$dateTimeArray: <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG_F)			print_r($dateArray);					
if(DEBUG_F)			echo "</pre>";
						*/

						// convert date				
						$euDate = "$dateArray[2].$dateArray[1].$dateArray[0]";
// if(DEBUG_F)		echo "<p class='debugDateTime'><b>Line " . __LINE__ . ":</b> \$euDate: $euDate <i>(" . basename(__FILE__) . ")</i></p>\r\n";					
																	
					} else {
						
						// Writing NULL Values to Array Indexes
						$euDate 		= NULL;
						$time 		= NULL;
						
					}
					
					// Return date and time separately
					return array("date"=>$euDate, "time"=>$time);
					
				}


/**************************************************************************************/


				/**
				*
				* 	Converts EU/US/ISO date format to ISO date format
				*
				* 	@param String 	The EU/US/ISO formatted date
				*
				* 	@return String ISO formatted date
				*
				*/
				function toIsoDate($value) {
if(DEBUG_F)		echo "<p class='debugDateTime'>Line " . __LINE__ . ": calling " . __FUNCTION__ . "($value) <i>(" . basename(__FILE__) . ")</i></p>\r\n";					
					
					if( $value ) {
						// possible input values
						// 17.05.2018 | 05/17/2018 | 2018-05-17
						
						// desired output values
						// 2018-05-17
						
						// Check given date format
						if( stripos($value, ".") ) {
if(DEBUG_F)				echo "<p class='debugDateTime'><b>Line " . __LINE__ . "</b>: The given date is in EU format. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							
							$dateArray = explode(".", $value);					
							$day 		= $dateArray[0];
							$month 	= $dateArray[1];
							$year 	= $dateArray[2];
							
						} elseif( stripos($value, "/") ) {
if(DEBUG_F)				echo "<p class='debugDateTime'><b>Line " . __LINE__ . "</b>: The given date is in US format. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							
							$dateArray = explode("/", $value);					
							$day 		= $dateArray[1];
							$month 	= $dateArray[0];
							$year 	= $dateArray[2];
							
						} elseif( stripos($value, "-") ) {
if(DEBUG_F)				echo "<p class='debugDateTime'><b>Line " . __LINE__ . "</b>: The given date is already in ISO format. <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							
							$dateArray = explode("-", $value);
							$day 		= $dateArray[2];
							$month 	= $dateArray[1];
							$year 	= $dateArray[0];						
						}
						
						$isoDate = "$year-$month-$day";
if(DEBUG_F)			echo "<p class='debugDateTime'><b>Line " . __LINE__ . "</b>: \$isoDate: $isoDate <i>(" . basename(__FILE__) . ")</i></p>\r\n";
						
						return $isoDate;	
						
					} else {
						return NULL;
					}

				}


/**************************************************************************************/


				/**
				*
				*	Checks the validity of a transferred ISO/US/EU date.
				*
				*	@param String 	$value	- The ISO/US/EU date to be checked
				*
				*	@return Boolean 			- false for wrong format or invalid date; otherwise true
				*
				*/
				function validateDate($value) {
if(DEBUG_F)		echo "<p class='debugDateTime'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "($value) <i>(" . basename(__FILE__) . ")</i></p>\r\n";						
					
					$day 		= NULL;
					$month 	= NULL;
					$year 	= NULL;
										
					if( $value ) {
						
						// split date for checkdate()
					
						// ISO-Format
						if( stripos($value, "-") ) {
							$dateArray = explode("-", $value);
							
							$day 		= $dateArray[2];
							$month 	= $dateArray[1];
							$year 	= $dateArray[0];
						
						// EU-Format
						} elseif( stripos($value, ".") ) {
							$dateArray = explode(".", $value);
							
							$day 		= $dateArray[0];
							$month 	= $dateArray[1];
							$year 	= $dateArray[2];
						
						// US-Format
						} elseif( stripos($value, "/") ) {
							$dateArray = explode("/", $value);
							
							$day 		= $dateArray[1];
							$month 	= $dateArray[0];
							$year 	= $dateArray[2];
						}

if(DEBUG_F)			echo "<pre class='debugDateTime'>Line <b>" . __LINE__ . "</b> <i>(" . basename(__FILE__) . ")</i>:<br>\r\n";					
if(DEBUG_F)			print_r($dateArray);					
if(DEBUG_F)			echo "</pre>";
						
					}
					
					
					// Check date components for completeness and 
					// Check date for valid gregorian calendar date
					if( (!$day OR !$month OR !$year) OR !checkdate($month, $day, $year) ) {
						// Error case
						return false;
						
					} else {
						// Success case
						return true;
					}					
				}


/**************************************************************************************/
?>