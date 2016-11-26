<? get_header(); ?>

	<div id="content" role="main">

		<?= get_pagination(TRUE, TRUE) ?>

		<? if ( have_posts() ) : ?>

			<? /* Start the Loop */ ?>
			<? while ( have_posts() ) : the_post(); ?>
				<? get_template_part( 'content', get_post_format() ); ?>
			<? endwhile; ?>

		<? endif; // end have_posts() check ?>

		<?= get_pagination(FALSE, TRUE) ?>

	</div><!-- #content -->

<? get_sidebar(); ?>
<? get_footer(); ?>