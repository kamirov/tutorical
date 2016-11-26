<?
//	  var_dump($this->session->all_userdata());

$find_request_buttons = 
'
	<span class="get-started-buttons buttons color-3-buttons find-tutor-buttons" title="Find tutors near you">
		Find a Tutor
	</span>
	<a href="'.base_url('request/new-request').'" data-reveal-id="request-modal" class="get-started-buttons buttons color-2-buttons" title="Make a free request and tutors will find you!">
		Request a Tutor
	</a>
';

if (!$logged_in)
{
	$signup_reveal = 'data-reveal-id="signup-tutor-modal"';
}
else
{
	$signup_reveal = '';
}
?>

<section id="main-front" class="cf">

	<div class="city-patterns"></div>

	<div class="containers">

		<div class="tutors-students-slides student-features" id="students-slide">
			<h2 class="main-messages">Find your perfect tutor</h2>
			<p class="welcome-messages">Tutorical is the best way to find and contact tutors for <b>free</b>!</p>
			<div class="under-welcome">
				<?= $find_request_buttons ?>
			</div>
		</div>

		<div class="tutors-students-slides tutor-features" id="tutors-slide">
			<h2 class="main-messages">Find more tutoring clients</h2>
			<p class="welcome-messages">Tutorical is the best way for tutors to expand their clientele for <b>free</b>!</p>
				<div class="under-welcome">
				<a href="<?= base_url('signup/tutor') ?>" <?= $signup_reveal ?> class="get-started-buttons buttons color-1-buttons signup-tutor-links">
					Become a Tutor
				</a>
				<span class="or-links">or <a href="<?= base_url('request/new-request') ?>" data-reveal-id="request-modal" class="">request a tutor</a></span>
			</div>
		</div>
	</div>

</section>

<section id="home-content" class="containers">

	<div id="features-links-cont">
		<span class="features-links active" id="student-features-link" data-show-type="student">Student Features</span><span class="features-links" id="tutor-features-link" data-show-type="tutor">Tutor Features</span>
	</div>

	<div class="features-content student-features" id="student-features-content">
		<div class="features-sections process-sections">
			<h2>Easy to find tutors</h2>

			<div class="features-section-body">
				<div class="process-items">
					<span class="process-text"><span class="process-numbers">1. </span>Search local/distant tutors</span>
					<img class="process-img" src="<?= base_url('assets/images/home/s-process-0.jpg') ?>">
				</div>
				<span class="arrows">&#8594;</span>
				<div class="process-items">
					<span class="process-text"><span class="process-numbers">2. </span>Request a tutor</span>
					<img class="process-img" src="<?= base_url('assets/images/home/s-process-1.jpg') ?>">
				</div>
				<br>	<!-- hidden on large views -->
				<span class="arrows">&#8594;</span>
				<div class="process-items">
					<span class="process-text"><span class="process-numbers">3. </span>See profiles &amp; reviews</span>
					<img class="process-img" src="<?= base_url('assets/images/home/s-process-2.jpg') ?>">
				</div>
				<span class="arrows">&#8594;</span>
				<div class="process-items">
					<span class="process-text"><span class="process-numbers">4. </span>Contact tutors for free</span>
					<img class="process-img" src="<?= base_url('assets/images/home/s-process-3.jpg') ?>">
				</div>
			</div>
		</div>
		<div class="features-sections half-slideshow-sections">
			<h2>Many ways to search</h2>

			<div class="features-section-body">
				<div class="features-text">
					<ul class="raquo-lists">
						<li>Search <b>local tutors</b> by subject and location</li>
						<li>Search <b>distance tutors</b> by subject</li>
						<li>See all <b>subjects taught</b> in a location</li>
						<li>Make a <b>tutor request</b> and let tutors find you</li>
					</ul>
				</div><div class="features-slideshow">
					<div class="slideshow-slide-conts">
						<div class="slideshow-slides">
							<img src="<?= base_url('assets/images/home/s-tutors.jpg') ?>">
						</div>
<!--
						<div class="slideshow-slides">
							<img src="<?= base_url('assets/images/home/s-distance-tutor.jpg') ?>">
						</div>
						<div class="slideshow-slides">
							<img src="<?= base_url('assets/images/home/s-subjects.jpg') ?>">
						</div>
-->
					</div>
					<div class="slideshow-button-conts">
						<div class="slideshow-buttons"></div>
						<div class="slideshow-buttons"></div>
						<div class="slideshow-buttons"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="features-sections centered">
			<h2>And it’s absolutely free!</h2>

			<div class="features-section-body">
				<p>No credit cards, no middleman costs. Use Tutorical to find your perfect tutor for free!</p>
				<?= $find_request_buttons ?>
			</div>
		</div>
	</div>

	<div class="features-content tutor-features" id="tutor-features-content">
			<div class="features-sections process-sections">
				<h2>Easy to use</h2>

				<div class="features-section-body">
					<div class="process-items">
						<span class="process-text"><span class="process-numbers">1. </span>Make a free tutor profile</span>
						<img class="process-img" src="<?= base_url('assets/images/home/t-process-0.jpg') ?>">
					</div>
					<span class="arrows">&#8594;</span>
					<div class="process-items">
						<span class="process-text"><span class="process-numbers">2. </span>Market your profile</span>
						<img class="process-img" src="<?= base_url('assets/images/home/t-process-1.jpg') ?>">
					</div>
					<br>	<!-- hidden on large views -->
					<span class="arrows">&#8594;</span>
					<div class="process-items">
						<span class="process-text"><span class="process-numbers">3. </span>Apply to tutor requests</span>
						<img class="process-img" src="<?= base_url('assets/images/home/t-process-2.jpg') ?>">
					</div>
					<span class="arrows">&#8594;</span>
					<div class="process-items">
						<span class="process-text"><span class="process-numbers">4. </span>Get reviewed by students</span>
						<img class="process-img" src="<?= base_url('assets/images/home/t-process-3.jpg') ?>">
					</div>
				</div>
			</div>
			<div class="features-sections half-slideshow-sections">
				<h2>Many ways to find students</h2>

				<div class="features-section-body">
					<div class="features-text">
						<ul class="raquo-lists">
							<li>Make a <b>beautiful profile</b> and let students find you</li>
							<li>Use our <b>marketing tools</b> to advertise yourself</li>
							<li>Search <b>local and distance requests</b></li>
							<li><b>Receive emails</b> about new requests</li>
						</ul>
					</div><div class="features-slideshow">
						<div class="slideshow-slide-conts">
							<div class="slideshow-slides">
								<img src="<?= base_url('assets/images/home/t-requests.jpg') ?>">
							</div>
	<!--
							<div class="slideshow-slides">
								<img src="<?= base_url('assets/images/home/s-distance-tutor.jpg') ?>">
							</div>
							<div class="slideshow-slides">
								<img src="<?= base_url('assets/images/home/s-subjects.jpg') ?>">
							</div>
	-->
						</div>
						<div class="slideshow-button-conts">
							<div class="slideshow-buttons"></div>
							<div class="slideshow-buttons"></div>
							<div class="slideshow-buttons"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="features-sections centered">
				<h2>Keep 100% of your earnings!</h2>

				<div class="features-section-body">
					<p>Unlike traditional tutoring companies, we don't take a cut of our tutors' profits. Set a price and keep 100% of what you earn!</p>
					<a href="<?= base_url('signup/tutor') ?>" <?= $signup_reveal ?> class="get-started-buttons buttons color-1-buttons signup-tutor-links" >
						Become a Tutor
					</a>
				</div>
			</div>
	</div>

</section>

<!--
<div class="button-conts">
	<a href="javascript:void(0);" id="for-be-a-tutor-button" class="buttons color-1-buttons signup-tutor-links" <?= $signup_reveal ?> >Become a Tutor</a>
</div>
-->

<script>

function startSlideshow(type)
{
	var $slideshow,
		$slides;

	$('.slideshow-slides').hide();

	if (type == 'tutor')
	{
		$slideshow = $('.features-slideshow', '#tutor-features-content');
	}
	else
	{
		$slideshow = $('.features-slideshow', '#student-features-content');
	}

	$slides = $slideshow.find('.slideshow-slides');

	$slides.first().animate(
	{
		opacity: 1
	});

	showSlide($slides, 1);
}

function showSlide($slides, slideNum)
{
	$slides.hide().eq(slideNum).fadeIn();
}

/*
var slidesAnimating = false;

function showSlide(selector)
{
	if (slidesAnimating)
		return;
	
	slidesAnimating = true;

	var $otherSlide = $(selector == '#students-slide' ? '#tutors-slide' : '#students-slide')
		$slide = $(selector),
		$welcome = $slide.find('.welcome-messages, .under-welcome')
		$benefits = $slide.find('.benefit-items'),
		animationSpeed = 500,
		easing = "easeInOutQuart";

	$otherSlide.find('.benefit-items').add($welcome).fadeOut(0, function()
	{
		$otherSlide.find('.benefit-items').css(
		{
			bottom: -225,
			display: 'inline-block'
		}).end().hide();
	});

	$slide.show();

	$welcome.fadeIn(animationSpeed);
	
	$benefits.eq(0).animate(
	{
		bottom: 0
	}, animationSpeed, easing);

	setTimeout(function() 
	{
		$benefits.eq(1).animate(
		{
			bottom: 0
		}, animationSpeed, easing);
	}, animationSpeed/4);

	setTimeout(function() 
	{
		$benefits.eq(2).animate(
		{
			bottom: 0
		}, animationSpeed, easing, function()
		{
			slidesAnimating = false;
		});
	}, animationSpeed/(2));
}
*/

function showType(type)
{
	var speed = 500,
		otherType;

	if (type == 'tutor')
		otherType = 'student';
	else
		otherType = 'tutor';

	$('#'+otherType+'-features-link').removeClass('active');
	$('#'+type+'-features-link').addClass('active');

	$('.'+otherType+'-features').hide();
	$('.'+type+'-features').fadeIn(speed);

	//startSlideshow(type);
}

$(function() 
{
	showType('student');

	$('[data-show-type]').click(function()
	{
		showType($(this).attr('data-show-type'));
	});

	$('.find-tutor-buttons').click(function()
	{
		$('#search-group').val('local-tutors').change();
		$('.search-button').click();
	});

/*	
	setTimeout(function() 
	{
		showSlide('#students-slide');
//		showSlide('#tutors-slide');
	}, 800);

	$('.see-benefits-links').click(function()
	{
		showSlide($(this).attr('data-target-slide'));
	});
*/
	<? if (isset($account_deleted)):
		$noty = '<b>Your account has been deleted.</b><br>Sorry it didn\'t work out. Take care!';
	?>
		noty(
		{
			text: <?= json_encode($noty) ?>,
			type: 'warning'
		});

	<? endif; ?>

	<? if (isset($need_login)):
		$noty = '<b>Sorry! You must '.anchor('login','log in', 'data-modal-state="temp" data-reveal-id="login-modal" data-redirect="'.$previous_page.'"').' to see that page.</b>';
	?>
		noty({
			text: '<?= $noty ?>',
			type: 'warning'
		});

		activateTempReveals();

	<? endif; ?>

	<? if (isset($already_registered)): 
		$noty = '<b>You\'ve already signed up!</b><br> To make another account, '.anchor('logout','log out').'.';
	?>
		noty({
			text: <?= json_encode($noty) ?>,
			timeout: 8000,
			type: 'warning'
		});
	<? endif; ?>

	<? if (isset($password_reset)): 
		$noty = '<b>Password changed</b>';
	?>
		noty({
			text: <?= json_encode($noty) ?>,
			timeout: 2000,
			type: 'success'
		});
	<? endif; ?>

	<? if ($this->session->userdata('user_id')): 
		$noty = '<b>You\'ve already signed up!</b><br> To make another account, '.anchor('logout','log out').'.';
	?>

	$('.signup-tutor-links').click(function(e) 
	{
		noty({
			text: <?= json_encode($noty) ?>,
			timeout: 5000,
			type: 'warning'
		});
		return false;
	});
	<? endif; ?>

});

</script>