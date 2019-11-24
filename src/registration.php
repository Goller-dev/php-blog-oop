<?php
/**********************************************************************************/


				/**
				*
				* @file View for Start-Page (index.php)
				* @author Martin Goller <mail.mgoller@gmail.com>
				* @copyright Martin Goller 2019
				*
				*/

				/*********************************************/
				/********** INCLUDE CONTROLLER FILE **********/
				/*********************************************/

				require_once("controller/registration.controller.php");


/**********************************************************************************/
?>

<!DOCTYPE html>

	<html>

		<head>

			<link rel="stylesheet" href="css/debug.css">
			<link rel="stylesheet" href="css/main.css">

			<meta charset="utf-8">
			<title>Registrierung</title>
		</head>

		<body>

			<!-- ----------------------------- HEADER ------------------------------------- -->
			<?php require_once("include/pageElements/header.php") ?>

			<!--  MAIN HEADLINE  -->
			<h1 class="mainheadline" style="padding-bottom:3%">Registrierung</h1>
			<!--  MAIN HEADLINE END  -->

			<section>
				<main>
					<!--  REGISTRATION FORM  -->
					<article class="registration">
						<div>

						<form method="POST" class="registration">

						<input type="hidden" name="formsentRegistration">

						<!-- FIRSTNAME -->
						<label>Vorname:</label>
						<input  type="text" name="usr_firstname" placeholder="Pflichtfeld." value="<?=$regUser->getUsr_firstname()?>">
						<?php if($errorFirstname) : ?>
							<p class="error"><span><?= $errorFirstname ?></span></p>
						<?php endif ?>

						<!-- LASTNAME -->
						<label>Nachname:</label>
						<input  type="text" name="usr_lastname" placeholder="Pflichtfeld." value="<?=$regUser->getUsr_lastname()?>">
						<?php if($errorLastname) : ?>
							<p class="error"><span><?= $errorLastname ?></span></p>
						<?php endif ?>

						<!-- EMAIL -->
						<label>E-Mail:</label>
						<input type="text" name="usr_email" placeholder="Pflichtfeld." value="<?=$regUser->getUsr_email()?>">
						<?php if($errorEmail) : ?>
							<p class="error"><span><?= $errorEmail ?></span></p>
						<?php endif ?>

						<!-- CITY -->
						<label>Wohnort:</label>
						<input type="text" name="usr_city" placeholder="" value="<?=$regUser->getUsr_city()?>">

						<!-- PASSWORD -->
						<label>Passwort:</label>
						<input type="password" name="usr_password" placeholder="Passwort wählen." value="<?=$regUser->getUsr_password()?>">
						<!-- PASSWORDCHECK -->
						<label>Bestätigen:</label>
						<input type="password" name="usr_passwordcheck" placeholder="Passwort bestätigen." value="<?=$passwordCheck?>">
						<?php if($errorPassword) : ?>
							<p class="error"><span><?= $errorPassword ?></span></p>
						<?php endif ?>




						<!-- REGISTRATION SUBMIT -->
						<input type="submit" value="Registrieren">


					</form>
					</div>
				</main>
			<!--  REGISTRATION FORM END  -->

		</section>

		</body>

	</html>
