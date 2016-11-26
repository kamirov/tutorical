<!-- No longer used -->

<div class="containers cf pages">
	<section id="text-regular" class="cf">
<!--
		<div id="text-nav" class="cf">
			<?= anchor('about', 'About', ''); ?>
			<?= anchor('credits', 'Credits'); ?>
			<?= anchor('sitemap', 'Sitemap'); ?>
			<?= anchor('contact', 'Contact', 'data-reveal-id="contact-modal"'); ?>
		</div>
-->
		<h1 id="page-heading">About Tutorical</h1>

		<div id="page-content" class="text-content">
			<div style="width: 100%;">
				<p>Tutorical is a place where students can find tutors near them, and tutors can advertise their services.</p>

				<h2>Tidbits</h2>
				<div id="about-item-cont">
					<div class="about-items left">
						<img class="about-item-images" src="<?= base_url('assets/images/about/profile.jpg') ?>" alt="Detailed Tutor Profiles">
						<div class="about-content">
							<span class="about-item-titles">Detailed Tutor Profiles</span>
							<span class="about-item-text">Tutors can specify when they can tutor, where they can meet, what they charge (hourly, range, or free), and many other details about themselves.</span>
						</div>
					</div>

					<div class="about-items">
						<img class="about-item-images" src="<?= base_url('assets/images/about/reviews.jpg') ?>" alt="Multi-Part Reviews">
						<div class="about-content">
							<span class="about-item-titles">Multi-Part Reviews</span>
							<span class="about-item-text">Students can read/write descriptions of their tutoring sessions and review tutors' expertise, helpfulness, response, and clarity.</span>
						</div>
					</div>

					<div class="about-items left">
						<img class="about-item-images" src="<?= base_url('assets/images/about/money.jpg') ?>" alt="Free Service">
						<div class="about-content">
							<span class="about-item-titles">Free Service</span>
							<span class="about-item-text">Unlike traditional tutoring companies, we don't take a cut of tutors' profits and we don't charge students anything for using our service.</span>
						</div>
					</div>

					<div class="about-items left">
						<img class="about-item-images" src="<?= base_url('assets/images/about/students.jpg') ?>" alt="Student/Tutor Management">
						<div class="about-content">
							<span class="about-item-titles">Student/Tutor Management</span>
							<span class="about-item-text">Tutors and students can accept, reject, review, and manage their contacts quickly and easily from their account.</span>
						</div>
					</div>

					<div class="about-items left">
						<img class="about-item-images" src="<?= base_url('assets/images/about/request.jpg') ?>" alt="For Students: Tutor Requests">
						<div class="about-content">
							<span class="about-item-titles">For Students: Tutor Requests</span>
							<span class="about-item-text">Type a subject, location, and max price and let tutors come to you! Read their offers, check out their profiles, and find your perfect match.</span>
						</div>
					</div>

					<div class="about-items">
						<img class="about-item-images" src="<?= base_url('assets/images/about/marketing.jpg') ?>" alt="For Tutors: Marketing Tools">
						<div class="about-content">
							<span class="about-item-titles">For Tutors: Marketing Tools</span>
							<span class="about-item-text">From the Marketing page on your account, advertise your profile through social media and use snippets post to classifieds sites like Craigslist.</span>
						</div>
					</div>
				</div>
				
				<h2>When, Where, and Who</h2>
				<p>Tutorical was launched in January 2013 from the Great Canadian North. It's managed by Andrei Khramtsov of <a href="http://aeternastudio">Aeterna</a>.</p>

				<h2>Get in Touch</h2>
				<p>For general comments and questions, please visit our <?= anchor('contact', 'contact page', 'data-reveal-id="contact-modal"') ?>.</p>
				<p>
					For press inquiries, please contact:<br>
					<span id="press-contact-info">
						<span class="press-contact-info"><b>Andrei Khramtsov</b></span><br>
						<span class="press-contact-info">Technical Director</span><br>
						<span class="press-contact-info"><?= mailto('andrei@aeternastudio.com') ?></span>
					</span>
				</p>

			</div>
		</div>

	</section>
</div>

