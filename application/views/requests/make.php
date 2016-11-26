
<?

if ($is_modal)
{
	$additional_classes = 'reveal-modal ';
	$id = 'request-modal';
	$close_sign = '<a class="close-reveal-modal">&#215;</a>';
}
else
{
	$additional_classes = 'on_page';
	$id = 'request-onpage';
	$close_sign = '';
}

$show_optionals_text = 'Show optional fields';
$hide_optionals_text = 'Hide optional fields';


$request_id = '';

$subjects = array(
	'name'			=> 'subjects',
	'id'			=> $id.'-subject',
	'value'			=> isset($extra_make_tutor_request) ? $readable_subject : '',
	'placeholder' 	=> ''
);

// Have to do this setting because this view is loaded before the page when we have onpage versions of the request on the distance find
$prefs = $this->session->userdata('prefs');
$readable_location = ($prefs ? $prefs['readable-location'] : '');

if (!$readable_location)
{
	$search_location = $this->session->userdata('search-location');
	$readable_location = ($search_location ? $search_location['readable'] : '');
}

$location = array(
	'name'			=> 'location',
	'id'			=> $id.'-location',
	'placeholder'	=> "e.g. 'Toronto, Canada' or 'Ryerson University'",
	'value'			=> (isset($readable_location) ? $readable_location : ''),
	'class'			=> 'no-enter-submit request-locations',
);

$details = array(
	'name'			=> 'details',
	'id'			=> $id.'-details',
	'placeholder'	=> "",
	'class'			=> "",
);

$max_price = array(
	'name'	=> 'max-price',
	'id' => $id.'-max-price',
	'placeholder' => '0.00',
	'class' => 'hourly-rates'
);

$type_title = "Pick 'Local' if you want a tutor near you, 'Distance' if you want a Skype/email tutor from anywhere in the world, or 'Both' if you're okay with either one";

$currency_name = 'currency';
$active_currency = 'CAD';	// Default

if (isset($request) && $request['user_id'] == $this->session->userdata('user_id'))
{
	$edit_values = array(
		'request-id' => $request['id'],
		'details' => (isset($request['details']) ? $request['details'] : ''),
		'max-price' => ($request['price'] == 0 ? '' : $request['price']),
		'location' => $request['location_name'],
		'country' => $request['location_country'],
		'city' => $request['location_city'],
		'lat' => $request['location_lat'],
		'lon' => $request['location_lon'],
		'subjects' => $request['subjects_string'],
		'currency' => $request['currency']
	);

	$type_checked = array(
		'local' => NULL,
		'distance' => NULL,
		'both' => NULL,
		'eq' => 0
	);

	if ($request['type'] == REQUEST_TYPE_LOCAL)
	{
		$type_checked['local'] = 'checked="checked"';
		$type_checked['eq'] = 0;
	}
	elseif ($request['type'] == REQUEST_TYPE_DISTANCE)
	{
		$type_checked['distance'] = 'checked="checked"';
		$type_checked['eq'] = 1;
	}
	else
	{
		$type_checked['both'] = 'checked="checked"';
		$type_checked['eq'] = 2;
	}

	$optionals_status = 'active';
	$optionals_state = 'display: block;';
	$optionals_title = $hide_optionals_text;
}
else
{
	$edit_values = NULL;

	$type_checked = array(
		'local' => 'checked="checked"',
		'distance' => NULL,
		'both' => NULL,
		'eq' => 0
	);

	if (isset($extra_make_tutor_request) && $groups == 'tutors' && !isset($readable_location))
	{
		$type_checked = array(
			'local' => NULL,
			'distance' => 'checked="checked"',
			'both' => NULL,
			'eq' => 1
		);
	}

	$optionals_status = '';
	$optionals_state = '';
	$optionals_title = $show_optionals_text;
}

// var_dump($type_checked);

?>

<section id="<?= $id ?>" class="cf request-form-secs <?= $additional_classes ?>" data-reveal>
<!--
	<? if (isset($extra_make_tutor_request)): ?>
		<div class="boxes" id="no-tutors-found-box">
			<? if ($groups == 'tutors'): ?>
				<? if (isset($readable_location)): ?>
					No <span class="find-editables"><?= $readable_subject ?></span> tutors found within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>.
				<? else: ?>
					<span>Sorry, no tutors teach <span class="find-editables"><?= $readable_subject ?> online</span>...yet!</span>
				<? endif; ?>
			<? elseif ($groups == 'subjects'): ?>
				<? if ($search_domain == 'local'): ?>
					No subjects taught within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>...yet!</span>
				<? else: ?>
					No subjects taught online...yet!
				<? endif; ?>
			<? endif; ?>
			<br>
			<b>Make a free Tutor Request and we'll find tutors for you.</b>
		</div>
	<? endif; ?>
-->
	<section class="popup cf">
		<div class="pre-send">
			<form class="ased" method="post">
				<header>
					<h2 class="request-make-titles">Make a Tutor Request</h2>
				    <?= $close_sign ?>
				</header>
				<div class="ajax-overlays">
					<div class="ajax-overlays-bg"></div>
					<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
				</div>
				
				<div class="popup-body edit-request-forms">
					<div class="request-fields">
						<input type="hidden" name="request-id" value="">
						<div class="form-elements">
							<?= form_label('Subject', $subjects['id'], array('class' => 'line-labels')) ?>
							<div class="form-inputs line-inputs">
						        <input type="text" id="<?= $subjects['id'] ?>" name="<?= $subjects['name'] ?>" value="<?= $subjects['value'] ?>" placeholder="<?= $subjects['placeholder'] ?>" />
						        <div class="form-input-notes error-messages"></div>
						    </div>
						</div>
						<div class="form-elements location-elements">
							<?= form_label('Location', 'request-location', array('class' => 'line-labels')); ?>
							<div class="form-inputs line-inputs">
								<?= form_input($location) ?>
								<input type="hidden" name="lat">
								<input type="hidden" name="lon">
								<input type="hidden" name="city">
								<input type="hidden" name="country">

								<div class="form-input-notes error-messages" data-input-name="location"></div>
								<div class="form-input-notes error-messages" data-input-name="lat"></div>
								<div class="form-input-notes error-messages" data-input-name="lon"></div>
								<div class="form-input-notes error-messages" data-input-name="city"></div>
								<div class="form-input-notes error-messages" data-input-name="country"></div>
								<div class="form-input-notes error-messages google-problem-messages">Sorry, we can't connect to Google right now to check your location. <a href="<?= current_url() ?>">Please refresh the page.</a></div>
							</div>
						</div>
						
						<div class="form-section-header form-elements <?= $optionals_status ?>">
							<div class="same-page-links-with-arrows optional-button">
								<span class="same-page-links" title="<?=$show_optionals_text ?>">Optionals</span> 
								<span class="click-arrows"></span>
							</div>
						</div>
						<div class="form-optional section" style="<?= $optionals_state ?>">

							<div class="form-elements">
								<div class="form-inputs block-inputs no-top-margin-inputs">
									<?= form_label('Type <span class="debold">(<span class="same-page-links no-pointer" title="'.$type_title.'">?</span>)</span>', NULL, array('class' => 'line-labels request-type-labels')) ?>	
									<div class="radio-options-conts-conts">					
										<label class="radio-option-conts inline-block first">
											<input type="radio" name="type" value="<?= REQUEST_TYPE_LOCAL ?>" <?= $type_checked['local'] ?> /> Local
										</label>
										<label class="radio-option-conts inline-block">
											<input type="radio" name="type" value="<?= REQUEST_TYPE_DISTANCE ?>" <?= $type_checked['distance'] ?> /> Distance
										</label>
										<label class="radio-option-conts inline-block">
											<input type="radio" name="type" value="<?= REQUEST_TYPE_BOTH ?>" <?= $type_checked['both'] ?> /> Both
										</label>
									</div>
									<div class="form-input-notes radio-notes error-messages"></div>
								</div>
							</div>

							<div class="form-elements">
								<?= form_label('Details <span class="debold">(<span class="same-page-links no-pointer" title="Where you\'d like to meet, what specifically you need help with, and any other things you\'d like to mention to tutors that apply">?</span>)</span>', $id.'-details', array('class' => 'line-labels')); ?>
								<div class="form-inputs line-inputs">
									<?= form_textarea($details) ?>
									<div class="form-input-notes error-messages"></div>
								</div>
							</div>

							<div class="form-elements">
								<?= form_label('Max Price', $id.'-max-price', array('class' => 'line-labels')); ?>
								<div class="form-inputs line-inputs hourly-price-line-inputs">
									<?= form_input($max_price); ?> per hour in <?= form_dropdown($currency_name, $currencies_for_selects, $active_currency, 'id="'.$id.'-currency" class="currency-selects request-currencies"') ?>
									<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $max_price['name'] ?>"></div>
									<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $currency_name ?>"></div>
								</div>
							</div>
						</div>
					</div>

					<div class="required-text tiny-aftertext"><span class="required-markers">*</span> Please fill in all blanks.</div>

					<div class="submit-conts">
						<input type="submit" value="Proceed" class="buttons color-3-buttons large-submit-on-mobile">
<!--
						<? if ($is_modal) { ?><input type="button" id="<?= $id ?>-cancel" class="buttons cancel-buttons" value="Cancel"> <? } ?>
						<a href="javascript:void(0);" class="remove-item-links danger-page-links">delete this request</a>
-->
					</div>
				</div>
			</form>
		</div>

	</section>
</section>

<script>

<? if ($is_modal): ?>

var requestEditValues = <?= json_encode($edit_values) ?>,
	typeCheckedEq = <?= $type_checked['eq'] ?>,
	requestType = 'make';

function toggleRequestEdit()
{
	var $form = $('form', '.request-form-secs');

	if (requestType != 'edit')
	{
		$form.find('.request-make-titles').text('Edit your Request');
		$form.find('[type=submit]').val('Update');

		$.each(requestEditValues, function(name, val)
		{
			$form.find('[name='+name+']').val(val).hideErrors();
		})

		$form.find('[name=subjects]').trigger("change").end()
			 .find('[name=currency]').trigger("liszt:updated").end()
			 .find('[name=type]').eq(typeCheckedEq).prop("checked", true);

		requestType = 'edit';
	}
}

function toggleRequestMake()
{
	var $form = $('form', '.request-form-secs');

	if (requestType != 'make')
	{
		$form.find('.request-make-titles').text('Make a Tutor Request');

		$form.find('[type=submit]').val('Proceed');
		$form.find('input, select, textarea').not('[type=button], [type=submit], [type=radio], [type=checkbox], .as-inputs').val('').hideErrors();
		$form.find('[name=subjects]').trigger("change").end()
			 .find('[name=currency]').val('CAD').trigger("liszt:updated");
		
		$form.find('[name=type]').eq(0).prop("checked", true);

//		console.log($form.find('[name=type]').eq(0).length)

		requestType = 'make';
	}
}

$(function()
{
	/*
	$('#request-onpage-currency').click(function()
	{
		console.log('click')
	}).change(function()
	{
		console.log('change');
	});
*/
	$('option').click(function()
	{
		console.log('df');
	});

	var locationHasClientError = false,
		secondGeocode = false,
		$sec = $('.request-form-secs'),
		$form = $sec.find('form');

	$('.optional-button').click(function()
	{
		var $this = $(this),
			$buttonCont = $this.parent(),
			$optional = $buttonCont.siblings('.form-optional'),
			effectSpeed = 200;

		if ($buttonCont.hasClass('active'))
		{
			$buttonCont.removeClass('active', effectSpeed);
			$this.attr('title', '<?= $show_optionals_text ?>')
				 .find('.click-arrows').removeClass('up').addClass('down');

			$optional.stop(true, true).slideUp(effectSpeed);
		}
		else
		{
			$buttonCont.addClass('active', effectSpeed);
			$this.attr('title', '<?= $hide_optionals_text ?>')
				 .find('.click-arrows').removeClass('down').addClass('up');

			$optional.stop(true, true).slideDown(effectSpeed);
		}
	});

	$form.submit(function()
	{
		var $curForm = $(this),
			$location = $curForm.find('[name=location]'),
			$overlay = $curForm.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		if (locationHasClientError)
		{
			$location.focus().showErrors("Please don't leave Location blank.", false);
			$overlay.hide();

			return false;
		}

		// If hidden values are empty, then we've found the readable location, but need to geocode it. This is the case for IP geocoding
		if ($location.next('[name=lat]').val() == '' && !secondGeocode)
		{
			secondGeocode = true;

			performPlaceChange($curForm.parents('.request-form-secs').attr('id'), function()
			{
				$curForm.submit();				
			});
		
			return false;
		}

		var data = $curForm.serialize();
		console.log(data);

		$curForm.find(':input').prop('disabled', true);

		$.ajax(
		{
			type: "POST",
			url: baseUrl("requests/"+requestType),
			data: data,
			dataType: 'json'
		}).done(function(response)
		{
			// console.log(response);

			$curForm.validate(response.errors);

			if (response.success == true)
			{
				if (response.data.needsAuth)
				{
					var $signupModal = $('#signup-student-modal'),
						$thisSec = $curForm.parents('.request-form-secs');

					$signupModal.find('.needs-request').show();
					
					if ($thisSec.attr('id') == 'request-onpage')
					{
						$signupModal.find('.pre-popups').hide();
					}
					else
					{
						// This is not working on request make modal from request make page
						$signupModal.find('.pre-popups').show();
					}
					
					$overlay.hide();
					$signupModal.foundation('reveal', 'open');
				}
				else
				{
					window.location = "<?= base_url('requests') ?>/"+response.data.requestId;
				}
			}
			else
			{
				$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);
			}
		}).always(function() 
		{
			$curForm.find(':input').prop('disabled', false);
			$overlay.hide();
		}).fail(function() 
		{
			ajaxFailNoty();
//			$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);
		});
		return false;
	});

	if (!window.handheld)
	{
/*		$form.find('[name=currency]').chosen({
			no_results_text: "Sorry! We don't currently support"			// Plugin appends %query% to this string
		});
*/
	}

	$form.find('[name=subjects]').autocomplete(
	{
		source: allSubjects
	});
/*
	$form.find('[name=subjects]').select2({
		allowClear: true,
		tags: <?= json_encode($all_subjects) ?>,
		tokenSeparators: [","],
		openOnEnter: false,
		maximumSelectionSize: 1,
		formatSelectionTooBig: function (limit) { return "Sorry, only 1 subject per request."; }
    });
*/
	// This only occurs in the modal, so ids are okay
	$('#request-modal-cancel').click(function()
	{
	    $('#request-modal').foundation('reveal', 'close');
	});


	// Only go ahead if GMaps has loaded
	if (typeof google === 'object' && typeof google.maps === 'object')
	{
		$sec.each(function()
		{
			setupRequestAutocomplete($(this).attr('id'));
		}); 
	}
	else
	{
		$('.google-problem-messages').show();
		// Eventually add error to popup 
	}

	function setupRequestAutocomplete(secId)
	{
//		log(secId);
		var $sec = $('#'+secId); 
		var $location = $sec.find('[name=location]');
		var locationDOM = $location.get(0);		// Can't get value directly because otherwise it woud just save the 1st (blank) location
		var requestAutocomplete = new google.maps.places.Autocomplete(locationDOM);

		$location.blur(function()
		{
            google.maps.event.trigger(requestAutocomplete, 'place_changed');
		})

		// Update Google Map with every change
		google.maps.event.addListener(requestAutocomplete, 'place_changed', function()
		{
			handleNewRequestLocation(locationDOM.value, secId);
		});
	}

	function performPlaceChange(secId)
	{
		var $location = $sec.find('[name=location]');
		var locationDOM = $location.get(0);

//		console.log(locationDOM.value);
//		console.log(secId);

		handleNewRequestLocation(locationDOM.value, secId, function()
		{
			$form.submit();
		});
	}

	function handleNewRequestLocation(address, secId, callback)
	{		
		var $sec = $('#'+secId);
		var enclosers = {
					start: '<div class="error-messages">',
					end: '</div>'
				},
			error = '';

		geocoder.geocode({'address': address}, function(results, status) 
		{
			if (status == google.maps.GeocoderStatus.OK) 
			{
				var loc = parseLocation(results[0].address_components),
					coords = results[0].geometry.location;

//				console.log(results[0].address_components);

				loc['lat'] = coords.lat();
				loc['lon'] = coords.lng();
								
				if (!loc['lat'] || !loc['lon'])
				{
					error += enclosers.start + 'Sorry, there was a problem.<br>Please check for a typo or try different location.' + enclosers.end;
				}
				else if (!(loc['country'] && loc['city']))
				{
					error += enclosers.start + 'Sorry, please include a more specific place (e.g. postal code, street name, nearby establishment).' + enclosers.end;
				}				
			}
			else 	// address is invalid
			{
				if (!address)
				{
//					error += enclosers.start + "Please don't leave Location blank." + enclosers.end;
				}
				else
				{
					error += enclosers.start + 'Sorry, there was a problem.<br>Please check for a typo or try different location.' + enclosers.end;				
				}
			}

//			log(secId, error, loc, $sec.find('[name=location]').length);

			if (!error 
				&& loc 
				&& typeof loc['city'] !== undefined
				&& typeof loc['country'] !== undefined)
			{
				recordCoordinates(loc, secId);
				locationHasClientError = false;
			}
			else
			{
				locationHasClientError = true;
			}

			if (typeof callback !== "undefined")
			{
				callback();
			}

			$sec.find('[name=location]').showErrors(error, false);
		});
	}

	function recordCoordinates(loc, secId)
	{
		var $sec = $('#'+secId);

		$sec.find('[name=lat]').val(loc['lat']);
		$sec.find('[name=lon]').val(loc['lon']);
		$sec.find('[name=city]').val(loc['city']);
		$sec.find('[name=country]').val(loc['country']);
	}
});

<? endif; ?>
</script>