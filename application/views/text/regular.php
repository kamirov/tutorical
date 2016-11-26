<div class="containers cf pages">
	<section id="text-regular" class="cf" data-page-id="<?= $text_page['id'] ?>">
		<h1 id="page-heading"><?= $text_page['title'] ?></h1>
		<span id="breadcrumbs"><?= $breadcrumbs ?></span>

		<div id="page-content" class="text-content">
			<?= $text_page['content'] ?>
		</div>

	<? if ($text_page['comments_enabled']): ?>

		<section id="comments-sec">
			<div id="disqus_thread"></div>
			<script>
			    var disqus_shortname = 'tutorical';
			    (function() {
			        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
			        dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
			        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			    })();
			</script>
			<noscript>Please enable JavaScript to view the <a rel="nofollow" href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
			<a rel="nofollow" href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
		</section>
		
	<? endif; ?>

	</section>
</div>

