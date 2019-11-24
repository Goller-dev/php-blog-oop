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

				require_once("controller/header.controller.php");


/**********************************************************************************/
?>

<!-- ---------------------------------- HEADER ---------------------------------- -->
<header id="pageHead" class="header">

	<?php if( !isset($_SESSION['userObject']) ): ?>

		<!-- -------- Login Form -------- -->
		<div class="login">
			<form action="#pageHead" method="POST">
				<input type="hidden" name="formsentLogin">
				<input type="test" name="loginName" placeholder="Email" value="<?php if(isset($user) )echo $user->getUsr_email()	?>">
				<input type="password" name="loginPassword" placeholder="Password">
				<input type="submit" value="Login">
				<!-- LOGIN ERROR MESSAGE -->
			</form>
			<?php if( $loginMessage ): ?>
				<p class="error"><?= $loginMessage ?></p>
			<?php endif ?>
		</div>

	</header>
	<?php else: ?>
		<!-- -------- Links -------- -->
		<div class="login">
			<p class="welcome"><span>Hallo <?php if(isset($user) )echo $user->getFullname() ?></span>
				<a href="?action=logout">Logout</a>
				<a href="index.php#pageHead">Startseite</a>
				<a href="dashboard.php">Erstellen</a>
				<a href="dashboard.php?action=showCategory&id=">Verwalten</a>
			</p>
		</div>

	</header>
	<?php endif ?>
<!-- ------------------------------- HEADER END --------------------------------- -->
