<?php
/*******************************************************************************************/


				/********************************/
				/********** CLASS ROLE **********/
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
				*	Class represents a Role (of an User)
				*
				*
				*/
				class Role {

					/********************************/
					/********** ATTRIBUTES **********/
					/********************************/

					// Attributes do not have to be initialized within the class definition.

					private $rol_id;
					private $rol_name;


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

					/********** ROL_ID **********/
					public function getRol_id() {
						return $this->rol_id;
					}
					public function setRol_id($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: rol_id: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->rol_id = cleanString($value);
						}
					}

					/********** ROL_NAME **********/
					public function getRol_name() {
						return $this->rol_name;
					}
					public function setRol_name($value) {
						// Check if the passed value is a string
						if( !is_string($value) ) {
if(DEBUG_C)				echo "<p class='debugClass err'><b>Line " . __LINE__ . "</b>: rol_name: Must be DATATYPE 'String'! <i>(" . basename(__FILE__) . ")</i></p>";
						} else {
							$this->rol_name = cleanString($value);
						}
					}


					/***********************************************************/


					/*****************************/
					/********** METHODS **********/
					/*****************************/



				}

/*******************************************************************************************/
?>
