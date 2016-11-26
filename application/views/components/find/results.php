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
					<?= anchor('tutors/'.$tutor['username'], '<img alt="Avatar" class="avatars" src="'.$tutor['avatar_url'].'" />', 'class="avatar-conts" target="_blank"') ?>
					<?// 	 anchor('tutors/'.$tutor['username'], 'View Profile', 'class="view-profile-links"') ?>
				</div>
			</div>
			<h2 class="user-names"><span><?= anchor('tutors/'.$tutor['username'], $tutor['display_name'], 'target="_blank"') ?></span></h2>

			<div class="search-text-conts">
				<?// $tutor['experience'] ?>
				
				<? if ($tutor['snippet']): ?>
					<p><?= $tutor['snippet'] ?></p>	
				<? else: ?>
					<p>A Tutorical tutor</p>
				<? endif; ?>
			</div>

			<? 
			if ($tutor['num_of_reviews'] != 0)
			{

				$review_title = "Based on ".$tutor['num_of_reviews']." ".($tutor['num_of_reviews'] > 1 ? "reviews" : "review");

				echo '
				<div class="review-conts">
					<form title="'.$review_title.'">';	// title goes on form because infowindow takes the review-conts html. If we put title on review-conts, then it doesn't come with it

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

			<div class="tutor-result-bits">
				<div class="price-conts no-pointer">
					<div class="prices">

					<? if ($tutor['price_high'] > 0): ?>

						<span class="hourly-prices"><?= $tutor['currency_sign'].$tutor['price'] ?> &#8211; <?= $tutor['currency_sign'].$tutor['price_high'] ?><span class="per-hour"> / hour</span> <span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

					<? elseif ($tutor['price_type'] == 'per_hour'): ?>
			
						<span class="hourly-prices"><?= $tutor['currency_sign'].$tutor['price'] ?><span class="per-hour"> / hour</span> </span><span class="currencies">(<?= $tutor['currency'] ?>)</span>
			
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
				<!--
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
				-->
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
				<span class="request-posteds">Posted <?/* time_elapsed_string($request['posted'], ' ago') */?></span> by <?= anchor('students/'.$request['username'], $request['display_name'], 'class="request-posters"') ?>
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

<script>

$(function()
{
	console.log('results loaded');
	$('.review-conts input[name=star]').rating();
/*
	$('.paginate').click(function()
	{
		var $this = $(this),
			page = $this.attr('data-page'),
			data = {
				page: page
			};

		$.ajax(
		{
			url: "<?= base_url('find/fetch_results') ?>",
			type: "POST",
			data: data,
			dataType: 'json'
		}).done(function(response)
		{
//			$form.validate(response.errors);
			console.log(response);

			if (response.success == true)
			{
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
//			$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
		});

		return false;
	})
*/
})

</script>