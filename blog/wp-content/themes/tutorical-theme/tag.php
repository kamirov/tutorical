<? get_header(); ?>

	<div id="content" role="main">

		<?= get_pagination(TRUE) ?>

		<? if ( have_posts() ) : ?>
			<header class="archive-header boxes">
				<h1 class="archive-title">
					<? 
						printf('Tag Archives: %s', '<span>'.single_tag_title( '', false ).'</span>'); 
					?>
				</h1>
			</header><!-- .archive-header -->


			<?
			/* Start the Loop */
			while ( have_posts() ) : the_post();

				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
				get_template_part( 'content', get_post_format() );

			endwhile;
		endif; ?>

		<?= get_pagination(FALSE) ?>

	</div><!-- #content -->

<? get_sidebar(); ?>
<? get_footer(); ?>