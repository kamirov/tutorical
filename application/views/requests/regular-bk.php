<?
//var_dump($this->session->all_userdata());
//var_dump($request);

//var_dump($visitor_role);

$contact_tutor_form = array(
	'id' => 'contact-tutor-form',
	'class' => 'ased'
);

$hourly_rate = array(
	'name'	=> 'hourly-rate',
	'id' => 'proposal-hourly-rate',
	'placeholder' => '0.00',
	'class' => 'hourly-rates'
);

$currency_name = 'currency';
$active_currency = 'CAD';

$message = array(
	'name'	=> 'message',
	'id' => 'proposal-message',
	'class'	=> 'autosize message',
);

$contact_button = array(
	'value' => 'Send', 
	'class' => 'buttons color-3-buttons',
	'id' => 'submit-user-contact-button', 
	'name' => 'submit',
);

if ($request['type'] == REQUEST_TYPE_LOCAL)
{
	$request_type_text = "only local tutors";
}
elseif ($request['type'] == REQUEST_TYPE_DISTANCE)
{
	$request_type_text = "only distance tutors";
}
else
{
	$request_type_text = "all tutors";
}

if (isset($application))
{
	$hourly_rate['value'] = $application['price'];
 	$message['value'] = $application['message'];
	$contact_button['value'] = 'Update';
}

$report = array(
	'name'	=> 'message',
	'id' => 'report-reason',
	'placeholder' => "Tell us what's wrong here..."
);

$report_button = array(
	'value' => 'Report', 
	'class' => 'buttons color-3-buttons',
);

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
	                  text: <?= json_encode($request['display_name']." is looking for ".$request['subjects_string']." tutors in ".$request['location_city'].", ".$request['location_country']." | "); ?>
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

<section id="request-full" class="cf pages containers profiles">

	<div class="col-1">
		<div>
			<span id="page-heading">Tutor Request Details</span>

			<? if ($visitor_role == 'visitor-poster'): ?>
				<? 
				if ($request['status'] == REQUEST_STATUS_OPEN):
				?>
				<div class="button-groups request-student-actions">
					<a href="javascript:void(0);" class="buttons color-3-buttons" data-reveal-id="request-modal-edit">Edit</a><?= anchor("requests/close/{$request['id']}", "Close Request", 'class="buttons" title="Close the request to not receive any more applications. If you\'ve chosen a tutor, accept their application and the request will be automatically closed."')?>
				</div>
				<?
				elseif (!$request['accepted_id']): // Only show Open option IF no student has been accepted
				?>
				<div class="request-student-actions">
				<?= anchor("requests/open/{$request['id']}", "Open Request", "class='buttons color-3-buttons' title='Open the request to let tutors apply.'");
				?>
				</div>
				<?
				endif;
				?>
			<? endif; ?>

		</div>

		<section id="request-meta" class="cf">
			<div id="request-student">
				<? if ($request['user_id'] == DELETED_ID): ?>
					<span id="request-student-name" class="deleted" title="This student has deleted their account"><?= $request['display_name'] ?></span>
					<img alt="Student's photo" src="<?= $request['avatar_url'] ?>" id="request-student-avatar">
				<? else: ?>
				<?= anchor($request['profile_path'], $request['display_name'], 'id="request-student-name"') ?>
				<?= anchor($request['profile_path'], '<img alt="Student\'s photo" src="'.$request['avatar_url'].'" id="request-student-avatar">') ?>
				<? endif; ?>
			</div>
			<span id="request-posted">
				Posted <?= time_elapsed_string($request['posted']) ?> ago
			</span>
		</section>

		<section id="user-details" class="profile-secs">

			<div id="max-price-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="Max price image" class="profile-label-images" id="requests-max-price" src="<?= base_url('assets/images/profile/requests-max-price.png') ?>">
					<span class="profile-label-content">Max Price</span>
				</div>
				
				<div id="max-request-price" class="profile-sec-contents">
					<div class="block-items" id="max-price-item" data-item-type="max-price">
						<? if ($request['price'] == '0.00'): ?>
							Not Listed
						<? else: 
							echo $currency_sign.$request['price'].' / hour ('.$request['currency'].')';
						   endif;
						?>
					</div>

				</div>
			</div>			

			<div id="subjects-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="Subjects image" class="profile-label-images" id="subjects-image" src="<?= base_url('assets/images/profile/subjects.png') ?>">
					<span class="profile-label-content">
						<?= ($request['subjects_count'] > 1 ? 'Subjects' : 'Subject') ?>
					</span>
				</div>


				<div id="subjects" class="profile-sec-contents">
					<div class="block-items" id="subjects-item" data-item-type="subjects">
						<span class="subject-names"><?= $request['subjects_string'] ?></span>
					</div>
				</div>

			</div>

			<? if(!empty($request['details'])): ?>
			<div id="details-cont" class="details-secs">
				<div class="profile-labels">
					<img alt="Details image" class="profile-label-images" id="details-image" src="<?= base_url('assets/images/profile/request-details.png') ?>">
					<span class="profile-label-content">Details</span>
				</div>
				
				<div id="details" class="profile-sec-contents">
					<div class="block-items" id="details-item" data-item-type="details">
						<?= nl2br($request['details']) ?>

						<div id="request-type-text">
							This request is for <b><?= $request_type_text ?></b>.
						</div>
					</div>
				</div>
			</div>	
			<? endif; ?>

		</section>  <!-- /#user-details --> 

		<section id="applications-sec" class="profile-secs">

			<header>
				<? if (!$request['applications']): ?>
					<h2>No applications yet</h2>
				<? else: ?>
					<h2>Applications (<span id="applications-count"><?= $request['applications_meta']['count'] ?></span>)</h2>
				<? endif; ?>
			</header>

			<? if ($request['applications']): ?>

				<div id="applications-info">
					<span class="applications-price-info-cont">
						<span class="price-info"><span class="labels">Low</span>: <?= $currency_sign ?><span id="min-price"><?= $request['applications_meta']['min_price'] ?></span></span> |
						<span class="price-info"><span class="labels">Average</span>: <?= $currency_sign ?><span id="avg-price"><?= $request['applications_meta']['avg_price'] ?></span></span> |
						<span class="price-info"><span class="labels">High</span>: <?= $currency_sign ?><span id="max-price"><?= $request['applications_meta']['max_price'] ?></span></span>
					</span>
				</div>

				<section class="applications-cont">
			<? 
				foreach ($request['applications'] as $application):
			?>
					<div class="applications" data-id="<?= $application['id'] ?>">
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>

						<div class="offer-headers">
							<?= anchor('tutors/'.$application['username'], '<img alt="Tutor photo" class="offer-avatars" src="'.base_url($application['avatar_path']).'" />') ?>
							<?= anchor('tutors/'.$application['username'], $application['display_name'], 'class="offer-names"') ?> <?= ($application['status'] == RESPONSE_STATUS_APPROVED ? '<span class="accepted-text" title="This tutor\'s offer has been accepted by the student">Accepted</span>' : ''); ?>
						</div>

						<div class="offer-content">
							<? if ($this->session->userdata('init')): ?>
								<i><?= ($application['snippet'] ?: 'A Tutorical tutor') ?></i><br><br>
								<div class="offer-proposed-hourly-rates">
									<span><?= $currency_sign ?><span class="application-prices"><?= $application['price'] ?></span> / hour (<?= $request['currency'] ?>)</span>
								</div>
								<?= nl2br($application['message']) ?>
							<? endif; ?>
							<? if ($visitor_role == 'visitor-poster'): ?>
								<? if (!$this->session->userdata('init')): ?>	
									<div class="offer-proposed-hourly-rates">
										<span><?= $currency_sign.$application['price'] ?> / hour (<?= $request['currency'] ?>)</span>
									</div>
									<?= nl2br($application['message']) ?>
								<? endif; ?>
								
								<? if ($request['status'] == REQUEST_STATUS_OPEN): ?>
									<div class="offer-buttons-cont">
										<span class="buttons color-3-buttons accept-buttons" title="Accept this tutor and close the request">Accept</span>
										<span class="buttons reject-buttons" title="Reject this tutor (they might apply later with a different offer)" data-dropdown="#dropdown-reject">Reject</span>
									</div>
								<? endif; ?>
							<? else: ?>
								<?= ($application['snippet'] ?: 'A Tutorical tutor') ?>
							<? endif; ?>
						</div>
						<div class="offer-meta">
							<span class="offer-posted">Posted <?= time_elapsed_string($application['posted']) ?> ago</span>
						</div>
					</div>
			<?
				endforeach;
			?>
				</section>
			<? endif; ?>

		</section>
	

	</div>  <!-- /.col-1 -->

	<div class="col-2 cf">
		<section id="user-contact" class="profile-secs"> 	
			<? if ($request['status'] == REQUEST_STATUS_OPEN): ?>

			<? if ($visitor_role == 'visitor-tutor'): ?>
				<span class="buttons color-3-buttons" id="user-contact-button">
					<span id="user-contact-button-content">Apply to this Tutor Request</span>
				</span>
			<? elseif ($visitor_role == 'visitor-tutor-applied'): ?>
				<? if ($application['status'] == RESPONSE_STATUS_REJECTED): ?>
					<span class="buttons color-3-buttons" id="user-contact-button">
						<span id="user-contact-button-content">Reapply to this Tutor Request</span>
					</span>
				<? else: ?>
					<span class="buttons color-3-buttons" id="user-contact-button">
						<span id="user-contact-button-content">Edit your Application</span>
					</span>
				<? endif; ?>
			<? elseif ($visitor_role == 'visitor-guest'): ?>
				<span class="buttons color-3-buttons" id="user-contact-button" data-reveal-id="login-modal" data-redirect="<?= current_url() ?>">
					<span id="user-contact-button-content">Log in to Apply</span>
				</span>
				
			<? else: 	// Either student or poster ?>
				<span class="buttons color-3-buttons disabled" id="user-contact-button">
					<span id="user-contact-button-content">This Request is Open</span>
				</span>
			<? endif; ?>

			<? if ($visitor_role == 'visitor-tutor' || $visitor_role == 'visitor-tutor-applied'): ?>
				<div class="pre-send">

					<div class="ajax-overlays">
						<div class="ajax-overlays-bg"></div>
						<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
					</div>

					<div class="sec-bodies">
						<div id="contact-tutor-form-cont" class="cf">
							<?= form_open($this->uri->uri_string(), $contact_tutor_form) ?>
							<div id="application-request-type-text">
								This request is for <b><?= $request_type_text ?></b>.
							</div>
							<div class="form-elements">
								<?= form_label('Proposed Hourly Rate', 'proposal-hourly-rate', array('class' => 'block-labels')); ?>
								<div class="form-inputs block-inputs">
									<?= $currency_sign.' '.form_input($hourly_rate); ?> per hour (<?= $request['currency'] ?>)
									<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $hourly_rate['name'] ?>"></div>
									<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $currency_name ?>"></div>
								</div>
							</div>
							<div class="form-elements">
								<?= form_label('Message', $message['id'], array('class' => 'block-labels')); ?>
								<div class="form-inputs block-inputs">
									<?= form_textarea($message); ?>
									<div class="form-input-notes error-messages"></div>
								</div>
							</div>
	
							<? if (isset($application)): ?>
								<span class="danger-page-links" id="delete-application-link">delete</span>
							<? endif; ?>
							<?= form_submit($contact_button); ?>

							<?= form_close() ?>
						</div>
					</div>
				</div>
				<? endif; ?>

			<? else: ?>
			<span class="buttons disabled" id="user-contact-button">
				<span id="user-contact-button-content">This Request is Closed</span>
			</span>
			<? endif; ?>

		</section>

		<section id="tutor-location" class="profile-secs">
			<header>
<!--
				<h2>Location</h2>
-->
			</header>
			<div class="sec-bodies">
				<div id="map"></div>
				<div id="under-map-cont">
					<div id="text-location">
						<img alt="Flag" class="flags" src="<?= $request['flag_url'] ?>">
						<span id="city-country">
							<span id="city"><?= $request['location_city']?></span>, <span id="country"><?= $request['location_country'] ?></span>
						</span>
					</div>				
				</div>
			</div>
		</section>

		<section id="user-meta" class="profile-secs">
			<a title="Share this request through Facebook, Google+, or Twitter" href="javascript:void(0);" class="meta-items tutor-share-items" data-dropdown="#dropdown-share">Share</a> | <a title="Please report any offensive, vulgar, or fake requests. Thanks!" href="javascript:void(0);" class="meta-items request-report-items" data-dropdown="#dropdown-report">Report Request</a>
		</section>
		

	</div>  <!-- /.col-2 -->

</section>  <!-- /#tutor-profile -->


<script>

var contactNoty,
	marker;

<? if ($request['applications']): ?>
	var offerCount = <?= $request['applications_meta']['count'] ?>,
		minPrice = <?= $request['applications_meta']['min_price'] ?>,
		avgPrice = <?= $request['applications_meta']['avg_price'] ?>,
		maxPrice = <?= $request['applications_meta']['max_price'] ?>,
		sumPrice = <?= $request['applications_meta']['sum_price'] ?>,
		$minPrice = $('#min-price'),
		$avgPrice = $('#avg-price'),
		$maxPrice = $('#max-price');
<? endif; ?>

$(function() 
{
	$('body').prepend($('#dropdown-report'))
			 .prepend($('#dropdown-share'));


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

 	$('.request-report-items').click(function()
 	{
 		$('#dropdown-report')
 		.find('#report-id').val("<?= $request['id'] ?>").end()
 		.find('#report-type').val(<?= REPORT_TYPE_REQUEST ?>);
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

	<? if ($rejected): ?>

		noty(
		{
			text: "<b>Your application was rejected&nbsp;&nbsp;:(</b><hr>To reapply, just edit your application",
			type: "warning"
		});

	<? endif; ?>

	$('.accept-buttons').click(function()
	{
		var $this = $(this),
			$application = $this.parents('.applications'),
			$overlay = $application.find('.ajax-overlays'),
			$count = $('#applications-count');

		if (confirm('Accept this tutor and close the request?'))
		{
			$overlay.fadeIn(<?= FAST_FADE_SPEED ?>);
			$.ajax({
				url: '<?= base_url("requests/accept") ?>',
				type: "POST",
				data: {
					'request-id': <?= $request['id'] ?>,
					'application-id': $application.attr('data-id'),
				},	
				dataType: 'json'
			}).done(function(response) 
			{
				if (response.success == true)
				{		
					location.reload();
				}
				else
				{
					$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
				}
			}).fail(function() 
			{
				ajaxFailNoty();
				$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
			}).always(function() 
			{
			});
		}
	});

	$('.reject-buttons').click(function()
	{
		var applicationId = $(this).parents('.applications').attr('data-id');

		$('#dropdown-reject')
		.find('#application-id').val(applicationId);
	});
	
	$('#final-reject-button').click(function()
	{
		var $this = $(this),
			$dropdown = $this.parents('.dropdown'),
			$form = $dropdown.find('form'),
			$overlay = $dropdown.find('.ajax-overlays'),
			$count = $('#applications-count'),
			applicationId = $form.find('[name=id]').val(),
			$application = $('.applications[data-id='+applicationId+']'),
			$price = $application.find('.application-prices'),
			price = +$price.text(),
			newMinPrice, newMaxPrice;

		$overlay.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax({
			url: '<?= base_url("requests/reject") ?>',
			type: "POST",
			data: {
				'request-id': <?= $request['id'] ?>,
				'application-id': applicationId,
				'message': $form.find('[name=message]').val()
			},	
			dataType: 'json'
		}).done(function(response) 
		{
			$form.validate(response.errors);

			if (response.success == true)
			{	
				if (offerCount == 1)
				{
					$count.parent().text('No applications yet');
					$('#applications-info').hide();
				}
				else
				{
					offerCount -= 1;
					sumPrice -= price;
					avgPrice = sumPrice / offerCount;

					newMinPrice = newMaxPrice = +$('.application-prices').not($price).eq(0).text();

					$('.application-prices').not($price).each(function()
					{
						var curPrice = +$(this).text();

						newMinPrice = Math.min(newMinPrice, curPrice);
						newMaxPrice = Math.max(newMaxPrice, curPrice);
					});

					$avgPrice.text(avgPrice.toFixed(2));
					$minPrice.text(newMinPrice.toFixed(2));
					$maxPrice.text(newMaxPrice.toFixed(2));

					$count.text(offerCount);
				}
				
				$dropdown.dropdown('hide');

				noty(
				{
					text: "<b>Rejected!</b>",
					type: 'success',
					timeout: 1500
				});

				$application.slideUp(function() 
				{
					$application.remove();
				});
			}
			else if (response.status != <?= STATUS_VALIDATION_ERROR ?>)
			{
				ajaxFailNoty();
			}
		}).fail(function() 
		{
			ajaxFailNoty();
			$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
		}).always(function() 
		{
			$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
		});	
		return false;
	});

	$('.review-conts input[name=star]').rating();

	$('input[type=radio].star').rating();

	if (!window.handheld)
	{
		$('#proposal-currency').chosen({
			no_results_text: "Sorry! We don't currently support"			// Plugin appends %query% to this string
		});
	}

	<? if ($request['status'] == REQUEST_STATUS_OPEN): ?>
	var $contactButton = $('#user-contact-button');

		<? if ($this->session->userdata('account_profile_made')): ?>
			$contactButton.click(showContactForm);

			$('#<?= $contact_tutor_form["id"] ?>').submit(function() 
			{
				var $form = $(this),
					$contact = $('#user-contact'),
					$overlay = $contact.find('.ajax-overlays')
					
					$hourlyRate = $form.find('[name=hourly-rate]'),
					hourlyRate = $hourlyRate.val(),
					$currency = $form.find('[name=currency]'),
					currency = $currency.val(),
					$message = $form.find('[name=message]'),
					message = nl2br($message.val()),
					
				$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

				$form.find(':input').prop('disabled', true);

				$.ajax({
					url: '<?= base_url("requests/apply") ?>',
					type: "POST",
					data: {
						'request-id': <?= $request['id'] ?>,
						'hourly-rate': hourlyRate,
						'currency': currency,
						'message': message,
						as_f: $form.find('[name=as_f]').val(),
						as_e: $form.find('[name=as_e]').val(),
						as_h: $form.find('[name=as_h]').val()
					},	
					dataType: 'json'
				}).done(function(response) 
				{
					$form.validate(response.errors);

					if (response.success == true)
					{								
						location.reload();
					}
					else
					{
						$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
					}
				}).fail(function() 
				{
					ajaxFailNoty();
					$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
				}).always(function() 
				{
					$form.find(':input').prop('disabled', false);
				});

				return false;
			});

			$('#delete-application-link').click(function()
			{
				if (confirm('Are you sure you want to delete your application?'))
				{
					var $overlay = $(this).parents('.pre-send').children('.ajax-overlays');

					$.ajax(
					{
						url: '<?= base_url("requests/delete_application") ?>',
						type: "POST",
						data: {
							'request-id': <?= $request['id'] ?>
						},
						dataType: 'json'
					}).done(function(response) 
					{
						if (response.success == true)
						{								
							location.reload();
						}
						else
						{
							$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
						}
					}).fail(function() 
					{
						ajaxFailNoty();
						$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
					}).always(function() 
					{
					});
				}
			})
		<? elseif ($logged_in): ?>
			$contactButton.click(showNeedProfileMessage);
		<? endif; ?>



	<? endif; ?>

	// Only go ahead if GMaps has loaded
	if (typeof google === 'object' && typeof google.maps === 'object')
	{
		var mapOptions = 
		{
			center: new google.maps.LatLng(<?= $request['location_lat'].','.$request['location_lon'] ?>),
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			zoom: 14,
			panControl: false,
			zoomControl: true,
			mapTypeControl: false,
			scaleControl: false,
			streetViewControl: false,
			overviewMapControl: false,

			keyboardShortcuts: false
		},	
			map = new google.maps.Map(document.getElementById("map"), mapOptions);

		marker = new google.maps.Marker(
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

function showNeedProfileMessage()
{
	<?
	$noty = '<b>Please finish '.anchor('account/profile','making your profile').' before applying to the request.</b>';
	?>
	noty(
	{
		text: <?= json_encode($noty) ?>,
		type: 'warning',
		timeout: 7000
	});	
}

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

			$form.find(':input').first().focus();					
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
</script>