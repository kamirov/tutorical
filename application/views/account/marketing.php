<section id="account" class="cf containers pages" data-user-id="<?= '' ?>">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

	<div id="marketing-content" class="account-subpage-conts">				
		<section id="account-text-content">
			<p>By marketing, you'll direct potential students to your profile, which leads to more tutoring opportunities!</p>

			<h3 class="marketing-step-titles first">1. Share a link to your profile via Facebook, Twitter, Google+, and LinkedIn</h3>
			<div id="marketing-share">
				<div id="marketing-share-message-cont">
					<span id="marketing-share-message">"Just made a tutor profile at Tutorical | <?= $profile_link ?>"</span>
				</div>

				<div class="marketing-share-items">
					<div class="marketing-share-text"><span>Google+</span></div>
					<!-- G+ -->

					<div class="share-buttons g-share-buttons">
						<!-- Place this tag where you want the +1 button to render. -->
						<div class="g-plusone" data-size="medium" data-annotation="none" data-href="<?= $profile_link ?>"></div>

						<!-- Place this tag after the last +1 button tag. -->
						<script type="text/javascript">
						  (function() {
						    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						    po.src = 'https://apis.google.com/js/plusone.js';
						    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
						  })();
						</script>

					</div>
					<!-- /G+ -->
				</div>

				<div class="marketing-share-items">
					<div class="marketing-share-text"><span>Facebook</span></div>
					<!-- Facebook -->
					<div class="share-buttons facebook-share-buttons">
						<div id="fb-root"></div>
						<script>(function(d, s, id) {
						  var js, fjs = d.getElementsByTagName(s)[0];
						  if (d.getElementById(id)) return;
						  js = d.createElement(s); js.id = id;
						  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
						  fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));</script>
						<div class="fb-like" data-href="<?= $profile_link ?>" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-font="arial"></div>
					</div>

					<!-- /Facebook -->
				</div>

				<div class="marketing-share-items">
					<div class="marketing-share-text">Twitter</div>
					<!-- Tweet -->
					<a rel="nofollow" href="https://twitter.com/share" class="twitter-share-button share-buttons" data-url="<?= $profile_link ?>" data-text="Just made a tutor profile at Tutorical |" data-count="none">Tweet</a>

					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					<!-- /Tweet -->
				</div>
				<div class="marketing-share-items">
					<div class="marketing-share-text"><span>LinkedIn</span></div>
					<!-- LinkedIn -->
					<div class="share-buttons">
						<script src="//platform.linkedin.com/in.js" type="text/javascript">
						 lang: en_US
						</script>
						<script type="IN/Share" data-url="<?= $profile_link ?>"></script>
					</div>
					<!-- /LinkedIn -->
				</div>
			</div>
			
			<h3 class="marketing-step-titles">2. Ask your friends and family to +1, like, and tweet about your profile</h3>

			<div id="marketing-friends">

				<p><b>Your profile link</b>: <input class="free-inputs select-on-click-inputs" type="text" value="tutorical.com/tutors/<?= $username ?>">
				<p>Ask your friends and family to share your profile through the share buttons on the bottom-right of <a href="<?= $profile_link ?>">your profile</a></p>

			</div>

			<h3 class="marketing-step-titles">3. Post about yourself on our Facebook and Google+ pages</h3>

			<div id="marketing-post">

				<div class="marketing-post-items">				
					<div class="marketing-post-text g-text"><span>Google+</span></div>
					<!-- G+ -->
					<div class="g-plus-post marketing-posts">
						<div rel="nofollow" class="g-plus" data-width="263" data-href="https://plus.google.com/113751227385229503772" data-rel="publisher"></div>

						<script type="text/javascript">
						  (function() {
						    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
						    po.src = 'https://apis.google.com/js/plusone.js';
						    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
						  })();
						</script>
					</div>
					<!-- /G+ -->
				</div>


				<div class="marketing-post-items">
					<div class="marketing-post-text fb-text"><span>Facebook</span></div>
					<!-- FB -->
					<div class="fb-post marketing-posts">
						<div class="fb-like-box" data-href="http://facebook.com/tutorical" data-width="261" data-height="62" data-show-faces="false" data-stream="false" data-show-border="false" data-header="false"></div>
					</div>
					<!-- /FB -->
				</div>
			</div>

			<h3 class="marketing-step-titles">4. Post your profile to Craigslist and Kijiji</h3>

			<div id="marketing-ad">
				<p>Click the button below for steps to post a <b>structured ad</b> with your profile to Craigslist and Kijiji.</p>
				<div id="marketing-ad-items-cont">
					<div class="marketing-ad-items">
						<span class="toggle-buttons post-instructions-buttons buttons color-3-buttons" data-toggle-container="#craigslist-instructions" data-toggle-text="Close Instructions">Post to Craigslist</span>
						<a data-reveal-id="craigslist-preview" data-animation="fade" class="same-page-links marketing-show-preview-links">Preview</a>
						<br>
						<div id="craigslist-instructions" class="marketing-ad-posting-instructions boxes" data-additional-height="55">
							<span class="marketing-ad-posting-instruction-titles">Craigslist Posting Instructions</span>
							<ol>
								<li><a href="https://accounts.craigslist.org/login" target="_blank" rel="nofollow" title="Opens in new tab/window">Log in to Craigslist</a>.</li>
								<li><a href="https://accounts.craigslist.org/login?show_tab=new_posting" target="_blank" rel="nofollow" title="Opens in new tab/window">Create a new posting</a>.</li>
								<li>Choose your location, 'service offered', and 'lessons &amp; tutoring'.</li>
								<li>Copy the values below and paste them into the posting. (<span class="same-page-links no-pointer" title="Leave 'specific location' blank. It's already listed in the 'posting title'.">?</span>)</li>

								<div class="marketing-ad-fields">
									<div class="form-elements">
										<label for="craigslist-title">Posting Title</label>
										<input type="text" id="craigslist-title" class="select-on-click-inputs" value="<?= $classifieds['post_title'] ?>">
									</div>
									<div class="form-elements">
										<label for="craigslist-description">Posting Description</label>
										<textarea class="select-on-click-inputs" id="craigslist-description"><?= $classifieds['craigslist'] ?></textarea>
									</div>
								</div>
								<li>Optionally click 'show on maps' to add a map. (<span class="same-page-links no-pointer" title="With a map, your ad will look a bit different than in the preview.">?</span>)</li>
								<li><b>Press 'continue' and you're done!</b> (<span class="same-page-links no-pointer" title="You can optionally add images on the next page, but your ad will then look a bit different than in the preview..">?</span>)</li>
							</ol>
						</div>
						<br>
					</div>
					<div class="marketing-ad-items">
						<span class="toggle-buttons post-instructions-buttons buttons color-2-buttons" data-toggle-container="#kijiji-instructions" data-toggle-text="Close Instructions">Post to Kijiji (Canada only)</span>
						<a data-reveal-id="kijiji-preview" data-animation="fade" class="same-page-links marketing-show-preview-links">Preview</a>
						<br>
						<div id="kijiji-instructions" class="marketing-ad-posting-instructions boxes" data-additional-height="55">
							<span class="marketing-ad-posting-instruction-titles">Kijiji Posting Instructions</span>
							<ol>
								<li>
									If you haven't been to the site before, <a href="http://kijiji.ca" target="_blank" rel="nofollow" title="Opens in new tab/window">Visit Kijiji</a> to select your location.
								</li>
								<li>
									Click on the link below that applies to you: (<span class="same-page-links no-pointer" title="You can also click on 'Post Ad FREE' on Kijiji, and then select your category. However, the links below auto-enter the Ad Title, Email, and Address fields for you.">?</span>)
									<ul>
										<li><a href="http://toronto.kijiji.ca/c-PostAd?CatId=86&Email=<?= $this->session->userdata('email') ?>&SelectedLeafCat=SelectedLeafCat&Title=<?= $classifieds['post_title'] ?>" target="_blank" rel="nofollow" title="Opens in new tab/window">Music Lessons</a></li>
										<li><a href="http://toronto.kijiji.ca/c-PostAd?CatId=169&Email=<?= $this->session->userdata('email') ?>&SelectedLeafCat=SelectedLeafCat&Title=<?= $classifieds['post_title'] ?>" target="_blank" rel="nofollow" title="Opens in new tab/window">Tutoring</a></li>
									</ul>
								</li>
								<li>Copy the <b>Description</b> below and paste it into your ad. If you're not worried about privacy, then copy &amp; paste the <b>Address</b> below as well.</li>
								<div class="marketing-ad-fields">
									<div class="form-elements">
										<label for="kijiji-description">Description</label>
										<textarea class="select-on-click-inputs" id="kijiji-description"><?= $classifieds['kijiji'] ?></textarea>
									</div>
									<div class="form-elements">
										<label for="kijiji-location">Address</label>
										<input type="text" id="kijiji-location" class="select-on-click-inputs" value="<?= $classifieds['post_location'] ?>">
									</div>
								</div>
								<li>Upload up to 8 images. To upload your profile's photo, follow these steps: (<span class="same-page-links no-pointer" title="Unlike Craigslist, Kijiji requires you to manually upload any images. Just click 'Select Images', find your images, select them, and press 'Open'.">?</span>)
									<ol>
										<li>Right-click and select 'Save link as...' on this link <?= anchor('assets/uploads/images/'.$this->session->userdata('user_id').'/avatar.jpg', 'Your Photo', 'title="Right-click and select \'Save link as...\'" target="_blank"'); ?>.</li>
										<li>Select a location to save them.</li>
										<li>Click 'Select Images', find where you saved your photo, select it, and press 'Open'.</li>
									</ol>
								<li>Pick a location and enter in your email. (<span class="same-page-links no-pointer" title="Kijiji also allows for optional paid services like website links and ad promotion (these are up to you).">?</span>)</li>
								<li><b>Press 'Post Your Ad' and you're done!</b></li>
							</ol>
						</div>
						<br>
					</div>
				</div>
			</div>
		</section>
	</div>
		
</section>

<section id="kijiji-preview" class="cf reveal-modal" data-reveal>
	<section class="popup cf">
		<header>				
			<h2>Preview your Kijiji ad</h2>
		    <a class="close-reveal-modal">&#215;</a>
		</header>
		<div class="popup-body cf">
			<div class="pre-ad-text centered">
				<i><b>Important!</b></i> This preview doesn't show your image! Kijiji requires that you upload it manually.
			</div>
			<?= $classifieds['kijiji'] ?>			
			<div class="centered close-preview-links"><span class="same-page-links close-reveal-modal">&#215; Close preview</span></div>
		</div>
	</section>
</section>

<section id="craigslist-preview" class="cf reveal-modal" data-reveal>
	<section class="popup cf">
		<header>				
			<h2>Preview your Craigslist ad</h2>
		    <a class="close-reveal-modal">&#215;</a>
		</header>
		<div class="popup-body cf">
			<div class="pre-ad-text centered">
				<i><b>Important!</b></i> Craigslist no longer allows links, tables, or custom designs for ads. If you'd like to add an image or a map to your ad, Craigslist requires that you do it manually from their website.
			</div>
			<?= $classifieds['craigslist'] ?>			
			<div class="centered close-preview-links"><span class="same-page-links close-reveal-modal">&#215; Close preview</span></div>
		</div>
	</section>
</section>