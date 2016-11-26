
<div id="sidebar" class="widget-area" role="complementary">

	<div id="blog-tutorical-about" class="sidebar-secs">
		<span class="sidebar-titles"><span class="emphasized">Tu</span>torical</span>
		<div class="boxes">
			<p><a href="<?= base_url() ?>" title="Visit Tutorical">Tutorical</a> is the best way for students
			and tutors to find each other. Post a tutor request, review detailed profiles, and find the perfect tutor near you.</p>

			<p><b>And best of all, it’s absolutely free.</b></p>
		</div>
	</div>

	<div id="blog-follow-cont" class="sidebar-secs">
		<a rel="nofollow" target="_blank" href="https://www.facebook.com/tutorical" title="Tutorical on Facebook" class="blog-follow-buttons" id="facebook-follow-button"></a>
		<a rel="nofollow" target="_blank" href="https://plus.google.com/113751227385229503772" title="Tutorical on Google+" class="blog-follow-buttons" id="google-follow-button"></a>
		<a rel="nofollow" target="_blank" href="https://twitter.com/tutorical" title="Tutorical on Twitter" class="blog-follow-buttons" id="twitter-follow-button"></a>
		<a target="_blank" href="<?= home_url('/feed') ?>" title="Tutorical RSS Feed" class="blog-follow-buttons" id="rss-follow-button"></a>

		<span id="blog-follow-text"><span class="arrows">&larr;</span> Follow Us</span>
	</div>

	<div id="blog-tutorical-category-list" class="sidebar-secs">
		<span class="sidebar-titles">Categories</span>
		<ul>
		<?
			$args = array(
				'title_li' => ''
			);

			wp_list_categories($args);
		?>
		</ul>
	</div>

	<div id="blog-tutorical-tag-list" class="sidebar-secs">
		<span class="sidebar-titles">Tags</span>
		<?
			$args = array(
				'smallest' => 13,
				'largest' => 13,
				'format' => 'list',
				'unit' => 'px',
				'topic_count_text_callback' => 'wp_cloud_title_text',
				'echo' => TRUE
			);

			wp_tag_cloud($args);
		?>
	</div>

</div><!-- #secondary -->

