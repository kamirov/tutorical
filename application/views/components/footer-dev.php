</div>  <!-- /#main-content -->

<div class="push"></div>

</div>  <!-- /.wrapper -->

	<footer id="gen-footer">

 		 <div class="containers cf" id="footer-content">

			<div class="footer-cols">
				<span class="footer-titles">Tutorical</span>
				<ul>
					<li><?= anchor('', 'Home') ?></li>
					<li><?= anchor ('about', 'About') ?></li>
					<li><?= anchor('sitemap', 'Sitemap') ?></li>
					<li><?= anchor('credits', 'Credits') ?></li>
				</ul>
			</div><div class="footer-cols">
				<span class="footer-titles">Connect</span>
				<ul>
					<li><?= anchor ('blog', 'Blog', 'title="Visit the Tutorical Blog"') ?></li>
					<li><a href="http://tutorical.uservoice.com" title="Visit our Uservoice Feedback Forum to create and vote on suggestions for Tutorical">Feedback</li>
					<li><?= anchor('contact', 'Contact', 'data-reveal-id="contact-modal"'); ?></li>
					<li id="footer-social">
						<span id="footer-follow">Follow us:</span>
						<span id="footer-follow-items">
							<a rel="nofollow" target="_blank" href="https://www.facebook.com/tutorical" class="follow-socials follow-facebook" title="Follow us on Facebook"></a>
							<a rel="nofollow" target="_blank" href="https://plus.google.com/113751227385229503772" rel="publisher" class="follow-socials follow-google" title="Follow us on Google+"></a>
							<a rel="nofollow" target="_blank" href="https://twitter.com/tutorical" class="follow-socials follow-twitter" title="Follow us on Twitter"></a>
						</span>
					</li>
				</ul>
			</div>

			<div id="footer-legal">
				<a id="footer-image" href="<?= base_url() ?>" title="Go to the Tutorical home page">
					<img src="<?= base_url('assets/images/title.png') ?>">
				</a>
				<?= anchor('privacy', 'Privacy Policy') ?> | &copy; <a target="_blank" href="http://aeternastudio.com">Aeterna</a> <?= date('Y') ?>
			</div>

		</div>

	</footer>

<? if (INTERNET_CONNECTION): ?>
<div id="uvTab">
	<a id="uvTabLabel" target="_blank" rel="nofollow" href="http://tutorical.uservoice.com">
		<img src="http://widget.uservoice.com/dcache/widget/feedback-tab.png?t=Feedback&amp;c=ffffff&amp;r=90" alt="Feedback">
	</a>
</div>
<? endif; ?>

<script src="<?= base_url('assets/js/tutorical-'.ENV.'.js') ?>"></script>

<script>

$(function() 
{
	<? if ($reveal): ?>
		$('<?= "#$reveal-modal" ?>').foundation('reveal', 'open');
	<? endif; ?>

	resizeSite();
});

<? if (INTERNET_CONNECTION): ?>
/* Analytics
   ========= */

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-26323495-3', 'tutorical.com');
  ga('send', 'pageview');
<? endif; ?>

</script>

<? if (isset($end_of_page_divs)) echo $end_of_page_divs; ?>

</body>

</html>