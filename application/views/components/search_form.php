<?
	
$group_attr = 'id="search-group" class="form-inputs search-inputs chosen-selects" tabindex="200"';
$group_options = array(
	'Local' => array(
		'local-tutors' => 'Tutors',
		'local-requests' => 'Requests',
		'local-subjects' => 'Subjects',
	),
	'Distance' => array(
		'distance-tutors' => 'Tutors',
		'distance-requests' => 'Requests',
		'distance-subjects' => 'Subjects'
	)
);

$subject_attr = 'id="search-subject" class="form-inputs search-inputs chosen-selects" data-placeholder="Type/select a subject" tabindex="300"';

$subject_name = $this->session->userdata('search-subject');

$subject_name = ($subject_name ? $subject_name['name'] : '');

$subject_as_input = array(
	'name'			=> 'search-subject',
	'id'			=> 'search-subject',
	'placeholder'	=> 'Any Subject',
	'class'			=> 'form-inputs',
	'value'			=> $subject_name,
//	'value'			=> htmlspecialchars_decode($subject_name),
//	'value'			=> htmlentities('(dogs)'),
//	'value'			=> html_entity_decode($subject_name, ENT_NOQUOTES, 'UTF-8'),
	'tabindex'		=> 300
);

$location = array(
	'name'			=> 'search-location',
	'id'			=> 'search-location',
	'placeholder'	=> 'Any Location',
	'class'			=> 'form-inputs',
	'value'			=> $readable_location,
	'tabindex'		=> 400
);

$submit = array(
	'class'			=> 'buttons color-3-buttons search-button dark-background-buttons',
	'value'			=> 'Search',
	'tabindex'		=> 500 
);

$hidden_fields = array(
	'lat' => '',
	'lon' => '',
	'loc-from' => '',
	'geocoder-status' => '',
);

?>
	
<?= form_open('find', array('id' => 'search-form')) ?>

	<div class="ajax-overlays">
		<div class="ajax-overlays-bg"></div>
		<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
	</div>

	<?= form_hidden($hidden_fields) ?>

	<div class="form-elements inline-block">
		<?= form_dropdown('search-group', $group_options, $current_search_domain.'-'.$current_search_group, $group_attr) ?>
	</div><div class="form-elements inline-block" id="search-subject-element" style="<? if ($current_search_group == 'subjects') { echo 'display: none;'; } ?>">
		<span id='for'>for</span>
		<div class="form-inputs line-inputs">
			<?= form_input($subject_as_input) ?>
		</div>
		<?// form_dropdown('search-subject-id', $subject_options, $current_subject_id, $subject_attr) ?>
	</div><div class="inline-block">
		<div class="form-elements inline-block" id="search-location-element" style="<? if ($current_search_domain == 'distance') { echo 'display: none;'; } ?>">
			<span id='in'>in</span>
			<div class="form-inputs line-inputs">
				<?= form_input($location) ?>
				<div class="form-input-notes error-messages search-form-errors"></div>
			</div>
		</div><div class="form-elements inline-block" id="button-loader-cont">
			<?= form_submit($submit); ?>
			<img src="<?= base_url('assets/images/'.LOADER_DARK) ?>" id="search-loader"> 
		</div>
	</div>

<?= form_close() ?>

<div id="hide-search-sec">
	<span class="hide-show-search-buttons">Hide Find Form &#x25B4;</span>
</div>
<div id="show-search-sec">
	<span class="hide-show-search-buttons buttons dark-background-buttons">Show Find Form &#x25BE;</span>
</div>

<script>

var savedLocation = <?= json_encode($readable_location) ?>;

function hideSearchForm()
{
	$('#search-form').removeClass('show-on-small-view').slideUp(<?= FAST_FADE_SPEED ?>);
	$('#hide-search-sec').hide();
	$('#show-search-sec').slideDown(<?= FAST_FADE_SPEED ?>);
}

function showSearchForm()
{
	$('#search-form').addClass('show-on-small-view').slideDown(<?= FAST_FADE_SPEED ?>);
	$('#show-search-sec').hide();
	$('#hide-search-sec').slideDown(<?= FAST_FADE_SPEED ?>);
}

$(function()
{
	$('#hide-search-sec .hide-show-search-buttons').click(hideSearchForm);
	$('#show-search-sec .hide-show-search-buttons').click(showSearchForm);
});

$(function() 
{
	var $form = $('#search-form'),
		$locFrom = $form.find('[name=loc-from]'),
		address,
		geocoding = 
		{
			active: false,
			done: false,
			needed: false
		},
		locationLookup = 
		{
			active: false,
			found: false,
			needed: true
		},
		$searchLocation = $form.find('[name=search-location]'),
		$searchGroup = $('#search-group'),
		searchGroup = $searchGroup.find('option:selected').val(),
		$subjectElement = $('#search-subject-element'),
		$locationElementAndIn = $('#search-location-element'),
		minGroupWidth = 100,
		groupPadding = 15,
		$searchHideShow = $('#hide-search-sec, #show-search-sec');

	enableSearchBar();
	resizeSearchBar();

	$(window).resize(resizeSearchBar);

	$('#search-group').autosize_select();

	$searchLocation.keydown(function (e)
	{
		// Detect character typed
		if($.inArray(e.keyCode,[13,16,17,18,19,20,27,35,36,37,38,39,40,91,93,224]) === -1)
		{
//				log('changed');
			geocoding.needed = true;
			locationLookup.needed = true;

		}
	}).autocomplete(
	{
		minLength: 2,
		source: function(request, response) 
		{
			$.getJSON( "<?= base_url('data/locations') ?>", request, function( data, status, xhr ) 
			{
				response(data);
			});
		},
		select: function(event, ui)
		{
			geocoding.needed = false;
		}
	});

	$('#search-subject').autocomplete(
	{
		source: allSubjects
	});
			
	$form.submit(function() 
	{
		var enclosers = {
					start: '<div class="error-messages">',
					end: '</div>'
				},
			error = '',
			$allSearchLoaders = $form.find('.ajax-overlays, #search-loader'),
			$searchLoader;

		if (window.vpWidth > <?= SCREEN_SUB_REGULAR ?>)
			$searchLoader = $form.find('#search-loader')
		else
			$searchLoader = $form.find('.ajax-overlays');
			
		searchGroup = $searchGroup.find('option:selected').val();

		if ($searchLocation.val() == '' && searchGroup.indexOf('local') != -1)
		{
			error += enclosers.start + "Please enter a location" + enclosers.end;
			$searchLocation.focus();
		}
		else
		{
			error = '';
		}
		
		$searchLocation.showErrors(error);
		
		if (error)
		{
			return false;
		}

		if (!geocoding.needed)
		{
			// == doesn't work because if the first entry was 'toRonto' and the second entry was 'Toronto', it would geocode it again
			if (strcasecmp(savedLocation, $searchLocation.val()) === 0)		// use session data
			{
				$locFrom.val(<?= LOC_FROM_SESSION ?>);
				return true;
			}
			else	// use DB data
			{
				$locFrom.val(<?= LOC_FROM_DB ?>);
				return true;
			}
		}

		address = $searchLocation.val();

		// If not needed and found, then we're done here
		if (locationLookup.found)
		{
			$locFrom.val(<?= LOC_FROM_DB ?>);
			return true;
		}

		if (locationLookup.active)
			return false;

		// If here, then location not selected from autocomplete. First check if in DB. Then, if not, geocode
		if (locationLookup.needed)
		{
			locationLookup.needed = locationLookup.found = false;
			locationLookup.active = true;

//				$searchLoader.fadeIn(<?= FAST_FADE_SPEED ?>);
			$searchLoader.show();
			$.ajax(
			{
				type: "POST",
				url: "<?= base_url('find/location_exists_in_db') ?>",
				data: {
					location: address
				}
			}).done(function(response) 
			{
				locationLookup.needed = locationLookup.active = false;

				if (response == true)
				{
					locationLookup.found = true;
				}

				$form.find('[name=lat]').val('').end()
					 .find('[name=lon]').val('').end()
					 .submit();

			}).always(function() 
			{
			}).fail(function() 
			{

			});

			return false;
		}
		
		// If here, then we have to geocode location (or it has just been geocoded and form has been resubmitted)
		// Note, we need .done and .active. .done indicates that we need to geocode and .active prevents people from submitting multiple times before geocoding sets .done to true

		if (geocoding.done)
		{
			$locFrom.val(<?= LOC_FROM_GEOCODE ?>);
			return true;
		}

		if (geocoding.active)
			return false;


		geocoding.active = true;
		//$searchLoader.fadeIn(<?= FAST_FADE_SPEED ?>);
		$searchLoader.show();

		geocoder.geocode({ 'address': address }, function(results, status) 
		{	
			$allSearchLoaders.fadeOut(<?= FAST_FADE_SPEED ?>);

			geocoding.active = false;
			geocoding.done = true;

			if (status == google.maps.GeocoderStatus.OK) 
			{
				var loc = results[0].geometry.location,
					lat = loc.lat(),
					lon = loc.lng();
					
				$form.find('[name=lat]').val(lat).end()
					 .find('[name=lon]').val(lon);
			}
			else
			{
				$form.find('[name=lat]').val('').end()
					 .find('[name=lon]').val('');

				if (status == 'OVER_QUERY_LIMIT')
					status = 'OVER_QUERY_ON_CLIENT';
			}
//				status = 'OVER_QUERY_ON_CLIENT';

			$form.find('[name=geocoder-status]').val(status).end()
				 .submit();

		});			

		return false;
	});

	function resizeSearchBar()
	{
		var $searchGroup = $('#search-group');

		if (window.vpWidth > <?= SCREEN_SMALL_REGULAR ?>)
		{
			$searchHideShow.hide();
			$form.show();
		}

		if (window.vpWidth > <?= SCREEN_SMALL ?> && !handheld)
		{
			$searchGroup.removeClass('group-prefix-added');
			$searchGroup.find('option').each(function()
			{
				var $this = $(this),
					optionText = $this.text().replace('Local ', '').replace('Distance ', '');
				$this.text(optionText);
			});
		}
		else
		{
			if (!$searchGroup.hasClass('group-prefix-added'))
			{
				$searchGroup.addClass('group-prefix-added');
				$searchGroup.find('option').slice(0, 3).each(function()
				{
					var $this = $(this),
						optionText = 'Local ' + $this.text();
					$this.text(optionText);
				});
				$searchGroup.find('option').slice(3, 6).each(function()
				{
					var $this = $(this),
						optionText = 'Distance ' + $this.text();
					$this.text(optionText);
				});
			}
		}

		if (window.vpWidth <= <?= SCREEN_SMALL_REGULAR ?>)
		{
			if ($form.hasClass('show-on-small-view'))
			{
				$form.show();
				$searchHideShow.filter('#hide-search-sec').show();
			}
			else
			{
				$form.hide();
				$searchHideShow.filter('#show-search-sec').show();
			}
		}

		updateGroupChosenDisplay();
	}

	function enableSearchBar()
	{
		if (!handheld)
		{
/*
			$('#search-subject').chosen({
				no_results_text: "Sorry! No tutors currently teach"			// Plugin appends %query% to this string
			});
*/
			$('#search-group').chosen(
			{
				disable_search: true,
			}).on('change liszt:updated liszt:hiding_dropdown liszt:ready', function(evt, params)
			{		
				updateGroupChosenDisplay();
			});

			// This is a hack. Can't be done immediately, but can't find event this should follow. Must be a better way to do this.
			setTimeout(function()
			{
				$('#search_group_chzn input').attr('disabled', 'disabled');		
			}, 1000);

		}
		else
		{
			$('#search-group').change(function()
			{
				updateGroupChosenDisplay();
			});
		}
	}

	function updateGroupChosenDisplay()
	{
//		log('here');

		var $chzn = $('#search_group_chzn'),
			$active = $chzn.find('.chzn-single'),
			activeText = $active.text().replace('Local ', '').replace('Distance ', ''),
			selected = $('#search-group').find('option:selected').val(),
			$chznResults = $chzn.find('.group-option'),
			newWidth;

//		log(selected);

		if (selected.indexOf('distance') != -1)
		{
			$locationElementAndIn.hide();
			$active.find('span').text('Distance ' + activeText);
		}
		if (selected.indexOf('local') != -1)
		{
			$locationElementAndIn.show().css('display', 'inline-block');
			$active.find('span').text('Local ' + activeText);
		}
		if (selected.indexOf('subjects') != -1)
		{
			$subjectElement.hide();
			$('.search-button').val('Find').addClass('show-buttons');
		}
		else
		{
			$subjectElement.show();
			$('.search-button').val('Search').removeClass('show-buttons');
		}

		newWidth = Math.max(minGroupWidth, $active.textWidth() + groupPadding);

		$chzn.width(newWidth);
		$chzn.find('.chzn-drop').width(newWidth-2);			

		// These have to be done through JavaScript, or through changing the Chosen source. Eventually change the source to just take the title attribute of an option
		// Local
		$chznResults.eq(0).attr("title", "Find local tutors");
		$chznResults.eq(1).attr("title", "Find local requests for tutors");
		$chznResults.eq(2).attr("title", "See all subjects being taught in a given location");
		// Distance
		$chznResults.eq(3).attr("title", "Find tutors that teach online (i.e. distance education)");
		$chznResults.eq(4).attr("title", "Find requests for distance tutors (i.e. distance education)");
		$chznResults.eq(5).attr("title", "See all subjects being taught online (i.e. distance education)");
	}
});

<? if (!$readable_location): ?>
$(function()
{
	set_location_from_ip(function(loc)
	{
		window.loc = loc;
		// Requests
		var $requestForm = $('.edit-request-forms');
		$requestForm.find('[name=location]').val(loc.readable);
//					.next('[name=lat]').val(loc.lat)
//					.next('[name=lon]').val(loc.lon)
//					.next('[name=city]').val(loc.city)
//					.next('[name=country]').val(loc.country);	

		// Main site search
		$("#<?= $location['id'] ?>").val(loc.readable);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('find/set_location') ?>",
			data: {
				loc: loc
			}
		}).done(function(response) 
		{
			console.log(response);
		}).always(function() 
		{
		}).fail(function() 
		{

		});
	});
});

// Uses IP to get location
function set_location_from_ip(callback)
{
	var loc,
		readable;
	
	// If a location is already set (from session)

	if (savedLocation)
		return;


	// First try ipinfo
	$.get('http://ipinfo.io', function(response) 
	{
		console.log(response);
		if (response 
			&& response.country 
			&& response.city 
			&& response.region
			&& response.loc)
		{
			// Build location string
			readable = response.city;
			if (response.country === 'CA' || response.country === 'US')
				readable += ', ' + response.region;		// Only display province/state for US and Canada
			readable += ', ' + response.country;

			// Get coords. ipinfo returns them as one string, so have to split them
			coords = response.loc.split(',');

			loc = {
				'lat' : coords[0],
				'lon' : coords[1],
				'readable' : readable,
				'city' : response.city,
				'country' : response.country
			};

			callback(loc);
		}
		// If we're getting a full or partial null response, try using geoplugin.net
		else
		{
			set_location_from_geoplugin(callback);
		}
	}, 'jsonp').fail(function() 
	{
		set_location_from_geoplugin(callback);
	});
}

function set_location_from_geoplugin(callback)
{
	$.getJSON("http://www.geoplugin.net/json.gp?jsoncallback=?",
	function (response) 
	{
		console.log(response);
		if (response 
			&& response.geoplugin_countryName 
			&& response.geoplugin_city 
			&& response.geoplugin_region
			&& response.geoplugin_latitude
			&& response.geoplugin_longitude)
		{
			// Build location string
			readable = response.geoplugin_city;
			if (response.geoplugin_countryCode === 'CA' || response.geoplugin_countryCode === 'US')
				readable += ', ' + response.geoplugin_region;		// Only display province/state for US and Canada
			readable += ', ' + response.geoplugin_countryName;

			loc = {
				'lat' : response.geoplugin_latitude,
				'lon' : response.geoplugin_longitude,
				'readable' : readable,
				'city' : response.geoplugin_city,
				'country' : response.geoplugin_countryName
			};

			callback(loc);
		}
		// If null or partial response, bail!
		else
		{
			return;
		}
	});
}


<? endif; ?>
</script>