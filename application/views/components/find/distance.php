<?
	$sort_price_text = ($groups == 'tutors' ? 'Lowest Priced' : 'Highest Paying');
?>

<section class="find-bars containers cf">

	<div class="sort-by-cont">

		<span class="search-types-cont sort-by-description-text">
		<? if ($groups == 'requests'): ?>
			<span class="search-types active">Requests</span> | <?= anchor("find/distance/tutors/$subject_query", "Tutors", 'class="search-types" title="See '.($readable_subject ? $readable_subject.' ' : '').'tutors that teach online"') ?>
		<? else: ?>
			<?= anchor("find/distance/requests/$subject_query", "Requests", 'class="search-types" title="See '.($readable_subject ? $readable_subject.' ' : '').'requests looking for an online tutor"') ?> | <span class="search-types active">Tutors</span>
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
	            <li><?= anchor(this_url_with_query(array('sort' => 'price', 'page' => 1)), $sort_price_text) ?></li>
	            <? if ($groups == 'tutors'): ?>
	            <li><?= anchor(this_url_with_query(array('sort' => 'rating', 'page' => 1)), 'Highest Rated') ?></li>
	    	    <? endif; ?>
	            <li><?= anchor(this_url_with_query(array('sort' => 'new', 'page' => 1)), 'Newest') ?></li>
		    </ul>
		</div>
	</div>

</section>

<?
if ($num_of_items != 0):
?>

<section id="find-results" class="cf containers">

	<div class="col-1">
	<?
		if ($groups == 'tutors'): ?>

		<div id="find-item-results">
	<?
			foreach ($items as $tutor):
	?>
				<div class="tutor-results distance-tutors cf" data-url="<?= base_url('tutors/'.$tutor['username']) ?>" data-avatar="<?= $tutor['avatar_url'] ?>" data-name="<?= $tutor['display_name'] ?>">
					<div class="review-cover"></div>
					<div class="avatar-and-marker-conts">
						<div class="avatar-and-profile-links">
							<?= anchor('tutors/'.$tutor['username'], '<img alt="Avatar" class="avatars" src="'.$tutor['avatar_url'].'" />', 'class="avatar-conts"') ?>
							<?// 	 anchor('tutors/'.$tutor['username'], 'View Profile', 'class="view-profile-links"') ?>
						</div>
					</div>
					<h2 class="user-names"><span><?= anchor('tutors/'.$tutor['username'], $tutor['display_name']) ?></span></h2>
					<span class="countries">
						<img alt="Flag photo" class="flags" src="<?= $tutor['flag_url'] ?>"> 
						<?= $tutor['city'] ?>, <?= $tutor['country'] ?>
					</span>
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
			<div class="request-results distance-requests">
				<div class="subjects-string-conts">
					<a href="<?= base_url('requests/'.$request['id']) ?>" title="This student wants to learn <?= $request['subjects_string'] ?>" class="subject-strings"><?= ellipsis_text($request['subjects_string'], 50) ?></a>
				</div>

				<div class="request-content">
					<div class="request-result-bits"> 
						<img alt="Flag photo" class="flags" src="<?= $request['flag_url'] ?>"> 
						<?= $request['city'] ?>, <?= $request['country'] ?> 
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

	</div>

	<div class="col-2">
		<div id="distance-description">
			<div class="boxes">
				<? if ($groups == 'tutors'): ?>
				<h2><b>What is a distance tutor?</b></h2>
				<p><b>Distance tutors</b> teach either over the phone or online.</p> <p>If you work with a distance tutor, you'll be:</p>
				<ul>
					<li>Learning from a distance, probably by phone, email, chat, or Skype</li>
					<li>Paying via Paypal, cheque, email transfer, or some other non-cash means</li>
				</ul>
				<? else: ?>
				<h2><b>What is a distance request?</b></h2>
				<p><b>Distance requests</b> are made by students that want to learn with a tutor online.</p> <p>If you apply to a distance request, you'll be</p>
				<ul>
					<li>Teaching over a distance, probably by phone, email, chat, or Skype</li>
					<li>Getting paid via Paypal, cheque, email transfer, or some other non-cash means</li>
				</ul>
				<? endif; ?>
			</div>
		</div>
	</div>

</section>  <!-- /#find-results -->

<? else: ?>

<section id="text-regular" class="cf pages containers">
	<h1 id="page-heading" class="none-found-heading">
		<span>Sorry, there are no <span class="find-editables"><?= $readable_subject ?></span> tutors that teach online</span>...yet!</span>
	</h1>
	<div id="page-content" class="text-content">
		<p>But you might find some if you <a href="javascript:void(0);" data-reveal-id="request-modal">make a Tutor Request</a>. It's free and it'll let tutors find you!</p>
	</div>

	<?= $extra_make_request ?>
</section>  <!-- /#text-regular -->

<? endif; ?>


<script>

$(function() 
{
	$('.review-conts input[name=star]').rating();


<?
if ($num_of_items == 0):
?>
//	$('.none-found-heading').textfill({ maxFontPixels: 26 });
<?
else:
?>
/*
	$('.user-names').textfill({ maxFontPixels: 20});

	$('.price-conts[title], .distance-conts[title], .reason-for-frees span[title]').qtip(
	{
		position: 
		{
			my: 'bottom center',
			at: 'top center'
		}
	});
*/
<?
endif;
?>

});

</script>
