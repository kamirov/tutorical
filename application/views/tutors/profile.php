<?
//var_dump($tutor);
$contact_tutor_form = array(
	'id' => 'contact-tutor-form',
	'class' => 'ased'
);

$contact_name = ($this->session->userdata('contact_name') ?: $this->session->userdata('display_name'));

$name = array(
	'name'	=> 'contact-user-name',
	'class'	=> 'name',
	'id' => 'contact-user-name',
	'value' => set_value('contact-user-name'),
	'maxlength' => 80,
	'tabindex' => 3000,
	'value' => $contact_name
);


$contact_email = ($this->session->userdata('contact_email') ?: $this->session->userdata('email'));
$email = array(
	'name'	=> 'contact-tutor-email',
	'class'	=> 'email',
	'type' => 'email',
	'id' => 'contact-tutor-email',
	'value' => set_value('contact-tutor-email'),
	'maxlength' => 80,
	'tabindex' => 3000,
	'value' => $contact_email
);

//$contact_phone = $this->session->userdata('contact_phone');
/*
$phone = array(
	'name'	=> 'contact-tutor-phone',
	'class'	=> 'phone',
	'id' => 'contact-tutor-phone',
	'value' => set_value('contact-tutor-phone'),
	'maxlength' => 80,
	'tabindex' => 3000,
	'value' => $contact_phone
);
*/
$message = array(
	'name'	=> 'contact-tutor-message',
	'id' => 'contact-tutor-message',
	'class'	=> 'contact-tutor-message',
	'tabindex' => 3100
);

$contact_button = array(
	'value' => 'Send Message', 
	'class' => 'buttons color-3-buttons',
	'id' => 'submit-user-contact-button', 
	'name' => 'submit',
	'tabindex' => 3200
);

$report = array(
	'name'	=> 'message',
	'id' => 'report-reason',
	'placeholder' => "Tell us what's wrong here..."
);

$report_button = array(
	'value' => 'Report', 
	'class' => 'buttons color-3-buttons',
);

if ($user_id == $tutor['id'])
{
	$favourite_text = "A student can favourite you by clicking this link. Favourited tutors show up on the student's Tutors page.";
	$invite_text = "A student can invite you to one of their requests by clicking this link.";
}
else
{
	$favourite_text = "Favourited tutors show up on your Tutors page. Favourite a tutor to make them easy to find later.";
	$invite_text = "Invite a tutor to one of your requests to indicate that you'd like them to apply.";
}

?>

<? if(isset($previous_page)): ?>
<div id="pre-profile" class="containers">
	<?= anchor($previous_page, "&laquo; <span>back to search results</span>", 'class="arrow-link back-to-search-results-links"') ?>
</div>
<? endif; ?>

<div id="dropdown-share" class="dropdown dropdown-tip dropdown-anchor-right dropdown" data-additional-height="30">
	<div class="ajax-overlays">
		<div class="ajax-overlays-bg"></div>
		<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
	</div>
	<div class="boxes dropdown-panel">
		  <div id="search-bar-tutor-share" class="addthis_toolbox addthis_default_style addthis_16x16_style">
	        <a class="addthis_button_google_plusone" g:plusone:count="false"></a>
	        <a class="addthis_button_facebook"></a>
	        <a class="addthis_button_twitter"></a>
	      </div>

	      <script type="text/javascript">
	  
	      var addthis_share = addthis_share || {};
	      addthis_share = 
	      {
	        passthrough : 
	        {
	              twitter: 
	              {
	                  <? if ($main_subject = $tutor['main_subject']['name']): ?>
	                      text: "Found a great <?= $main_subject ?> tutor in <?= $tutor['city'] ?>, <?= $tutor['country'] ?> - " + <?= json_encode($tutor['display_name']) ?> + " | "
	                  <? else: ?>
	                      text: "Found a great tutor in <?= $tutor['city'] ?>, <?= $tutor['country'] ?> - " + <?= json_encode($tutor['display_name']) ?> + " | "
	                  <? endif; ?>
	              }
	        },
	        url_transforms: 
	        {
	          shorten: 
	          {
	             twitter: 'bitly'
	          }
	        }, 
	        shorteners : 
	        {
	          bitly : {} 
	        }
	      };
	      
	      </script>
	      <script src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5193cf8e7853dd52"></script>
	</div>
</div>

<div id="dropdown-report" data-additional-height="30" class="dropdown dropdown-tip dropdown-anchor-right dropdown">
	<div class="ajax-overlays">
		<div class="ajax-overlays-bg"></div>
		<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
	</div>
	<div class="boxes dropdown-panel">
		<div class="form-elements">
			<form>
				<input type="hidden" name="id" id="report-id">
				<input type="hidden" name="type" id="report-type">

				<?= form_label('Reason for Report', $report['id'], array('class' => 'block-labels')); ?>
				<div class="form-inputs block-inputs">
					<?= form_textarea($report); ?>
					<div class="form-input-notes error-messages"></div>
				</div>
				<div class="right-aligned submit-conts">
					<?= form_submit($report_button); ?>
				</div>
			</form>
		</div>
	</div>
</div>

<section id="tutor-profile" class="cf pages containers profiles">
	<header class="persistent-headers profile-header" id="persistent-profile-header" data-appear-offset="280">
		<div class="backgrounds"></div>
		<div class="containers">
			
			<span title="Contact <?= $tutor['display_name'] ?>" id="header-contact-user-button" class="buttons color-3-buttons truncate"><span class="user-contact-button-name">Contact <?= $tutor['display_name'] ?></span></span>
			<img alt="Tutor photo" id="profile-header-avatar" src="<?= $tutor['avatar_url'] ?>">
			<h1 href="#top" id="profile-header-name" title="<?= $tutor['display_name'] ?>" class="truncate"><?= $tutor['display_name'] ?></h1>
		</div>
	</header>

	<div class="col-1">
		<section id="user-intro" class="profile-secs cf">
			<div class="review-cover"></div>

			<div id="user-avatar-cont">
				<img alt="Tutor photo" itemprop="image" id="user-avatar" src="<?= $tutor['avatar_url'] ?>">
			</div>
			<h1 id="user-name">
				<span>
					<span id="user-name-content" itemprop="name"><?= $tutor['display_name'] ?></span>
				</span>
			</h1>
			<div id="user-left-intro-bits">

				<div id="text-location">
					<img alt="Flag photo" id="flag" src="<?= $tutor['flag_url'] ?>">
					<span id="city-country" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span id="city" itemprop="addressLocality"><?= $tutor['city']?></span>, <span id="country" itemprop="addressCountry"><?= $tutor['country'] ?></span>
					</span>
				</div>
				
				<div id="gender-age">
					<? if ($tutor['gender']): ?>
						<span class="bull">&bull;</span>
						<span id="gender"><?= $tutor['gender'] ?></span>
					<? endif; ?>
				</div>

				<div id="user-right-intro-bits">
					<div class="price-conts">
						<div class="prices">
							<span class="price-val">
							<? if ($tutor['hourly_rate_high'] > 0): ?>

								<span class="hourly-prices" itemprop="priceRange"><?= $currency_sign.$tutor['hourly_rate'] ?> - <?= $currency_sign.$tutor['hourly_rate_high'] ?><span class="per-hour"> / hour</span> <span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

							<? elseif ($tutor['price_type'] == 'per_hour'): ?>

								<span class="hourly-prices"><span itemprop="priceRange"><?= $currency_sign.$tutor['hourly_rate'] ?><span class="per-hour"> / hour</span></span> <span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

							<? elseif ($tutor['price_type'] == 'free'): ?>

									<span class="frees" itemprop="priceRange">Free</span>

									<? if ($tutor['reason']): ?>
										<span class="reason-for-frees"><span class="tiny-aftertext">(<span class="same-page-links no-pointer" title="<?= nl2br($tutor['reason']) ?>">why?</span>)</span></span>
									<? endif; ?>
							<? endif; ?>
							</span>
						</div>
					</div>
					<? 
					if (!empty($tutor['reviews']))
					{
						echo '<div class="review-conts"><form>';	

						$max = 5.5; // *n because each star is broken into n; +1 because loop starts at 1, not 0
						for ($i = 0.5; $i < $max; $i+=0.5)
						{
							echo '<input name="star-main" disabled="disabled" type="radio" '.($tutor['average_rating'] == $i ? 'checked="checked"' : '').' class="star {split:2}"/>';
						}
						echo '</form></div>';
					}
					?>
				</div>
			</div>
		</section>

		<div id="profile-section-selection-cont">
			<span class="link-like active" title="" data-target="#user-details">Details</span> | 
			<span class="link-like" title="" data-target="#tutor-location">Location</span> | 

			<? if ($tutor['has_availability']): ?>
			<span class="link-like" title="" data-target="#tutor-availability">Availability</span> | 
			<? endif; ?>
	
			<? if (!(empty($tutor['external_reviews']) && empty($tutor['reviews']))): ?>
			<span class="link-like" title="" data-target="#user-reviews-sec, #user-external-reviews-sec">Reviews</span> | 
			<? endif; ?>
	
			<? if(!empty($tutor['links'])): ?>
			<span class="link-like" title="" data-target="#tutor-links">Links</span> | 
			<? endif; ?>
	
			<span class="link-like" title="" data-target="#user-contact">Contact</span>
		</div>

		<section id="user-details" class="profile-secs">
			<div id="subjects-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="Subjects photo" class="profile-label-images" id="subjects-image" src="<?= base_url('assets/images/profile/subjects.png') ?>">
					<span class="profile-label-content">Subjects</span>
				</div>


				<div id="subjects" class="profile-sec-contents">
					<div class="block-items" id="subjects-item" data-item-type="subjects">
						<? foreach($tutor['subjects_array'] as $subject): 
								$subject_title = "See all tutors that teach $subject near {$tutor['city']}, {$tutor['country']}";
						?><a href="<?= base_url('find/local/tutors/'.$tutor['city'].', '.$tutor['country'].'/'.$subject).'?distance='.DEFAULT_FIND_DISTANCE ?>" title="<?= $subject_title ?>" class="subject-names"><?= $subject ?></a><? endforeach; ?>
					</div>
				</div>

			</div>

			<? if (!empty($tutor['education'])): ?>
			<div id="education-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="Education photo" class="profile-label-images" id="education-image" src="<?= base_url('assets/images/profile/education.png') ?>">
					<span class="profile-label-content">Education</span>
				</div>

				<div id="education" class="profile-sec-contents">
					<div id="education-item-conts" class="item-conts">
					<? foreach($tutor['education'] as $item): ?>
						<?// var_dump($item); ?>
						<div class="education-items block-items" data-item-type="education">

							<div class="school-conts">
								<span class="schools"><?= $item['school'] ?></span>
							</div>
							<div class="studied">
								<div class="degrees-fields" style="<? if (!($item['degree'] || $item['field'])) echo ' display: none; '; ?> ">
									<span class="degrees"><?= $item['degree'] ?></span><span class="degree-field-divs" style=" <? if (!($item['degree'] && $item['field'])) echo ' display: none; '; ?> "> - </span><span class="fields"><?= $item['field'] ?></span>
								</div>
								<div class="starts-ends">
									<span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span>
								</div>
							</div>

							<div class="education-notes" style="<?= ($item['notes'] ? '' : 'display: none;') ?>"><?= nl2br($item['notes']) ?></div>
						</div>
					<? endforeach; ?>
					</div>
				</div>
			</div>
			<? endif; ?>

			<? if (!empty($tutor['experience'])): ?>
			<div id="experience-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="Experience photo" class="profile-label-images" id="experience-image" src="<?= base_url('assets/images/profile/experience.png') ?>">
					<span class="profile-label-content">Experience</span>
				</div>


				<div id="experience" class="profile-sec-contents">
					<div id="experience-item-conts" class="item-conts">
					<? foreach($tutor['experience'] as $item): ?>
						<div class="experience-items block-items" data-item-type="experience">

							<div class="companies">
								<? if ($item['company']): ?>
									<span class="company-values"><?= $item['company'] ?></span>
								<? else: ?>
									<span class="company-values self-employed">Self-Employed</span>
								<? endif; ?>
							</div>
							<div class="positions-dates-locations">
								<span class="positions"><?= $item['position'] ?></span>
								<div class="dates-locations">
									<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span></span>
								</div>
							</div>

							<div class="descriptions" style="<?= ($item['description'] ? '' : 'display: none;') ?>"><?= nl2br($item['description']) ?></div>
						</div>
					<? endforeach; ?>
					</div>

				</div>
			</div>
			<? endif; ?>

			<? if(!empty($tutor['volunteering'])): ?>
			<div id="volunteering-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="Volunteering photo" class="profile-label-images" id="volunteer-image" src="<?= base_url('assets/images/profile/volunteer.png') ?>">
					<span class="profile-label-content">Volunteer <br>Work</span>
				</div>


				<div id="volunteering" class="profile-sec-contents">
					<div id="volunteering-item-cont" data-type="volunteering" class="item-conts">
					<? foreach($tutor['volunteering'] as $item): ?>
						<div class="volunteering-items block-items" id="volunteering-<?= $item['id'] ?>" data-item-type="volunteering" data-item-id="<?= $item['id'] ?>">

							<div class="companies">
								<span class="company-values"><?= $item['company'] ?></span>
							</div>
							<div class="positions-dates-locations">
								<span class="positions"><?= $item['position'] ?></span>
								<div class="dates-locations">
									<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span></span>
								</div>
							</div>

							<div class="descriptions" style="<?= ($item['description'] ? '' : 'display: none;') ?>"><?= nl2br($item['description']) ?></div>
						</div>
					<? endforeach; ?>
					</div>
				</div>
			</div>
			<? endif; ?>

			<? if(!empty($tutor['about'])): ?>
			<div id="about-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="About photo" class="profile-label-images" id="about-image" src="<?= base_url('assets/images/profile/about.png') ?>">
					<span class="profile-label-content">About</span>
				</div>
				
				<div id="about" class="profile-sec-contents">
					<div class="block-items" id="about-item" data-item-type="about">
						<?= nl2br($tutor['about']) ?>
					</div>

				</div>
			</div>			
			<? endif; ?>

		</section>  <!-- /#user-details --> 

		<? if (!empty($tutor['reviews'])): ?>
		<section id="user-reviews-sec" class="profile-secs <? if (!empty($tutor['external_reviews'])) { echo 'external-reviews-beneath'; } ?>">
			<header itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" class="cf">
				<h2>Reviews (<span itemprop="reviewCount"><?= count($tutor['reviews']) ?></span>)</h2>
				<div class="average-review">
					<span>Average Rating: <span id="meta-average-rating" itemprop="ratingValue"><?= $tutor['average_rating'] ?></span></span>

					<?
						$max = 5.5; // *n because each star is broken into n; +1 because loop starts at 1, not 0
						for ($i = 0.5; $i < $max; $i+=0.5)
						{
							echo '<input name="star-average" disabled="disabled" type="radio" '.($tutor['average_rating'] == $i ? 'checked="checked"' : '').' class="star {split:2}"/>';
						}
					?>

				</div>
			</header>

			<div class="user-reviews-cont">
				<? foreach ($tutor['reviews'] as $review): 
				?>
					<div class="user-reviews" data-review-id="<?= $review['id'] ?>">
						<form>
							<div class="review-statistics">
								<div class="review-rating">
									<?= $review['rating'] ?>
								</div>
								<div class="review-detailed-ratings-cont">
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['expertise'] ?>
										</div>
										<span class="review-detailed-rating-names">Expertise</span>
									</div>
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['helpfulness'] ?>
										</div>
										<span class="review-detailed-rating-names">Helpfulness</span>
									</div>
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['response'] ?>
										</div>
										<span class="review-detailed-rating-names">Response</span>
									</div>
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['clarity'] ?>
										</div>
										<span class="review-detailed-rating-names">Clarity</span>
									</div>
								</div>
							</div><div class="review-thread-conts" data-comment-id="<?= $review['comment']['id'] ?>">
								<ul class="review-threads">
									<li class="review-thread-items">
										<div class="review-thread-item-text-conts">
											<div class="review-thread-item-content">
												<?= nl2br($review['comment']['content']) ?>
											</div>
											<div class="review-thread-item-authors">
												<? if ($review['comment']['contact_id'] == DELETED_ID || !$review['comment']['contact_id']): ?>
													<img alt="Student's photo" src="<?= base_url(DELETED_AVATAR_PATH) ?>" class="review-thread-item-author-avatars">
													<span class="review-thread-item-author-names deleted more-info-in-title-text" title="This student has deleted their account"><?= DELETED_NAME ?></span>
												<? else: ?>
													<img alt="Student's photo" src="<?= $review['comment']['avatar_url'] ?>" class="review-thread-item-author-avatars">
													<a href="<?= $review['comment']['profile_link'] ?>" class="review-thread-item-author-names"><?= $review['comment']['display_name'] ?></a>
												<? endif; ?>
												<span class="review-thread-item-author-posted">(posted <?= time_elapsed_string($review['comment']['posted'], ' ago') ?>)</span>
											</div>
										</div>
										<div class="review-thread-meta">
											<a title="Please report any offensive, vulgar, or fake reviews. Thanks!" href="javascript:void(0);" class="meta-items review-report-items" data-dropdown="#dropdown-report">Report</a>
										</div>
									</li>
								</ul>
							</div>
						</form>
					</div>
				<? endforeach; ?>
			</div>
		</section>
		<? endif; ?>

		<? if (!empty($tutor['external_reviews'])): ?>
		<section id="user-external-reviews-sec" class="profile-secs">
			<header class="cf">
				<h2>External Reviews (<?= count($tutor['external_reviews']) ?>)</h2>
				<span class="same-page-links no-pointer" id="external-reviews-what" title="These reviews were posted on other websites and were submitted by the tutor. We encourage you to click 'Visit Site' under the reviews to see the original site and to determine the credibility of each one.">What's this?</span>
			</header>
			<div id="er-item-cont" data-type="er" class="item-conts"> 
				<? foreach($tutor['external_reviews'] as $item): ?>
					<div class="er-items block-items" data-external-review-id="<?= $item['id'] ?>">
		
						<div class="er-ratings-and-icons">
							<div class="er-ratings">
								<? if ($item['rating'] == 0.0): ?>
									<span class="no-rating-text">No Rating</span>
								<? else: ?>
									<form>
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 0.5) echo 'checked="checked"' ?> value="0.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 1) echo 'checked="checked"' ?> value="1.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 1.5) echo 'checked="checked"' ?> value="1.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 2) echo 'checked="checked"' ?> value="2.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 2.5) echo 'checked="checked"' ?> value="2.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 3) echo 'checked="checked"' ?> value="3.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 3.5) echo 'checked="checked"' ?> value="3.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 4) echo 'checked="checked"' ?> value="4.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 4.5) echo 'checked="checked"' ?> value="4.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 5) echo 'checked="checked"' ?> value="5.0" >
									</form>
								<? endif; ?>
							</div>
						</div

						><div class="er-content-and-metas">
							<div class="er-contents">
								<?= nl2br($item['content']) ?>
							</div>

							<div class="er-metas">
								<span class="meta-items er-name-items">By: <span class="er-names"><?= $item['reviewer'] ?></span></a>
								|
								<a title="Visit the original website where this review was posted" target="_blank" rel="nofollow" href="<?= $item['url'] ?>" class="meta-items er-url-items">Visit Site</a>
								|<a title="Please report any offensive, vulgar, or fake reviews. Thanks!" href="javascript:void(0);" class="meta-items external-review-report-items" data-dropdown="#dropdown-report">Report review</a>
							</div>
						</div>

					</div>
				<?  endforeach; ?>
			</div>
		</section>
		<? endif; ?>

	</div>  <!-- /.col-1 -->
	<div class="col-2 cf">
		<section id="user-contact" class="profile-secs"> 	
			<span class="buttons color-3-buttons" id="user-contact-button">
				<span id="user-contact-button-content" title="Contact <?= $tutor['display_name'] ?>">Contact<span class="user-contact-button-name"> <?= $tutor['display_name'] ?></span></span>
			</span>

			<div class="pre-send">

				<div class="ajax-overlays">
					<div class="ajax-overlays-bg"></div>
					<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
				</div>

				<div class="sec-bodies">
					<div id="contact-tutor-form-cont" class="cf">
						<?= form_open($this->uri->uri_string(), $contact_tutor_form) ?>

						<div class="form-elements">
							<?= form_label('Name', $name['id'], array('class' => 'block-labels')); ?>
							<div class="form-inputs block-inputs">
								<?= form_input($name); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<div class="form-elements">
							<?= form_label('Email', $email['id'], array('class' => 'block-labels')); ?>
							<div class="form-inputs block-inputs">
								<?= form_input($email); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
<!--
						<div class="form-elements">
							<?// form_label('Phone Number <span class="tiny-aftertext">(optional)</span>', $phone['id'], array('class' => 'block-labels')); ?>
							<div class="form-inputs block-inputs">
								<?// form_input($phone); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
-->						<div class="form-elements">
							<?= form_label('Message', $message['id'], array('class' => 'block-labels')); ?>
							<div class="form-inputs block-inputs">
								<?= form_textarea($message); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>

						<?= form_submit($contact_button); ?>

						<?= form_close() ?>
					</div>
				</div>
			</div>
			<div id="under-contact-links-cont">
				<span title="<?= $favourite_text ?>" class="under-contact-links dropdown-padding link-like" id="update-favourites" data-favourited="<?= $favourited ?>"><?= ($favourited ? 'Unfavourite' : 'Favourite') ?></span><img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="favourite-loader"> | 
				<span href="#" data-dropdown="#dropdown-invite-to-tutor-request" class="under-contact-links link-like" title="<?= $invite_text ?>">Invite</span>

				<div id="dropdown-invite-to-tutor-request" class="dropdown dropdown-relative dropdown-tip dropdown-anchor-right">
					<div class="ajax-overlays">
						<div class="ajax-overlays-bg"></div>
						<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
					</div>
	
					<? if ($this->session->userdata('user_id') == $tutor['id']): ?>
					<div class="dropdown-panel">
						<span class="no-click">Students will see their tutor requests appear in a drop down here. They can then invite you by clicking on one of them.</span>
					</div>
					<? else: ?>
				    <ul class="dropdown-menu">
						<? 
						if ($logged_in): 
							$requests = $this->session->userdata('requests');
							if (!empty($requests)):
							
								foreach($requests as $request):

									if (in_array($request['id'], $tutor['affiliated_request_ids'])):
						?>
							        <li>
							        	<a href="javascript:void(0);" class="students-requests crossed" title="The tutor has already applied or has been invited to this request">
							        		Invite to <span class="request-names"><?= $request['subjects_string'] ?></span>
							        	</a>
							        </li>
						<?
									else:
						?>
							        <li>
							        	<a href="javascript:void(0);" class="students-requests" data-id="<?= $request['id'] ?>" title="Posted <?= time_elapsed_string($request["posted"], ' ago') ?>">
							        		Invite to <span class="request-names"><?= $request['subjects_string'] ?></span>
							        	</a>
							        </li>
						<?
									endif;
								endforeach;
						?>
						        <li class="dropdown-divider"></li>
						<?		
							endif;
						?>
						<? 
						else: 
						?>
					        <li><a class="italic" href="javascript:void(0);" data-reveal-id="login-modal">Log in to see your tutor requests</a></li>
					        <li class="dropdown-divider"></li>
						<? 
						endif; 
						?>
					        <li><a href="javascript:void(0);" data-reveal-id="request-modal">Create new tutor request</a></li>
				    </ul>
					<? endif; ?>
				</div>
			</div>
		</section>

		<section id="tutor-location" class="profile-secs">
			<header>
				<h2>Location</h2>
			</header>
			<div class="sec-bodies">
				<div id="map"></div>

				<? if($tutor['has_can_meet']): ?>
				<div id="can-meet-cont">
					<div class="profile-labels">
						Can meet...
					</div> 
					<div id="can-meet" class="profile-sec-contents">
						<div class="can-meet-item-cols">
							<div class="can-meet-items can-meet-students-home <?= $tutor['can_meet']['students_home'] ? 'checked' : '' ?>">
								<span class="check-marks">&#10004;</span> 
								<span class="cross-marks">&times;</span>
								<span class="can-meet-texts">At student's home</span>
							</div>
							<div class="can-meet-items can-meet-tutors-home <?= $tutor['can_meet']['tutors_home'] ? 'checked' : '' ?>">
								<span class="check-marks">&#10004;</span> 
								<span class="cross-marks">&times;</span>
								<span class="can-meet-texts">At tutor's home</span>
							</div>
							<div class="can-meet-items can-meet-online-local <?= $tutor['can_meet']['online_local'] ? 'checked' : '' ?>">
								<span class="check-marks">&#10004;</span> 
								<span class="cross-marks">&times;</span>
								<span class="can-meet-texts">Online (local) (<span class="same-page-links no-pointer" title="Online with a local student">?</span>)</span>
							</div>
						</div>
						<div class="can-meet-item-cols second">
							<div class="can-meet-items can-meet-public <?= $tutor['can_meet']['public'] ? 'checked' : '' ?>">
								<span class="check-marks">&#10004;</span> 
								<span class="cross-marks">&times;</span>
								<span class="can-meet-texts">In public place</span>
							</div>
							<div class="can-meet-items can-meet-centre <?= $tutor['can_meet']['centre'] ? 'checked' : '' ?>">
								<span class="check-marks">&#10004;</span> 
								<span class="cross-marks">&times;</span>
								<span class="can-meet-texts">In tutor centre (<span class="same-page-links no-pointer" title="In an tutoring company's building">?</span>)</span>
							</div>
							<div class="can-meet-items can-meet-online-distant <?= $tutor['can_meet']['online_distant'] ? 'checked' : '' ?>">
								<span class="check-marks">&#10004;</span> 
								<span class="cross-marks">&times;</span>
								<span class="can-meet-texts">Online (distant) (<span class="same-page-links no-pointer" title="Online over long distances (e.g. different countries)">?</span>)</span>
							</div>
						</div>
					</div>
				</div>
				<? endif; ?>

				<? if(!empty($tutor['travel_notes'])): ?>
				<div id="travel-notes-cont">
					<h3 class="profile-labels">Travel Notes</h3>
					<div id="travel-notes" class="profile-sec-contents">
						<?= nl2br($tutor['travel_notes']) ?>
					</div>
				</div>
				<? endif; ?>
			</div>
		</section>

		<? if ($tutor['has_availability']): ?>
		<section id="tutor-availability" class="profile-secs">
			<header>
				<h2>Availability</h2>
			</header>
			<div id="availability-item">
				<?= $tutor['availability'] ?>
			</div>
		</section>
		<? endif; ?>

		<? if(!empty($tutor['links'])): ?>		
		<section id="tutor-links" class="profile-secs">
			<header>
				<h2>Links</h2>
			</header>
			<div id="links-item-cont" data-type="link" class="item-conts"> 
				<? foreach($tutor['links'] as $item): ?>
					<div class="link-items block-items truncate">
						<div class="link-images regular-link-image"></div>
						<a href="<?= $item['url'] ?>" title="<?= $item['description'] ?>" target="_blank" class="user-links" rel="nofollow"><?= $item['label'] ?></a>
					</div>
				<? endforeach; ?>
			</div>
		</section>
		<? endif; ?>

		<section id="user-meta" class="profile-secs">
			<a title="See requests this tutor has posted on their student profile" href="<?= base_url('students/'.$tutor['username']) ?>" class="meta-items dropdown-padding">Student Profile</a> |
			<a title="Share this profile through Facebook, Google+, or Twitter" href="javascript:void(0);" class="meta-items tutor-share-items" data-dropdown="#dropdown-share">Share</a> | <a title="Please report any offensive, vulgar, or fake profiles. Thanks!" href="javascript:void(0);" class="meta-items tutor-report-items" data-dropdown="#dropdown-report">Report</a>
		</section>
	</div>  <!-- /.col-2 -->

	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
	<!-- Tutor Profile (bottom) -->
	<ins class="adsbygoogle"
	     style="display:block"
	     data-ad-client="ca-pub-6947976348124168"
	     data-ad-slot="1737049136"
	     data-ad-format="auto"></ins>
	<script>
	(adsbygoogle = window.adsbygoogle || []).push({});
	</script>

</section>  <!-- /#tutor-profile -->

<? if(isset($previous_page)): ?>
<div id="post-profile" class="containers">
	<?= anchor($previous_page, "&laquo; <span>back to search results</span>", 'class="arrow-link back-to-search-results-links"') ?>.
</div>
<? endif; ?>

<script>

var map;

$(function() 
{
	$('body').prepend($('#dropdown-report'))
			 .prepend($('#dropdown-share'));

	$(window).resize(function()
	{
		if (window.vpWidth > <?= SCREEN_SUB_REGULAR ?>)
		{
			$('.profile-secs').show();
		}
		else
		{
			$('#profile-section-selection-cont span.active').click();
		}
	});

	$('span', '#profile-section-selection-cont').click(function()
	{
		var $link = $(this),
			$siblings = $link.siblings(),
			target = $link.attr('data-target'),
			speed = 200;

		$siblings.removeClass('active');
		$link.addClass('active');
		$('.profile-secs').not(target + ', #user-meta, #user-intro').hide();
		$(target).fadeIn(speed, function()
		{
			if (target == '#tutor-location')
			{
//				console.log('resized');
				google.maps.event.trigger(map, 'resize');
			}
			else if (target == '#user-contact' && !$('#user-contact-button').hasClass('active'))
			{
				showContactForm();
			}
		});
	});

	<? if ($user_id != $tutor['id']): ?>
	$('#update-favourites').click(function()
	{
		updateFavourites($(this));
	});
	<? endif; ?>

	$('.review-report-items').click(function()
	{
		var reviewId = $(this).parents('.user-reviews').attr('data-review-id');

		$('#dropdown-report')
		.find('#report-id').val(reviewId).end()
		.find('#report-type').val(<?= REPORT_TYPE_REVIEW ?>);
	});

	$('.external-review-report-items').click(function()
	{
		var reviewId = $(this).parents('.er-items').attr('data-external-review-id');

		$('#dropdown-report')
		.find('#report-id').val(reviewId).end()
		.find('#report-type').val(<?= REPORT_TYPE_EXTERNAL_REVIEW ?>);
	});

	$('.tutor-report-items').click(function()
	{
		$('#dropdown-report')
		.find('#report-id').val("<?= $tutor['username'] ?>").end()
		.find('#report-type').val(<?= REPORT_TYPE_TUTOR ?>);
	});

	$('#dropdown-share').on('show', function()
	{
		var $this = $(this);

		scrollAndFocus($this);
	});

	$('#dropdown-report').on('show', function()
	{
		var $this = $(this);
		$this.find('textarea').focus().val('');

		scrollAndFocus($this);
	});

	$('#dropdown-report').find('form').submit(function()
	{	
		var $form = $(this),
			$dropdown = $form.parents('.dropdown'),
			$overlay = $dropdown.find('.ajax-overlays');
		
		$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$.ajax(
		{
			url: "<?= base_url('report') ?>",
			type: "POST",
			data: $form.serialize(),
			dataType: 'json'
		}).done(function(response)
		{
			$form.validate(response.errors);
//			console.log(response);

			if (response.success == true)
			{
				noty(
				{
					text: "<b>Reported!</b><hr>We'll look into it. Thanks for helping keep Tutorical prim and proper.",
					type: 'success',
					timeout: 8000
				});

				$dropdown.dropdown('hide');
			}
			else if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
			{
				ajaxFailNoty();				
			}
		}).fail(function() 
		{
			ajaxFailNoty();
		}).always(function() 
		{
			$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
			$.noty.closeAll();
		});

		return false;	
	});

	$('.students-requests').click(function()
	{
		var $this = $(this),
			$overlay = $this.parents('#dropdown-invite-to-tutor-request').find('.ajax-overlays');
		
		// This is needed to not close the dropdown when a crossed request is clicked
		if ($this.hasClass('crossed'))
			return false;

		$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$.ajax(
		{
			url: '<?= base_url("requests/invite") ?>',
			type: "POST",
			data: 
			{
				'username': <?= json_encode($tutor['username']) ?>,
				'request-id': $this.attr('data-id')
			},	
			dataType: 'json'
		}).done(function(response)
		{
			// console.log(response);

			if (response.success == true)
			{
				noty(
				{
					text: "<b>Tutor invited!</b><hr>Check your request later to see if they've posted an application.",
					type: 'success',
					timeout: 5000
				});

				$this.addClass('crossed').attr('title', 'The tutor has already applied or has been invited to this request');
			}

		}).fail(function() 
		{
			ajaxFailNoty();
		}).always(function() 
		{
			$.noty.closeAll();
			$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
		});


		return false;
	})

	$('#header-contact-user-button').click(function()
	{
		var scrollTop = $('#user-contact').offset().top;


		if (window.vpWidth <= <?= SCREEN_SUB_REGULAR ?>)
		{
			console.log('hey');
			$('#profile-section-selection-cont span[data-target=#user-contact]').click();
		}
		
		$("html, body").animate({ scrollTop: scrollTop }, 200, function()
		{
			$tutorContactContent = $('.pre-send', '#user-contact');
			if (!$tutorContactContent.is(':visible'))
			{
				showContactForm();
			}
			else
			{
				$tutorContactContent.find(':input').first().focus();
			}
		});	
	});

	<? if (!$tutor['is_active']): 
		$noty = "<b>Your profile is hidden from the public.</b><hr>You can change this in your ".anchor('account/profile','Account').".";
	?>
		noty(
		{
			text: <?= json_encode($noty) ?>,
			type: 'warning'
		});
	<? endif; ?>

	setupOvernightTimeLinks('#tutor-profile');

	$('input[type=radio].star').rating();

//  $('#user-contact .pre-send').hide();

  var $contactButton = $('#user-contact-button');
  
//  $contactButton.textfill({ maxFontPixels: 16 });
  
//  $('#user-name').textfill({ maxFontPixels: 26 });

	<? 
/*
	if ($this->session->userdata('init') && $tutor['role'] == ROLE_ADMIN): 

	$contactButton.click(function() 
	{
		$.ajax(
		{
			url: '<?= base_url("contact/admin") ?>',
			type: "POST",
			data: 
			{
				'username': <?= json_encode($tutor['username']) ?>
			},	
			dataType: 'json'
		}).done(function(response)
		{
			 // console.log(response);

			if (response.success == true)
			{
				window.location = "<?= base_url('account/tutors') ?>";
			}

		});
	});

	else: 
*/
	?>

	$contactButton.click(showContactForm);

	<?// endif; ?>

	$('#contact-tutor-email').addMailcheck();

	$('#<?= $contact_tutor_form["id"] ?>').submit(function() {
		var $form = $(this),
			$contact = $('#user-contact'),
			$message = $form.find('[name=contact-tutor-message]'),
			message = nl2br($message.val()),
			$overlay = $contact.find('.ajax-overlays'),
			email = $form.find('[name=contact-tutor-email]').val();

		$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);
		$form.find(':input').prop('disabled', true);

		$.ajax({
			url: '<?= base_url("contact/tutor") ?>',
			type: "POST",
			data: {
				'contact-user-name': $form.find('[name=contact-user-name]').val(),
				'contact-tutor-email': email,
				'contact-tutor-message': message,
				'username': <?= json_encode($tutor['username']) ?>,
				as_f: $form.find('[name=as_f]').val(),
				as_e: $form.find('[name=as_e]').val(),
				as_h: $form.find('[name=as_h]').val()
			},	
			dataType: 'json'
		}).done(function(response) {
			// // console.log(response);

			$form.validate(response.errors);

			if (response.success == true)
			{				
				$('#contact-modal-email').val(email);

				if (response.data.accountStatus == <?= ACCOUNT_STATUS_JUST_MADE ?>)
				{
					noty(
					{
						text: "<b>Message Sent!</b><hr>We've made a student account for you to track any tutors you've contacted. Check <a href='http://"+response.data.emailDomain+"'>your email</a> for more info.",
						type: 'success'
					});
				}
				else if (response.data.accountStatus == <?= ACCOUNT_STATUS_JUST_MADE_EMAIL_QUEUED ?>)
				{
					noty(
					{
						text: "<b>Message Sent!</b><hr>We've made a student account for you to track any tutors you've contacted. Check <a href='http://"+response.data.emailDomain+"'>your email</a> in about 1 hour for more info. <span class='tiny-aftertext'>(our mail server is a bit slow right now)</span>",
						type: 'success'
					});
				}
				else if (response.data.accountStatus == <?= ACCOUNT_STATUS_LOGGED_IN ?> || response.data.accountStatus == <?= ACCOUNT_STATUS_UNKNOWN ?>)
				{
					noty(
					{
						text: '<b>Message Sent!</b>',
						type: 'success',
						timeout: '3000'
					});
				}
				else if (response.data.accountStatus == <?= ACCOUNT_STATUS_EXISTS_BUT_INACTIVE ?>)
				{
					// <span class='tiny-aftertext'>(<span class='same-page-links'>Resend email</span>)</span>
					noty(
					{
						text: "<b>Message Sent!</b><hr>Check <a href='http://"+response.data.emailDomain+"'>your email</a> to log in to your account.",
						type: 'success'
					});
				}
				else if (response.data.accountStatus == <?= ACCOUNT_STATUS_UNLOGGED_USER ?>)
				{
					<? if (!$logged_in): ?>
					noty(
					{
						text: '<b>Message Sent!</b><hr><?= anchor('login','Log in', 'class="temp-modal-links" data-reveal-id="login-modal"') ?> to view the tutors you\'ve messaged.',
						type: 'success',
						timeout:  '5000'
					});					

					$('.temp-modal-links').click(function(e)
					{
						e.preventDefault();

						var revealId = $(this).attr('data-reveal');
						$('#'+revealId).foundation('reveal', 'open', {
							opened: function() {
								var $this = $(this);

								if (globalAutofocus)
									$this.find('input:first').focus();
							}
						});
					});

					<? else: ?>

					noty(
					{
						text: '<b>Message Sent!</b><hr>Log out and in to your account to view the tutors you\'ve messaged.',
						type: 'success',
						timeout:  '7000'
					});

					<? endif; ?>
				}

				var postSendClass = 'send-success';
				
				$message.val('').height(100);		// Find a better way than hardcoding			
				$contact.find('.pre-send').slideUp(<?= STANDARD_FADE_SPEED ?>);
				$contactButton.removeClass('active');
			}
			else if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
			{
				ajaxFailNoty();				
			}

		}).fail(function() {
			ajaxFailNoty();
		}).always(function() {
			$.noty.closeAll();
			$form.find(':input').prop('disabled', false);
			$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
		});

		return false;
	});

	// Only go ahead if GMaps has loaded
	if (typeof google === 'object' && typeof google.maps === 'object')
	{
		var mapOptions = 
		{
			center: new google.maps.LatLng(<?= $tutor['lat'].','.$tutor['lon'] ?>),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoom: 14,
			panControl: false,
			zoomControl: true,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,

			keyboardShortcuts: false
		};

		map = new google.maps.Map(document.getElementById("map"), mapOptions);	// this is a glob var
		
		var marker = new google.maps.Marker(
			{
				map: map,
			    animation: google.maps.Animation.DROP,
			    position: mapOptions.center
			});

		google.maps.event.addListener(marker, 'click', toggleBounce);
	}
	else
	{
		noty(
		{
			text: "<b>Sorry. Google Maps couldn't load. Please refresh the page.</b><hr>If you see this message again, then don't worry, we're working on it.",
			type: 'warning',
			timeout: 8500
		});
	}
});

function showContactForm()
{
	var $contactButton = $('#user-contact-button'),
		$form = $('#user-contact .pre-send');

	if ($form.is(':visible'))
	{
		$contactButton.removeClass('active', <?= FAST_FADE_SPEED ?>);
		$form.slideUp(<?= FAST_FADE_SPEED ?>);
	}
	else
	{
		$contactButton.addClass('active', <?= FAST_FADE_SPEED ?>);
		$form.slideDown(<?= FAST_FADE_SPEED ?>, function() 
		{
			$form.find('textarea').autosize();

			if ($form.find('[name=contact-user-name]').val())
			{
				if ($form.find('[name=contact-tutor-email]').val())
				{
					$form.find('[name=contact-tutor-message]').focus();
				}
				else
				{
					$form.find('[name=contact-tutor-email]').focus();	
				}
			}
			else
			{
				$form.find('[name=contact-user-name]').focus();	
			}					
		});
	}
}

function toggleBounce() 
{
	if (marker.getAnimation() != null) 
	{
		marker.setAnimation(null);
	} 
	else 
	{
		marker.setAnimation(google.maps.Animation.BOUNCE);
	}
}


function updateFavourites($favouriteLink)
{
<? if (!$logged_in): ?>
	noty(
	{
		text: '<?= 'Sorry! You must '.anchor('login','log in', 'data-modal-state="temp" data-reveal-id="login-modal" data-redirect="'.current_url().'"').' to favourite a tutor.' ?>',
		type: 'warning',
		timeout: 5000
	});

<? else: ?>
	var favourite = $favouriteLink.attr('data-favourited'),
		favouriteText;

	if (favourite == 1)
	{
		favourite = 0;
		newFavouriteText = 'Favourite';
	}
	else
	{
		favourite = 1;
		newFavouriteText = 'Unfavourite';
	}

	var data = {
		tutor_id : <?= $tutor['id'] ?>,
		favourite : favourite
	},
		$loader = $('#favourite-loader');

	$favouriteLink.unbind('click');
	$loader.show();

//	console.log(data); return;

	$.ajax(
	{
		url: "<?= base_url('account/update_favourites') ?>",
		type: "POST",
		data: data,
		dataType: 'json'
	}).done(function(response)
	{
		console.log(response);

		if (response.success == true)
		{
			noty(
			{
				text: "<b>"+$favouriteLink.text()+"d!</b>",
				type: 'success',
				timeout: 1500
			});

			$favouriteLink.attr('data-favourited', favourite).text(newFavouriteText);
		}
		else if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
		{
			ajaxFailNoty();				
		}
	}).fail(function() 
	{
		ajaxFailNoty();
	}).always(function() 
	{
		$loader.hide();
		$.noty.closeAll();

		$favouriteLink.click(function()
		{
			updateFavourites($(this));
		});
	});
<? endif; ?>
}
$(function()
{
//	$('#tutor-location').hide().css('opacity', 1);
});

</script>