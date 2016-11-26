<?
	$sort_price_text = ($groups == 'tutors' ? 'Lowest Priced' : 'Highest Paying');

	$marked_up_subject = '<span class="find-bar-current-subject" title="Change the subject using the form above">'.$readable_subject.'</span>';
?>

<section class="find-bars containers cf">

	<div class="sort-by-cont">

		<span class="search-types-cont sort-by-description-text">
		<? if ($groups == 'requests'): ?>
			<span class="search-types active">Requests</span> | <?= anchor("find/local/tutors/$location_query/$subject_query", "Tutors", 'class="search-types" title="See '.($readable_subject ? $readable_subject.' ' : '').'tutors within '.$readable_distance.' of '.$readable_location.'"') ?>
		<? else: ?>
			<?= anchor("find/local/requests/$location_query/$subject_query", "Requests", 'class="search-types" title="See '.($readable_subject ? $readable_subject.' ' : '').'requests within '.$readable_distance.' of '.$readable_location.'"') ?> | <span class="search-types active">Tutors</span>
		<? endif; ?>
		</span>

		<span class="sort-by-description-text">
			<? if ($num_of_items == 0): ?>
				No <?= $marked_up_subject ?> <?= $groups ?> found
			<? elseif ($num_of_items == 1): ?>
			 	1 <?= $marked_up_subject ?> <?= $group ?> found
			<? elseif ($num_of_pages > 1): ?>
				<?= $items_low ?>-<?= min($num_of_items, $items_high) ?> of <?= $num_of_items ?> <?= $marked_up_subject ?> <?= $groups ?>
			<? else: ?>
				<?= $num_of_items ?> <?= $marked_up_subject ?> <?= $groups ?> found
			<? endif; ?>			
		
		<? if ($num_of_items): ?> 
		| Showing:
		<a href="javascript:void(0);" data-dropdown="#dropdown-sorts" class="sort-options"><?= $readable_sort ?> <span class="sort-arrows">↓</span></a></span>		
		<div id="dropdown-sorts" class="dropdown dropdown-tip dropdown-relative">
		    <ul class="dropdown-menu">
		        <li><?= anchor(this_url_with_query(array('sort' => 'distance', 'page' => 1)), 'Closest') ?></li>
		        <li><?= anchor(this_url_with_query(array('sort' => 'price', 'page' => 1)), $sort_price_text) ?></li>
		        <? if ($groups == 'tutors'): ?>
		        <li><?= anchor(this_url_with_query(array('sort' => 'rating', 'page' => 1)), 'Highest Rated') ?></li>
			    <? endif; ?>
		        <li><?= anchor(this_url_with_query(array('sort' => 'new', 'page' => 1)), 'Newest') ?></li>
		    </ul>
		</div>
		<? endif; ?> 

		<span class="sort-by-description-text">within <a href="javascript:void(0);" data-dropdown="#dropdown-distances" class="sort-options"><?= $distance ?> <span class="sort-arrows">↓</span></a></span>
		<div id="dropdown-distances" class="dropdown dropdown-tip dropdown-relative">
		    <ul class="dropdown-menu">
		        <li><?= anchor(this_url_with_query(array('distance' => 1, 'page' => 1)), '1') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 5, 'page' => 1)), '5') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 10, 'page' => 1)), '10') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 15, 'page' => 1)), '15') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 20, 'page' => 1)), '20') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 25, 'page' => 1)), '25') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 30, 'page' => 1)), '30') ?></li>
		    </ul>
		</div>
		<span class="sort-by-description-text"><a href="javascript:void(0);" data-dropdown="#dropdown-units" class="sort-options"><?= $readable_units ?> <span class="sort-arrows">↓</span></a></span>
		<div id="dropdown-units" class="dropdown dropdown-tip dropdown-relative">
		    <ul class="dropdown-menu">
		        <li><?= anchor(this_url_with_query(array('units' => 'km', 'page' => 1)), 'km') ?></li>
		        <li><?= anchor(this_url_with_query(array('units' => 'miles', 'page' => 1)), $mile_unit) ?></li>
		    </ul>
		</div>

	</div>

</section>

<?
if ($num_of_items != 0):
?>

<section id="find-results" class="cf containers">

	<div id="find-map">
		<div class="sec-bodies">
			<div id="map"></div>
			<div id="under-map">
				<div class="find-legends">
					<div class="legend-items" id="loc-legend">
						<img alt="Map Marker" class="map-markers" src="http://maps.google.com/mapfiles/ms/icons/blue-dot.png"><span> <?= $readable_location ?></span>
					</div>
					<div class="legend-items" id="tutor-legend">
						<img alt="Map Marker" class="map-markers" src="http://maps.google.com/mapfiles/ms/icons/red.png"><span> <?= ucfirst($groups) ?>' locations</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="results-items-cont">
		<?= $results ?>
	</div>

</section>  <!-- /#find-results -->

<? else: ?>

<section id="text-regular" class="cf pages containers">
	<div class="boxes no-results-found-box" id="no-<?= $groups ?>-found-box">
	<? if ($groups == 'tutors'): ?>
		No <span class="find-editables"><?= $readable_subject ?></span> tutors found within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>.
		<br>
		<b>Make a free Tutor Request and we'll find tutors for you.</b>
	<? else: ?>
		No <span class="find-editables"><?= $readable_subject ?></span> requests found within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>.
		<br>
		<? if (!$logged_in): // not logged in ?>
			<b>
				Want to receive emails when students are looking for tutors?
				<br>
				<a href="<?= base_url('signup/tutor') ?>" data-reveal-id="signup-tutor-modal">Make a free profile!</a>
			</b>
		<? elseif ($role == ROLE_STUDENT): // logged in and a student ?>
			<b>
				Want to receive emails when students are looking for tutors?
				<br>
				<a href="<?= base_url('account/settings') ?>">Upgrade to a tutor account for free!</a>
			</b>
		<? else: // logged in and a tutor ?>
			<b>
				Want to find more students?
				<br>
				<a href="<?= base_url('account/marketing') ?>">Market your profile for free!</a>
			</b>
		<? endif; ?>
	<? endif; ?>
	</div>

	<? if ($groups == 'tutors'): ?>
		<?= $extra_make_request ?>
	<? endif; ?>

</section>  <!-- /#text-regular -->

<? endif; ?>

<script>

function resizeFind() 
{
	var padding = 17, // arbitrary, think it looks nice with this
		findHeight = vpHeight 
					- $('#reg-header').outerHeight() 
					- $('.find-bars').outerHeight()
					- padding,
		minHeight = 140;	// arbitrary

	if (findHeight < minHeight)
	{
		findHeight = 500;		
	}

	$('#find-results').height(findHeight);
//	$(‘#somediv’).css({‘height’: vph + ‘px’});
}


$(function() 
{
	resizeFind();
	$(window).resize(resizeFind);

	if (vpWidth <= <?= SCREEN_SUB_REGULAR ?>)
	{
		$('html, body').animate({
	        scrollTop: $(".find-bars").offset().top
	    }, 2000);
	}

	var $map = $('#find-map'),
		isFirstInfoWindowClick = true;

<?
if ($num_of_items == 0 && $readable_subject):
?>
	var $requestLocation = $('#request-onpage-location');
	if ($requestLocation.val())
		$('#request-onpage-details').focus();
	else
		$requestLocation.focus();
//	$('.none-found-heading').textfill({ maxFontPixels: 26 });
<?
else:
?>
//	$('.user-names').textfill({ maxFontPixels: 20});
/*
	$('.price-conts[title], .distance-conts[title], .reason-for-frees span[title]').qtip(
	{
		position: 
		{
			my: 'bottom center',
			at: 'top center'
		}
	});
*/

	// Only go ahead if GMaps has loaded
	if (typeof google === 'object' && typeof google.maps === 'object')
	{
		var gm = google.maps,
			bounds = new gm.LatLngBounds(),
			mainLoc = new gm.LatLng(<?= $lat.','.$lon ?>),
			mapOptions = 
			{
				mapTypeId: gm.MapTypeId.ROADMAP,
				zoom: 13,
				panControl: false,
				zoomControl: true,
				mapTypeControl: false,
				scaleControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				keyboardShortcuts: false
			},
			map = new gm.Map(document.getElementById("map"), mapOptions),
			markers = [],
			activeInfoWindow;

		var oms = new OverlappingMarkerSpiderfier(map, 
		{
			markersWontMove: true,
			markersWontHide: true,
			legWeight: 2.5 	// default is 1.5
		});
		
		oms.addListener('click', function(marker) 
		{
			var windowOptions,
				infoWindow;
			// Main location marker has no index. Don't remember why. Really should know this.
			if (typeof marker.index == 'undefined')
			{
				windowOptions = {
						content: '<div class="infowindows"><?= $readable_location ?></div>'
				};
			}
			else
			{
				<? if ($groups == 'tutors'): ?>
				var $result = $('.tutor-results').eq(marker.index),
					avatar = $result.attr('data-avatar'),
					url = $result.attr('data-url'),
					name = $result.attr('data-name'),
					price = $result.find('.prices').html(),
					rating = $result.find('.review-conts').html() || '';

				windowOptions = {
						content: '<div class="infowindows"><a href="'+url+'" title="See '+name+'\'s profile" target="_blank"><img src="'+avatar+'" class="gm-avatars"></a><a href="'+url+'" class="gm-names" target="_blank" title="See '+name+'\'s profile"><b>'+name+'</b></a><hr>'+rating+''+price+'</div>'
				};		
				<? else: ?>
				var $result = $('.request-results').eq(marker.index),
					subject = $result.find('.subject-strings').text(),
					url = $result.find('.subject-strings').attr('href'),
					price = $result.find('.max-prices').text(),
					applications = $result.find('.num-of-applications').text();

				windowOptions = {
						content: '<div class="infowindows"><a href="'+url+'" class="gm-names" target="_blank" title="See request details"><b>'+subject+'</b></a><hr>'+price+'</div>'
				};		

				<? endif; ?>
			}
			infoWindow = new gm.InfoWindow(windowOptions);

			if (activeInfoWindow)
				activeInfoWindow.close();

			activeInfoWindow = infoWindow;

			infoWindow.open(map, marker); // or this instead of marker
			// This is a hack. For some reason, GMaps shows only part of the infowindow and shows a scrollbar THE FIRST TIME it's opened
			if (isFirstInfoWindowClick)
			{
				isFirstInfoWindowClick = false;
				setTimeout(function() {
					infoWindow.open(map, marker);
				}, 500);				
			}

//			console.log(5);
		});

		var i,
			markerPositions = [<?= $item_markers['positions_string'] ?>],
			curLetter = 'A',
			marker,
			numOfMarkers = markerPositions.length;		// more efficient to just use the PHP var for tutor count?

		for(i = 0; i < numOfMarkers; i++) 
		{
			marker = new gm.Marker(
			{
				map: map,
			    animation: gm.Animation.DROP,
			    position: markerPositions[i],
			    index: i
			});
			marker.setIcon('http://www.google.com/mapfiles/marker'+curLetter+'.png');
			bounds.extend(markerPositions[i]);
			curLetter = nextChar(curLetter);

			oms.addMarker(marker);
			markers.push(marker);
		}


		var mainMarker = new gm.Marker(
		{
			map: map,
		    animation: gm.Animation.DROP,
		    position: mainLoc
		});

		mainMarker.setIcon('http://maps.google.com/mapfiles/ms/icons/blue-dot.png');

		oms.addMarker(mainMarker);

		bounds.extend(mainLoc);
		map.fitBounds(bounds);


	var mcOptions = 
	{
		maxZoom: 12,
		styles: [
		{
			height: 53,
			url: "<?= base_url('assets/images/markerClusters/m1.png') ?>",
			width: 53
		},
		{
			height: 56,
			url: "<?= base_url('assets/images/markerClusters/m2.png') ?>",
			width: 56
		},
		{
			height: 66,
			url: "<?= base_url('assets/images/markerClusters/m3.png') ?>",
			width: 66
		},
		{
			height: 78,
			url: "<?= base_url('assets/images/markerClusters/m4.png') ?>",
			width: 78
		},
		{
			height: 90,
			url: "<?= base_url('assets/images/markerClusters/m5.png') ?>",
			width: 90
		}]
	}
	var markerCluster = new MarkerClusterer(map, markers, mcOptions);


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

/*
	var $results = $('#find-results'),
		mapOffset = $map.position().top,
		startOffset = $results.offset().top,
		endOffset = startOffset + $results.outerHeight() - $('#find-map').outerHeight(),
		mapScrollAnimateSpeed = 150;

	$(window).scroll(function() 
	{
		if (window.vpWidth <= <?= SCREEN_SUB_REGULAR ?>)
		{
			return;
		}

		var scroll = $(this).scrollTop(),
			scrollFromStart = scroll - startOffset;

			// // console.log(startOffset, endOffset, mapOffset, scroll);

		if (scroll >= endOffset)
		{
			// // console.log('at end');
			$map.stop().animate(
			{
				'top': endOffset - startOffset - 20		// 20 is arbitrary; just looks nice
			}, mapScrollAnimateSpeed);
		}

		else if (scrollFromStart <= 0)
		{
			// // console.log('at start');
			$map.stop().animate(
			{
				'top': mapOffset
			}, mapScrollAnimateSpeed);
		}
		
		else
		{
			// // console.log('in progress');
			$map.stop().animate(
			{
				'top': scrollFromStart + mapOffset
			}, mapScrollAnimateSpeed);			
		}
	});
*/
<?
endif;
?>


});

function nextChar(c) {
    return String.fromCharCode(c.charCodeAt(0) + 1);
}

</script>