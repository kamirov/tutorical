<? get_header(); ?>

	<div id="content" role="main">

		<?= get_pagination(TRUE) ?>

		<? if ( have_posts() ) : ?>

			<?
				the_post();
			?>

			<header class="archive-header boxes">
				<h1 class="archive-title"><?= 'Author Archives: '.get_the_author(); ?></h1>
			</header><!-- .archive-header -->

			<?
				rewind_posts();
			
				echo get_author_box(FALSE);
			?>

			<? /* Start the Loop */ ?>
			<? while ( have_posts() ) : the_post(); ?>
				<? get_template_part( 'content', get_post_format() ); ?>
			<? endwhile; ?>
		<? endif; ?>

		<?= get_pagination(FALSE, TRUE) ?>

	</div><!-- #content -->

<? get_sidebar(); ?>
<? get_footer(); ?>