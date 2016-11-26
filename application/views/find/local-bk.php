<?
	$sort_price_text = ($groups == 'tutors' ? 'Lowest Priced' : 'Highest Paying');
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
				No <?= $groups ?> found
			<? elseif ($num_of_items == 1): ?>
			 	1 <?= $group ?> found
			<? elseif ($num_of_pages > 1): ?>
				<?= $items_low ?>-<?= min($num_of_items, $items_high) ?> of <?= $num_of_items ?> <?= $groups ?>
			<? else: ?>
				<?= $num_of_items ?> <?= $groups ?> found
			<? endif; ?>			
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

	<div id="results-items-cont">
	<?
		if ($groups == 'tutors'): ?>

		<div id="find-item-results">
	<?
			foreach ($items as $tutor):
	?>
				<div class="tutor-results cf" data-url="<?= base_url('tutors/'.$tutor['username']) ?>" data-avatar="<?= $tutor['avatar_url'] ?>" data-name="<?= $tutor['display_name'] ?>">
					<div class="review-cover"></div>
					<div class="avatar-and-marker-conts">
						<div class="avatar-and-profile-links">
							<img alt="Map Marker" class="map-markers" src="<?= $tutor['marker_url']; ?>">
							<?= anchor('tutors/'.$tutor['username'], '<img alt="Avatar" class="avatars" src="'.$tutor['avatar_url'].'" />', 'class="avatar-conts"') ?>
							<?// 	 anchor('tutors/'.$tutor['username'], 'View Profile', 'class="view-profile-links"') ?>
						</div>
					</div>
					<div class="tutor-result-items">
						<div class="text-details-conts">
							<? 
							if ($tutor['num_of_reviews'] != 0)
							{

								$review_title = "Based on ".$tutor['num_of_reviews']." ".($tutor['num_of_reviews'] > 1 ? "reviews" : "review");

								echo '
								<div class="review-conts" title="'.$review_title.'">
									<form>';	

								$max = 5.5; // *n because each star is broken into n; +1 because loop starts at 1, not 0
								for ($i = 0.5; $i < $max; $i+=0.5)
								{
									echo '<input name="star" disabled="disabled" type="radio" '.($tutor['average_rating'] == $i ? 'checked="checked"' : '').' class="star {split:2}"/>';
								}

								echo '
										<span class="num-of-reviews">('.$tutor['num_of_reviews'].')</span>
									</form>
								</div>';
							}
							?>
							<h2 class="user-names"><span><?= anchor('tutors/'.$tutor['username'], $tutor['display_name']) ?></span></h2>

							<div class="search-text-conts">
								<?// $tutor['experience'] ?>
								
								<? if ($tutor['snippet']): ?>
									<p><?= $tutor['snippet'] ?></p>	
								<? else: ?>
									<p>A Tutorical tutor</p>
								<? endif; ?>

							</div>
						</div>
					</div>
					<div class="tutor-result-bits">
						<div class="price-conts no-pointer">
							<div class="prices">

							<? if ($tutor['price_high'] > 0): ?>

								<span class="hourly-prices"><?= $tutor['currency_sign'].$tutor['price'] ?> &#8211; <?= $tutor['currency_sign'].$tutor['price_high'] ?><span class="per-hour"> / hour</span><span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

							<? elseif ($tutor['price_type'] == 'per_hour'): ?>
					
								<span class="hourly-prices"><?= $tutor['currency_sign'].$tutor['price'] ?><span class="per-hour"> / hour</span></span><span class="currencies">(<?= $tutor['currency'] ?>)</span>
					
							<? elseif ($tutor['price_type'] == 'free'): ?>

								<span class="frees">Free</span>
								
								<? if ($tutor['price_notes']): ?>
									<span class="reason-for-frees tiny-aftertext">(<span class="same-page-links no-pointer" title="<?= $tutor['price_notes'] ?>">why?</span>)</span>
								<? endif; ?>
					
							<? endif; ?>
							</div>
						</div>

						<div class="distance-conts no-pointer">
							<span class="distances"><?= $tutor['distance'] ?> </span>
						</div>
					</div>
				</div>
	<?
			endforeach;
	?>
		</div>
	<?
		else:
	?>
		<div id="find-item-results">
	<?
			foreach ($items as $request):
//				var_dump($request);

	?>
			<div class="request-results">
				<div class="marker-conts">
					<img alt="Map Marker" class="map-markers" src="<?= $request['marker_url']; ?>">
				</div>
				<div class="subjects-string-conts">
					<a href="<?= base_url('requests/'.$request['id']) ?>" title="This student wants to learn <?= $request['subjects_string'] ?>" class="subject-strings"><?= ellipsis_text($request['subjects_string'], 50) ?></a>
				</div>

				<div class="request-content">
					<div class="request-result-bits">
						<span class="distances"><?= $request['distance'] ?></span> 
						|
						<span class="max-prices">			
							Max -  
								<? if ($request['price'] != 0 ):	// Need to explicity check because 0 result is the string "0.00", which PHP doesn't equate to 0 (it does for "0" though) ?>
								<span class="hourly-prices"><?= $request['currency_sign'].$request['price'] ?><span class="per-hour"> / hour</span></span> <span class="currencies">(<?= $request['currency'] ?>)</span>
								<? else: ?>
								<span class="hourly-prices">Price not listed</span>
								<? endif; ?>
						</span>
						|
						<span class="num-of-applications">
							<a href="<?= base_url('requests/'.$request['id']) ?>">
								<? if ($request['num_of_applications'] == 0): ?>
									No applications
								<? elseif ($request['num_of_applications'] == 1): ?>
									1 application
								<? else: ?>
									<?= $request['num_of_applications'] ?> applications
								<? endif; ?>
							</a>
						</span>

					</div>

					<div class="text-details-conts">
						<div class="search-text-conts">							
							<? if ($request['details']): ?>
								<p><?= ellipsis_text($request['details'], 200) ?></p>
							<? else: ?>
								<p class="no-details">No additional details</p>
							<? endif; ?>
						</div>
					</div>

					<div class="request-metas">
						<span class="request-posteds">Posted <?= time_elapsed_string($request['posted'], ' ago') ?></span> by <?= anchor('students/'.$request['username'], $request['display_name'], 'class="request-posters"') ?>
					</div>
				</div>

			</div>

	<?
			endforeach;
	?>
		</div>
	<?
		endif;
	?>

	<div class="pagination">
	<?= $page_list ?>
	</div>

	</div>

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

</section>  <!-- /#find-results -->

<? else: ?>

<section id="text-regular" class="cf pages containers">
<? if ($groups == 'tutors'): ?>
<h1 id="page-heading" class="none-found-heading">
	<span>Sorry, there are no <span class="find-editables"><?= $readable_subject ?></span> tutors within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>...yet!</span>
</h1>
	<div id="page-content" class="text-content">
		<p>But you might find some if you <a href="javascript:void(0);" data-reveal-id="request-modal">make a Tutor Request</a>. It's free and it'll let tutors find you!</p>
	</div>
<? else: ?>
<h1 id="page-heading" class="none-found-heading">
	<span>Sorry, there are no <span class="find-editables"><?= $readable_subject ?></span> requests within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>...yet!</span>
</h1>

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
					- padding;

	$('#find-results').height(findHeight);
//	$(‘#somediv’).css({‘height’: vph + ‘px’});
}


$(function() 
{
	$('.review-conts input[name=star]').rating();

	resizeFind();
	$(window).resize(resizeFind);

	var $map = $('#find-map');

<?
if ($num_of_items == 0):
?>
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
			// Main location marker has no index. Don't remember why. Really should know this.
			if (typeof marker.index == 'undefined')
			{
				return;
			}

			var $result = $('.tutor-results').eq(marker.index),
				avatar = $result.attr('data-avatar'),
				url = $result.attr('data-url'),
				name = $result.attr('data-name'),
				windowOptions = {
					content: '<div class="infowindows"><a href="'+url+'" title="See '+name+'\'s profile"><img src="'+avatar+'" class="gm-avatars"></a><a href="'+url+'" class="gm-names" title="See '+name+'\'s profile"><b>'+name+'</b></a></div>'
				},
				infoWindow = new gm.InfoWindow(windowOptions);

			if (activeInfoWindow)
				activeInfoWindow.close();

			activeInfoWindow = infoWindow;

			infoWindow.open(map, marker); // or this instead of marker
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