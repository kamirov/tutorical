<? get_header(); ?>

	<div id="content" role="main">

		<?= get_pagination(TRUE) ?>
		<header class="archive-header boxes">
			<h1 class="page-title"><?= 'Search Results for: '.get_search_query(); ?></h1>
		</header><!-- .archive-header -->

		<? if ( have_posts() ) : ?>

			<? /* Start the Loop */ ?>
			<? while ( have_posts() ) : the_post(); ?>
				<? get_template_part( 'content', get_post_format() ); ?>
			<? endwhile; ?>

		<?= get_pagination() ?>

		<? else: ?>

			<article id="post-0" class="posts no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title"><?= "Nothing found!"; ?></h1>
				</header>

				<div class="entry-content">
					<p><?='Sorry, but nothing matched your search criteria. Please try again with some different keywords.'; ?></p>

				</div><!-- .entry-content -->
			</article><!-- #post-0 -->

		<? endif; ?>

	</div><!-- #content -->

<? get_sidebar(); ?>
<? get_footer(); ?>