<?php
/*******************************************************************************************/


				/*****************************************/
				/********** TRAIT AUTOCONSTRUCT **********/
				/*****************************************/

				/*
					Ein Trait ist eine Art (im philosophischen Sinn) abstrakte Klasse, die, unabhängig
					von Vererbungskonstrukten in jeder Klasse verwendet werden kann.<br>
					Der Sinn eines Traits besteht darin, beispielswesie in ihm notierte Methoden
					in anderen Klassen zu verwenden, so als wären sie in der jeweiligen Klasse
					selbst notiert. Man könnte also sagen, Methoden innerhalb eines Traits verhalten
					sich ähnlich wie Funktionen, die in Include-Dateien ausgelagert sind. Nur eben
					für Klassen.
					Ein Trait dient also beispielsweise dazu, Code auszulagern, der in verschiedenenen
					Klassen eingebunden werden kann.

					Aus einem Trait lassen sich keinerlei Objekte instanziieren.
				*/


/*******************************************************************************************/


				/**
				*
				* Trait for automated creation of either empty or filled objects
				* Also filles given object automatically with attribute's data
				*
				*/

				trait autoConstruct {

					/*********************************/
					/********** KONSTRUKTOR **********/
					/*********************************/


					/**
					*
					* @constructor
					*
					* Creates new object from calling Class and calls setAttributes() method
					* forwarding either filled or empty array
					*
					* @param [Array $attributesArray=array()]	associative array of object attributes
					* 														array must contain indexes named exactly
					* 														the same as the attribute's names of the
					* 														calling object's class
					*
					*/

					public function __construct( $attributesArray = array() ) {
if(DEBUG_T)			echo "<h3 class='debugTrait'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "()  (<i>" . basename(__FILE__) . "</i>)</h3>";

						// Aufruf der Klassenmethode setAttributes()
						$this->setAttributes($attributesArray);

					}

					/***********************************************************/


					/******************************/
					/********** METHODEN **********/
					/******************************/

					/********** FILLS ATTRIBUTES OF OBJECT AUTOMATICALLY BY ARRAY **********/
					/**
					*
					* FILLS ATTRIBUTES OF AN GIVEN OBJECT AUTOMATICALLY BY ARRAY
					* Creates virtual setters from the array's indexes, checks if the virtual setters exist
					* and calls them one by one and forwards the value corresponding to the index
					*
					* @param Array $attributesArray		associative array of object attributes
					* 												array must contain indexes named exactly
					* 												the same as the attribute's names of the
					* 												calling object's class
					*
					*/

					public function setAttributes ( array $attributesArray ) {
if(DEBUG_T)			echo "<h3 class='debugTrait'><b>Line  " . __LINE__ .  "</b>: Aufruf " . __METHOD__ . "() (<i>" . basename(__FILE__) . "</i>)</h3>\r\n";

						// Prüfen, ob Array Werte enthält
						if( $attributesArray ){
							// Objekt mit Werten füllen
							foreach( $attributesArray AS $key => $value){
// if(DEBUG_T)					echo "<p class='debugTrait'><b>Line " . __LINE__ . "</b>: [$key]: '$value' <i>(" . basename(__FILE__) . ")</i></p>\r\n";

								// Aus $key einen Setternamen generieren (z.B. "SetProd_artist()")
								$setterName	=	"set" . ucfirst($key);
if(DEBUG_T)					echo "<p class='debugTrait'><b>Line " . __LINE__ . "</b>: \$setterName: $setterName [<i>(" . basename(__FILE__) . ")</i></p>\r\n";

								//  Prüfen, ob es tatsächlich einen Setter mit diesem Namen gibt
								if( method_exists( $this, $setterName ) ){
									// Wenn der Setter existiert, aufrufen und Attributswert übergeben
									$this->$setterName($value);
								}
							}
						}

if(DEBUG_T)			echo "<pre class='debugTrait'><b>Line  " . __LINE__ .  "</b> <i>(" . basename(__FILE__) . ")</i>:<br>";
if(DEBUG_T)			print_r($this);
if(DEBUG_T)			echo "</pre>";
					}


					/***********************************************************/

				} // trait autoConstruct end


/*******************************************************************************************/
?>
