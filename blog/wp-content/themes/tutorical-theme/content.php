<?
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	<article id="post-<? the_ID(); ?>" class="posts">
		<header class="entry-header">
			<? if ( is_single() ) : ?>
			<h1 class="entry-title single-title"><? the_title(); ?></h1>
			<? else : ?>
			<h1 class="entry-title">
				<a href="<? the_permalink(); ?>" title="<? echo esc_attr( sprintf( __( 'Permalink to %s'), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><? the_title(); ?></a>
			</h1>
			<? endif; // is_single() ?>
			
			<div class="entry-meta">
				<span class="entry-date">Posted <a href="<? the_permalink(); ?>" title="Posted on <? the_time('F j, Y'); ?> at <? the_time('g:i a'); ?>"><? the_time('F j, Y'); ?></a></span>
				<span class="entry-author">by <? the_author_posts_link(); ?></span>
				<span class="entry-category">in <? the_category(' &bull; '); ?></span>
				
				<?
					$tag_list = get_the_tag_list('',' &bull; ', '');

					if ($tag_list):
				?>
				
				|
				<span class="entry-tags">Tagged: <?= $tag_list ?></span>
				
				<?
					endif;
				?>				
			</div>

		</header><!-- .entry-header -->

		<? if (is_single()): ?>
		<div class="entry-content">
			<? 
				the_content(); 
				echo get_author_box(); 
			?>
		</div><!-- .entry-content -->

		<? else : ?>
		<div class="entry-summary">
			<? the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<footer class="entry-footer-buttons">
			<? if (current_user_can('edit_post')): ?>
				<a class="edit-link buttons" title="Edit this post" href="<?= get_edit_post_link(); ?>">Edit</a>
			<? endif; ?>
			<a class="read-more-link buttons color-3-buttons" title="Read the rest of this post" href="<? the_permalink(); ?>">Read on &raquo;</a>
		</footer>
		<? endif; ?>
	</article><!-- #post -->
