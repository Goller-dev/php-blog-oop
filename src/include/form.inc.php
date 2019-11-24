<?php
/*********************************************************************************/


				/**
				*
				*	Cleans the given String of whitespaces and
				*	Defuses potentially dangerous control characters (< > & '' "")
				*
				*	@param	String $value		- String to be defused and cleaned
				*
				*	@return	String				- The cleaned and defused string
				*
				*/
				function cleanString($value) {
if(DEBUG_F)		echo "<p class='debugCleanString'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "('$value') <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					// trim() removes at the beginning and at the END of a string all
					// so-called whitespaces (spaces, tabs, line breaks)
					$value = trim($value);

					// If the string already contains coded HTML snippets (e.g. when reading
					// and defusing an already defused string from the DB),
					// these are first converted back to avoid &amp;apos; constructs or the like
					$value = htmlspecialchars_decode($value, ENT_QUOTES | ENT_HTML5);

					// htmlspecialchars() defuses HTML control characters like < > & '' """
					// and replace them with, &lt;, &gt;, &amp;, &apos; &quot;
					// ENT_QUOTES | ENT_HTML5 additionally replaces ' with &#039;
					$value = htmlspecialchars( $value, ENT_QUOTES | ENT_HTML5 );

					// So that cleanString() does not change NULL values in empty strings,
					// it is checked whether $value has a valid value at all.
					if( !$value ) {
						$value = NULL;
					}

					return $value;
				}


/*********************************************************************************/


				/**
				*
				*	Prüft einen übergebenen Wert auf Leerstring, minimal nötige und maximal erlaubte Länge
				*
				*	@param	String $value			- Der zu prüfende String
				*	@param	[Int $minLength]		- Die erforderliche Mindestlänge
				*	@param	[Int $maxLength]		- Die erlaubte Maximallänge
				*
				*	@return	String/NULL				- Einen String mit Fehlermeldung, ansonsten NULL
				*
				*/
				function checkInputString($value, $minLength=MIN_INPUT_LENGTH, $maxLength=MAX_INPUT_LENGTH) {
if(DEBUG_F)		echo "<p class='debugCheckInputString'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "('$value', [" . $minLength . " | " . $maxLength . "]) <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					$errorMessage = NULL;

					// 1. $value auf Leerstring prüfen
					if( !$value ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";

					// 2. $value auf Mindestlänge prüfen
					} elseif( mb_strlen($value) < $minLength ) {
						$errorMessage = "Muss mindestens $minLength Zeichen lang sein!";

					// 3. $value auf Maximallänge prüfen
					} elseif( mb_strlen($value) > $maxLength ) {
						$errorMessage = "Darf maximal $maxLength Zeichen lang sein!";
					}

					return $errorMessage;
				}


/*********************************************************************************/


				/**
				*
				*	Prüft einen übergebenen String auf Leerstring und auf eine valide Email-Adresse
				*
				*	@param 	String $value 		- Der zu prüfende String
				*
				*	@return	String/NULL			- Einen String mit Fehlermeldung, ansonsten NULL
				*
				*/
				function checkEmail($value) {
if(DEBUG_F)		echo "<p class='debugCheckEmail'><b>Line  " . __LINE__ .  "</b>: calling " . __FUNCTION__ . "('$value') <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					$errorMessage = NULL;

					// 1. $value auf Leerstring prüfen
					if( !$value ) {
						$errorMessage = "Dies ist ein Pflichtfeld!";

					// 2. $value auf valide Email-Adresse prüfen
					} elseif( !filter_var( $value, FILTER_VALIDATE_EMAIL ) ) {
						$errorMessage = "Dies ist keine gültige Email-Adresse!";
					}

					return $errorMessage;

				}


/*********************************************************************************/


				/**
				*
				*	Prüft ein hochgeladenes Bild auf MIME-Type, Datei- und Bildgröße
				*	Speichert das erfolgreich geprüfte Bild unter einem zufällig generierten einmaligen Dateinamen
				*
				*	@param Array 	$uploadedImage												- Die Bildinformationen aus $_FILES
				*	@param [String $prefix]														- Optionales Präfix für den Dateinamen
				*	@param [Int 	$maxWidth = IMAGE_MAX_WIDTH]							- Die maximal erlaubte Bildbreite in PX
				*	@param [Int 	$maxHeight = IMAGE_MAX_HEIGHT]						- Die maximal erlaubte Bildhöhe in PX
				*	@param [Int 	$maxSize = IMAGE_MAX_SIZE]								- Die maximal erlaubte Dateigröße in Bytes
				*	@param [Array 	$allowedMimeTypes = IMAGE_ALLOWED_MIMETYPES]		- Whitelist der erlaubten MIME-Types
				*	@param [String $uploadPath = IMAGE_UPLOAD_PATH]						- Das Speicherverzeichnis auf dem Server
				*
				*	@return Array { "imageError" => String/NULL 	- Fehlermeldung im Fehlerfall,
				*						 "imagePath"  => String 		- Der Speicherpfad auf dem Server }
				*
				*/
				function imageUpload( 	$uploadedImage,
												$prefix				= NULL,
												$maxWidth			= IMAGE_MAX_WIDTH,
												$maxHeight			= IMAGE_MAX_HEIGHT,
												$maxSize				= IMAGE_MAX_SIZE,
												$allowedMimeTypes	= IMAGE_ALLOWED_MIMETYPES,
												$uploadPath			= IMAGE_UPLOAD_PATH
											) {
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: calling imageUpload() <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					/*
						Das Array $_FILES['avatar'] bzw. $uploadedImage enthält:
						Den Dateinamen [name]
						Den generierten (also ungeprüften) MIME-Type [type]
						Den temporären Pfad auf dem Server [tmp_name]
						Die Dateigröße in Bytes [size]
					*/
					/*
if(DEBUG_F)		echo "<pre class='debugImageUpload'>\r\n";
if(DEBUG_F)		print_r($uploadedImage);
if(DEBUG_F)		echo "</pre>\r\n";
					*/


					/********** BILDINFORMATIONEN SAMMELN **********/

					// Dateiname
					$fileName = cleanString($uploadedImage['name']);
					// If no prefix was given, generate a random prefix
					if(!$prefix) {
						$prefix = rand(1,999999) . str_shuffle("abcdefghijklmnopqrstuvwxyz");
					} else {
						// clean given prefix and change to lower case
						$prefix = mb_strtolower(cleanString($prefix));

					}
					// $prefix wird angefügt
					$fileName = $prefix."-".$fileName;
					// ggf. Leerzeichen durch "-" ersetzen
					$fileName = str_replace(" ", "-", $fileName);
					// Dateinamen in Kleinbuchstaben umwandeln
					$fileName = mb_strtolower($fileName);
					// Umlaute ersetzen
					$fileName = str_replace( array("ä","ö","ü","ß","&apos;","&ent;","&amp;"), array("ae","oe","ue","ss","-","-","-"), $fileName );

					// replace anything except "a-z" and "0-9" with "-"
					$fileName = preg_replace( '/[^a-z0-9]/', '-', $fileName);

					// generate a unique filename with Microtime
					$fileTarget = $uploadPath . time() . "-" . $fileName;

					// Delete all double- ("--") in filename
					do {
					$fileTarget= str_replace("--","-", $fileTarget);
					}
					while(strpos($fileTarget,"--")	);

					// generate file endings (like ".jpg") from allowed mime types and save in string
					$dotFileExtensions =  str_replace( "image/",".", $allowedMimeTypes );
					$minusFileExtensions =  str_replace( ".","-", $dotFileExtensions );
					// replace "-file_extension (-jpg)" with ".file_extension (.jpg)"
					$fileTarget = str_replace($minusFileExtensions, $dotFileExtensions, $fileTarget);

					// Filesize
					$fileSize = $uploadedImage['size'];

					// Temporary path on the server
					$fileTemp = $uploadedImage['tmp_name'];

if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileName: $fileName <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileSize: " . round($fileSize/1024, 2) . "kB <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileTemp: $fileTemp <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$fileTarget: $fileTarget <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					// Genauere Informationen zum Bild holen
					$imageData = @getimagesize($fileTemp);

					/*
						Die Funktion getimagesize() liefert bei gültigen Bildern ein Array zurück:
						Die Bildbreite in PX [0]
						Die Bildhöhe in PX [1]
						Einen für die HTML-Ausgabe vorbereiteten String für das IMG-Tag
						(width="480" height="532") [3]
						Die Anzahl der Bits pro Kanal ['bits']
						Die Anzahl der Farbkanäle (somit auch das Farbmodell: RGB=3, CMYK=4) ['channels']
						Den echten(!) MIME-Type ['mime']
					*/
					/*
if(DEBUG_F)		echo "<pre class='debugImageUpload'>\r\n";
if(DEBUG_F)		print_r($imageData);
if(DEBUG_F)		echo "</pre>\r\n";
					*/

					$imageWidth 	= $imageData[0];
					$imageHeight 	= $imageData[1];
					$imageMimeType = $imageData['mime'];
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$imageWidth: $imageWidth px <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$imageHeight: $imageHeight px <i>(" . basename(__FILE__) . ")</i></p>\r\n";
if(DEBUG_F)		echo "<p class='debugImageUpload'><b>Line  " . __LINE__ .  "</b>: \$imageMimeType: $imageMimeType <i>(" . basename(__FILE__) . ")</i></p>\r\n";


					/********** BILD PRÜFEN **********/

					// MIME-Type prüfen
					// Whitelist mit erlaubten Bildtypen
					// $allowedMimeTypes = array("image/jpg", "image/jpeg", "image/gif", "image/png");

					if( !in_array($imageMimeType, $allowedMimeTypes) ) {
						$errorMessage = "Dies ist kein gültiger Bildtyp!";

					// Maximal erlaubte Bildhöhe
					} elseif( $imageHeight > $maxHeight ) {
						$errorMessage = "Die Bildhöhe darf maximal $maxHeight Pixel betragen!";

					// Maximal erlaubte Bildbreite
					} elseif( $imageWidth > $maxWidth ) {
						$errorMessage = "Die Bildbreite darf maximal $maxWidth Pixel betragen!";

					// Maximal erlaubte Dateigröße
					} elseif( $fileSize > $maxSize ) {
						$errorMessage = "Die Dateigröße darf " . round($maxSize/1024, 2) . "kB nicht überschreiten!";

					// Wenn es keinen Fehler gab
					} else {
						$errorMessage = NULL;
					}


					/********** ABSCHLIESSENDE BILDPRÜFUNG **********/

					if( $errorMessage ) {
						// Error case
if(DEBUG_F)			echo "<p class='debugImageUpload err'><b>Line  " . __LINE__ .  "</b>: $errorMessage <i>(" . basename(__FILE__) . ")</i></p>\r\n";

					} else {
						// Success case
if(DEBUG_F)			echo "<p class='debugImageUpload ok'><b>Line  " . __LINE__ .  "</b>: Die Bildprüfung ergab keine Fehler. <i>(" . basename(__FILE__) . ")</i></p>\r\n";


						/********** BILD SPEICHERN **********/

						if( !@move_uploaded_file($fileTemp, $fileTarget) ) {
							// Error case
if(DEBUG_F)				echo "<p class='debugImageUpload err'><b>Line  " . __LINE__ .  "</b>: Fehler beim Speichern des Bildes auf dem Server! <i>(" . basename(__FILE__) . ")</i></p>\r\n";
							$errorMessage = "Fehler beim Speichern des Bildes! Bitte versuchen Sie es später noch einmal.";

						} else {
							// Success case
if(DEBUG_F)				echo "<p class='debugImageUpload ok'><b>Line  " . __LINE__ .  "</b>: Bild wurde erfolgreich auf dem Server gespeichert. <i>(" . basename(__FILE__) . ")</i></p>\r\n";

						} // BILD SPEICHERN END

					} // ABSCHLIESSENDE BILDPRÜFUNG END


					/********** GGF: FEHLERMELDUNG UND BILDPFAD ZRUÜCKGEBEN **********/

					return array("imageError" => $errorMessage, "imagePath" => $fileTarget);

				}


/*********************************************************************************/
?>
