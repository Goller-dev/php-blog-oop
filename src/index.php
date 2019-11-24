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

				require_once("controller/index.controller.php");


/**********************************************************************************/
?>

<!DOCTYPE html>

	<html>

		<head>

			<link rel="stylesheet" href="css/debug.css">
			<link rel="stylesheet" href="css/main.css">

			<meta charset="utf-8">
			<title><?= $pageTitle ?></title>
		</head>

		<body>
			<!-- ----------------------------- HEADER ------------------------------------- -->
			<?php require_once("include/pageElements/header.php") ?>

			<!-- ----------------------------- MAIN HEADLINE ------------------------------------- -->
			<h1 class="mainheadline">Spartan CMS</h1>
			<!-- ----------------------------- MAIN HEADLINE END ------------------------------------- -->

			<!-- ----------------------------- NAVIGATION ------------------------------------- -->


			<?php if( $categoriesArray ): ?>
				<nav class="nav">
					<ul>
						<li><a href='<?= $_SERVER['SCRIPT_NAME'] ?>#pageHead'>Alle anzeigen</a></li>
						<?php foreach( $categoriesArray AS $category ): ?>
							<li><a href='?action=showCategory&id=<?= $category->getCat_id() ?>#pageHead'><?= $category->getCat_name() ?></a></li>
						<?php endforeach ?>
					</ul>
				</nav>
			<?php else: ?>
				<nav class="nav">
					<ul>
						<p class="info">Noch keine Kategorien vorhanden.</p>
					</ul>
				</nav>
			<?php endif ?>

			<!-- --------------------------- NAVIGATION END ---------------------------------- -->



			<section>

			<!-- ------------------------------- BLOG ENTRIES --------------------------------- -->

			<main>
				<?php if( $blogEntriesArray ): ?>
					<?php if( $blogEntriesArray[0]->getCategoryId() == $showCategory->getCat_id() OR $showBlog->getBlog_id() ):?>
						<!-- CATEGORY OR BLOG IS SELECTED -->
						<h2><?= $blogEntriesArray[0]->getCategory()->getCat_title() ?></h2>
						<div class="blogcontent"><p><?= nl2br(str_replace("\r\n\r\n","</p><p>",$blogEntriesArray[0]->getCategory()->getCat_description())) ?></p></div>
					<?php else :?>
						<h2>Alle Beiträge werden angezeigt</h2>
						<div class="blogcontent"><p>Auf der Startseite werden alle Beiträge angezeigt. "Schön, dass Sie hier sind."</p></div>
					<?php endif ?>

					<?php foreach( $blogEntriesArray AS $blog ): ?>

						<!-- THE BLOG ARTICLE -->
						<article>

							<!-- BLOG ARTICLE HEADLINE H3 -->
								<h3><?= $blog->getBlog_headline() ?></h3>
								<!-- CATEGORY LINK -->
								<div class="blogentrynav">
								<p>

									<a href='?action=showCategory&id=<?= $blog->getCategory()->getCat_id() ?>#pageHead'><?= $blog->getCategory()->getCat_name() ?></a>
									<?php if( isset($_SESSION['userObject']) ): ?>
										<!-- EDIT & DELETE LINKS -->
											<!-- EDIT LINK -->
											<a href='dashboard.php?action=edit&blog_id=<?= $blog->getBlog_id() ?>#blogform'>Bearbeiten</a>
											<!-- DELETE LINK -->
											<a href='dashboard.php?action=delete&blog_id=<?= $blog->getBlog_id() ?>#delblog'>Löschen</a>
									<?php endif ?>
								</p>
								</div>


							<p class='authorstats'><?= $blog->getEuDateTime() ?>, <?= $blog->getUserFullname() ?> (<?= $blog->getUser()->getUsr_city() ?>):</p>

							<!-- BLOG IMAGE -->
							<div class="blogcontent">
								<?php if($blog->getBlog_image()): ?>
									<!-- thumbnail image wrapped in a link -->
									<a href="#<?= $blog->getBlog_image() ?>">
									  <img src="<?= $blog->getBlog_image() ?>" class="thumbnail <?= $blog->getBlog_imageAlignment() ?>">
									</a>

									<!-- lightbox container hidden with CSS -->
									<a href="#_" class="lightbox" id="<?= $blog->getBlog_image() ?>">
									  <img src="<?= $blog->getBlog_image() ?>">
									</a>
									<!-- BLOG IMAGE END -->
								<?php endif ?>

							<!-- BLOG CONTENT -->
							<?php if( !$showBlog->getBlog_id() ): ?>
									<!-- No Blogfilter: limit Content -->
								<p><?= mb_substr( nl2br(str_replace("\r\n\r\n","</p><p>",$blog->getBlog_content()) ) ,0 ,300 ) ?>
									... <a class="readPost" href="?action=showBlog&id=<?= $blog->getBlog_id() ?>">"<?= $blog->getBlog_headline() ?>" lesen</a></p>

							<?php else: ?>
								<!-- Blogfilter true: Show full Content -->
								<p><?= nl2br(str_replace("\r\n\r\n","</p><p>",$blog->getBlog_content()) ) ?></p>
							<?php endif ?>


							</div>
						</article>

					<?php endforeach ?>

				<?php else: ?>
					<article>
						<p class="info">Noch keine Blogeinträge vorhanden.</p>
					</article>
				<?php endif ?>

			</main>
			<!-- ---------------------------- BLOG ENTRIES END ------------------------------- -->

		</section>

		</body>

	</html>
