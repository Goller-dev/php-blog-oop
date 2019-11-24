<?php
/**********************************************************************************/


				/**
				*
				* @file View for Dashboard-Page (dashboard.php)
				* @author Martin Goller <mail.mgoller@gmail.com>
				* @copyright Martin Goller 2019
				*
				*/

				/*********************************************/
				/********** INCLUDE CONTROLLER FILE **********/
				/*********************************************/

				require_once("controller/dashboard.controller.php");


/**********************************************************************************/
?>

<!DOCTYPE html>

<html>

	<head>
		<meta charset="utf-8">
		<title>Spartan CMS - Dashboard</title>
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="css/debug.css">
	</head>

	<body>
		<!--------------------------------- HEADER ----------------------------------------->
		<?php require_once("include/pageElements/header.php") ?>
		<!-------------------------------------------------------------------------------->

		<h1 class="mainheadline"><?= $pageMainHeadline ?></h1>
		<section class="fullwidth">
			<?php if( $blogSuccessMessage OR $catSuccessMessage ): ?>
				<!-- FORM POPUP MESSAGES CAT & BLOG -->
				<popupBox class="success">
					<p class="success"><?= $blogSuccessMessage ?></p>
					<p class="success"><?= $catSuccessMessage ?></p>
					<a class="button" onclick="document.getElementsByTagName('popupBox')[0].style.display = 'none'">OK</a>
				</popupBox>
				<?php endif ?>

			<?php if( $blogErrorMessage OR $noPermissionMessage ): ?>
				<popupBox class="error">
					<p class="error"><?= $blogErrorMessage ?></p>
					<p class="error"><?= $noPermissionMessage ?></p>
					<a class="button" onclick="document.getElementsByTagName('popupBox')[0].style.display = 'none'">OK</a>
				</popupBox>
			<?php endif ?>


			<?php if( $action == ("showCategory") ): ?>
				<!--------------------------- SHOW CATEGORY TABLE -------------------------------->
				<article class="dashboardarticle">
					<h2>Kategorien:</h2>
					<div>
					<?php if( $categoriesArray ): ?>
						<?php foreach( $categoriesArray AS $categoryObject ): ?>
							<div class="underlined">
								<p><?= $categoryObject->getCat_name() ?></p>
								<p><a href='?action=showCategory&id=<?= $categoryObject->getCat_id() ?>#selectblog'>Beiträge zeigen</a></p>
								<p><a class="editlink" href="?action=edit&cat_id=<?= $categoryObject->getCat_id() ?>#categoryform">bearbeiten</a></p>
								<p><a class="deletelink" href="?action=delete&cat_id=<?= $categoryObject->getCat_id() ?>#delcat">Löschen</a></p>
							</div>
						<?php endforeach ?>
						<div class="underlined">
							<p class="fullwidth"><a href='<?= $_SERVER['SCRIPT_NAME'] ?>?action=showCategory'>Alle Beiträge anzeigen</a></p>
						</div>
					<?php else : // of: if( $categoriesArray ): ?>
						<p class="info">Noch keine Kategorien vorhanden.</p>
					<?php endif ?>
					</div>
				</article>

				<article id="selectblog" class="dashboardarticle">
				<div>
					<h2>Beiträge:</h2>

					<?php if( $blogEntriesArray ): ?>

						<?php foreach( $blogEntriesArray AS $blogObject ): ?>
							<div class="underlined">
								<p class="dashboardblogheadline">"<b><?= $blogObject->getBlog_headline() ?></b>"</p>
								<p><a class="editlink" href="?action=edit&blog_id=<?= $blogObject->getBlog_id() ?>#blogform">Bearbeiten</a></p>
								<p><a class="deletelink" href="?action=delete&blog_id=<?= $blogObject->getBlog_id() ?>#delblog">Löschen</a></p>
								<p class="fullwidth"><?= mb_substr($blogObject->getBlog_content(),0,180) // cut string after 180 ?>...</p>

							</div>
						<?php endforeach ?>
						</p>
					<?php else: ?>
						<p class="info">Noch keine Blogeinträge vorhanden.</p>
					<?php endif ?>

				</div>
				</article>

			<?php endif ?>



			<?php if(  !$action OR ( $action == "edit" AND isset($blogFilter) )  ):
			// don't show BLOG FORM in 'delete' Mode or before a blog was picked in edit mode?>
				<!-- 			***** BLOG FORM ***** 			-->
				<article id="blogform" class="<?php echo !$action ? "blogform" : "fullwidth" //  ternary operator: if (!$action) echo "blogform" else echo "fullwidth"?>">
					<div>
					<!--------------------------- DYNAMIC H2 -------------------------------->
					<h2 class="dashboard"><?= $blogArticleHeadline ?></h2>
					<!-- Form Blog-Eintrag erstellen -->
					<form action="#blogform" method="POST" enctype="multipart/form-data" class="dashboard">
						<input class="dashboard" type="hidden" name="formsentBlogEntry">

						<!-- SELECT A CATEGORY -->
						<select class="dashboard bold" name="cat_id">

							<!------------ STATIC SELECT: PLEASE CHOOSE: ----------------->
							<option value=''>Wähle eine Kategorie!</option>
							<option value='' disabled>------------------------------</option>

							<!-- CATEGORIES FROM DB -->
							<?php foreach($categoriesArray AS $catSelectOption): ?>
								<!-- show the new value after edit -->
								<?php if( $blog->getCategoryId() == $catSelectOption->getCat_id() OR ( $action != "edit" AND $blog->getCategoryName() == $catSelectOption->getCat_name() ) ): ?>
									<option value='<?= $catSelectOption->getCat_id() ?>' selected><?= $catSelectOption->getCat_name() ?></option>
								<?php else: ?>
									<option value='<?= $catSelectOption->getCat_id() ?>'><?= $catSelectOption->getCat_name() ?></option>
								<?php endif ?>
							<?php endforeach ?>
						</select>
						<!-- CATEGORY ERROR MESSAGE -->
						<?php if($errorCategory) : ?>
							<p class="error"><span><?= $errorCategory ?></span></p>
						<?php endif ?>

						<!-- BLOG-FORM HEADLINE -->
						<input class="dashboard" type="text" name="blog_headline" placeholder="Überschrift" value="<?= $blog->getBlog_headline() ?>">
						<!-- HEADLINE ERROR MESSAGE -->
						<?php if($errorHeadline) : ?>
							<p class="error"><span><?= $errorHeadline ?></span></p>
						<?php endif ?>

						<!------------ PICTURE UPLOAD: ----------------->
						<?php if( $action == "edit" AND $blog->getBlog_image() ): ?>
							<p><img src="<?= $blog->getBlog_image() ?>"></img></p>
							<label class="container">Bild löschen
							  <input type="checkbox" name="delete_blog_image" value="delete">
							  <span class="checkmark"></span>
							</label>
						<?php endif?>
						<label>Bild hochladen:

						<!-- PICTURE ALIGNMENT -->
						<input type="file" name="blog_image">
						</label>
						<label>Bildausrichtung:
						<select class="alignment" name="blog_imageAlignment">
							<option value="fleft" <?php if($blog->getBlog_imageAlignment() == "fleft") echo "selected"?>>align left</option>
							<option value="fright" <?php if($blog->getBlog_imageAlignment() == "fright") echo "selected"?>>align right</option>
						</select>
						</label>
						<!-- PICTURE UPLOAD ERROR MESSAGE -->
						<?php if($errorImageUpload) : ?>
							<p class="error"><span><?= $errorImageUpload ?></span></p>
						<?php endif ?>

						<!------------ CONTENT ----------------->
						<textarea class="dashboard" name="blog_content" placeholder="Blog Beitragstext"><?= $blog->getBlog_content()?></textarea><br>

						<!-- CONTENT ERROR MESSAGE -->
						<?php if($errorContent) : ?>
							<p class="error"><span><?= $errorContent ?></span></p>
						<?php endif ?>

						<input class="dashboard" type="submit" value="<?= $blogFormInputValue ?>">
					</form>
					<!-------------------------------------------------------------------------------->
					</div>
				</article>
			<?php endif ?>

			<?php if(  !$action OR ( $action == "edit" AND isset($catFilter) )  ):
			// don't show CATEGORY FORM in 'delete' Mode or before a category was picked ?>
			<article id="categoryform" class="<?php if(!$action) echo "categoryform"; else echo "fullwidth" ?>">
				<div class="felft dashboard">

					<h2 class="dashboard"><?= $catArticleHeadline ?></h2>
					<?= $catErrorMessage ?>

					<!-- 			***** CATEGORY FORM ***** 			-->
					<form class="dashboard" action="#categoryform" method="POST">
						<input class="dashboard" type="hidden" name="formsentCategory">

						<!-- CATEGORY NAME -->
						<input class="dashboard" type="text" name="cat_name" placeholder="Name der Kategorie" value="<?= $category->getCat_name() ?>">
						<!-- CATEGORY NAME ERROR MESSAGE -->
						<?php if($errorCatName) : ?>
							<p class="error"><span><?= $errorCatName ?></span></p>
						<?php endif ?>

						<!-- CATEGORY TITLE -->
						<input class="dashboard" type="text" name="cat_title" placeholder="Titel der Kategorie" value="<?= $category->getCat_title() ?>">
						<!-- CATEGORY TITLE ERROR MESSAGE -->
						<?php if($errorCatTitle) : ?>
							<p class="error"><span><?= $errorCatTitle ?></span></p>
						<?php endif ?>

						<!-- CATEGORY DESCRIPTION -->
						<textarea class="dashboard" name="cat_description" placeholder="Einleitungstext"><?= $category->getCat_description() ?></textarea>
						<!-- CATEGORY DESCRIPTION ERROR MESSAGE -->
						<?php if($errorCatDescription) : ?>
							<p class="error"><span><?= $errorCatDescription ?></span></p>
						<?php endif ?>

						<!-- CATEGORY SUBMIT -->
						<input class="dashboard" type="submit" value="<?= $catFormInputValue ?>">
					</form>
					<!-- 			***** CATEGORY FORM END ***** 			-->
					<!-------------------------------------------------------------------------------->

				</div>
			</article>
			<?php elseif($action == "delete") : // show selected Category or selected Blog in Delete view?>

				<?php if( isset($catFilter) ): // Show selected CATEGORY ?>
					<article id="delcat" class="fleft">
						<div class="felft">
							<h4 class="fullwidth"><?= $deleteCheckHeadline ?></h4>
							<p class="error"><?= $deleteCheckMessage ?></p>
							<br>
							<p><b><?= $category->getCat_name() ?></b></p>
							<p><?= $category->getCat_title() ?></p>
							<p><?= $category->getCat_description() ?></p>
							<div class="underlined">
								<?php if( $category->getCat_id() ) : // Show edit this category link ?>
								<p>Wechseln zu:</p>
								<p><a class="editlink" href="dashboard.php?action=edit&cat_id=<?= $category->getCat_id() ?>">Bearbeiten</a></p>
								<p><a href="dashboard.php?action=showCategory">Verwalten</a></p>
								<p><a href="index.php">Startseite</a></p>
								<?php else : // show back to showCategory ?>
								<p>Wechseln zu:</p>
								<p><a href="dashboard.php?action=showCategory&id=">Verwalten</a></p>
								<p><a href="dashboard.php">Was neues erstellen</a></p>
								<p><a href="index.php">Startseite</a></p>
								<?php endif ?>
							</div>
						</div>
					</article>
					<?php endif //  if( isset($catFilter) ): ?>

					<?php if( isset($blogFilter) ): // Show selected BLOG ?>
						<article id="delblog" class="fleft">
							<div class="felft">
								<h4 class="fullwidth"><?= $deleteCheckHeadline ?></h4>
								<br>
								<div class="underlined">
								<?php if( $blog->getBlog_id() ) : // Show only if a BLOG is selected ?>
								<p class="fullwidth"><b><?= $blog->getBlog_headline() ?></b></p>
								<p>von: <?= $blog->getUserFullname() ?></p>
								<p><?= $blog->getEuDateTime() ?></p>
								<p class="deletelink"><?= $deleteCheckMessage ?></p>
								<p><a class="editlink" href="dashboard.php?action=edit&blog_id=<?= $blog->getBlog_id() ?>">Bearbeiten</a></p>
								<p><a href="dashboard.php?action=showCategory">Verwalten</a></p>
								<p><a href="index.php">Startseite</a></p>
								<?php else : // show back to showCategory ?>
								<p>Zurück zur Auswahl-Seite:</p>
								<p><a href="dashboard.php?action=showCategory&id=">Verwalten</a></p>
								<p><a href="dashboard.php">Neuen Beitrag erstellen.</a></p>
								<p><a href="index.php">Startseite</a></p>
								<?php endif ?>
								</div>
							</div>
						</article>
						<?php endif //  if( isset($catFilter) ): ?>

				<?php endif // elseif($action == "delete") : ?>
		</section>

	</body>
</html>
