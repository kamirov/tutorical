<?// var_dump($this->session->all_userdata()); 
//var_dump($this->session->userdata('userdata'));
?>

<section id="account" class="cf containers pages" data-user-id="<?= '' ?>">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

		<div id="dashboard-content" class="account-subpage-conts">
			<div class="col-1 cols">
				<section id="profile-notices-cont" class="account-secs">
					<header>
					<? if ($profile_notices): 
						$notice_count = count($profile_notices);
						$heading = $notice_count == 1 ? 'Notice' : 'Notices';
					?>
						<h1><span class="number-of-items" id="profile-notices-count"><?= count($profile_notices) ?></span> <?= $heading ?></h1>
					<? else: ?>
						<h1>No Notices</h1>
					<? endif; ?>

					</header>
					<article class="profile-notices no-notices" <? if ($profile_notices) { echo 'style="display: none;"'; } ?>>
						<header>Important notices regarding your account and profile will appear here.</header>
					</article>

					<? 
						if ($profile_notices):
							foreach($profile_notices as $profile_notice):
					?>
						<article class="profile-notices <? if ($profile_notice['is_sticky']) echo 'sticky'; ?>" data-tpn-id="<?= $profile_notice['tpn_id'] ?>">
							<div class="ajax-overlays">
								<div class="ajax-overlays-bg"></div>
								<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
							</div>
							<header>
								<span class="profile-notice-close" title="Delete this notice">&times;</span>
								<span class="profile-notice-meta <?= $profile_notice['meta'] ?>"><?= $profile_notice['meta'] ?></span>

<!--								<span class="profile-notice-meta new">new</span>
-->							<h1><?= $profile_notice['title'] ?></h1>
							</header>
							<div class="profile-notice-body">
								<?= $profile_notice['content'] ?>
							</div>
							<div class="profile-notices-posted">
								<span>
									<?= time_elapsed_string($profile_notice['posted'], ' old') ?>
								</span>
							</div>
						</article>
					<? 
							endforeach;
					endif;
					?>
				</section>
			</div>
			<div class="col-2 cols">
				<? if ($role == ROLE_STUDENT): ?>
					<!-- Nada -->
				<? elseif ($location_set): ?>

				<section id="requests-cont" class="account-secs dashboard-request-secs" <? if ($requests['more_local']) { ?> data-more-local="1" <? } ?> >
					<header>
					<? if ($requests['local']['current_page_count']): ?>
						<h1>New local requests</h1>
					<? else: ?>
						<h1>No new local requests</h1>
					<? endif; ?>
					</header>

					<article class="requests no-requests" <? if ($requests['local']['current_page_count']) { echo 'style="display: none;"'; } ?>>
						<header>Requests for any subject near you will appear here.</header>
					</article>
				<?
					if ($requests['local']['current_page_count']):
						foreach($requests['local']['items'] as $request): 
				?>
						<article class="requests cf" data-id="<?= $request['id'] ?>">
							<div class="ajax-overlays">
								<div class="ajax-overlays-bg"></div>
								<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
							</div>
							<header>
<!--								<span class="request-remove" title="Remove this request">&times;</span>
-->								<h1 class="request-links"><?= anchor('requests/'.$request['id'], $request['subjects_string']) ?></h1>
							</header>
							<div class="request-details">
								<?= ellipsis_text($request['details'], 200) ?>
							</div>

							<div class="request-buttons button-groups cf">
								<?= anchor('requests/'.$request['id'].'/apply', 'Apply', 'class="buttons small-buttons color-3-buttons" title="Go to the request\'s page."'); ?>
								<span class="buttons small-buttons remove-request-buttons" title="Remove the request from this list. You'll still be able to find it using the main site search.">Remove</span>
							</div>

							<div class="request-meta">
									<span class="request-max-prices">			
										
											<? if ($request['price'] != 0 ):	// Need to explicity check because 0 result is the string "0.00", which PHP doesn't equate to 0 (it does for "0" though) ?>
											<span class="hourly-prices"><?= $request['currency_sign'].$request['price'] ?><span class="per-hour"> / hour</span></span> <span class="currencies">(<?= $request['currency'] ?>)</span>
											<? else: ?>
											<span class="hourly-prices">Price not listed</span>
											<? endif; ?>
									</span>
									<!--
									|
									<span class="request-posteds">
										<?= time_elapsed_string($request['posted'], ' old') ?>
									</span>
									-->
							</div>
						</article>
					<? 
							endforeach;
						if ($requests['more_local']): ?>
							<div class="see-more-items">
								<?= anchor('find/local/requests/'.$requests['location']['city'].', '.$requests['location']['country'].'/?sort=new', 'See more requests near you'); ?>
							</div>
					<? endif; ?>
				<?
					endif;
				?>
				</section>

				<? else: ?>
					<section id="requests-cont" class="account-secs dashboard-request-secs">
						<header>
							<h1>Local requests</h1>
						</header>

						<article class="requests">
							<header>Requests for any subject near you will appear here after you specify your location on your <?= anchor('account/profile', 'Edit Profile page') ?>.</header>
						</article>
					</section>
				<? endif; ?>
				<!--
				<section id="distance-requests-cont" class="account-secs dashboard-request-secs">
				<? 
					if (FALSE): //$requests['distance']['current_page_count']): 
				?>
					<header>
						<h1>New distance requests</h1>
					</header>
				<?
						foreach($requests['distance']['items'] as $request): 
				?>
						<article class="requests cf" data-id="<?= $request['id'] ?>">
							<div class="ajax-overlays">
								<div class="ajax-overlays-bg"></div>
								<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
							</div>
							<header>
								<h1 class="request-links"><?= anchor('requests/'.$request['id'], $request['subjects_string']) ?></h1>
							</header>
							<div class="request-details">
								<?= ellipsis_text($request['details'], 200) ?>
							</div>

							<div class="request-buttons button-groups cf">
								<?= anchor('requests/'.$request['id'], 'Apply', 'class="buttons small-buttons color-3-buttons" title="Go to the request\'s page."'); ?>
								<span class="buttons small-buttons" class="remove-request-buttons" title="Remove the request from this list. You'll still be able to find it using the main site search.">Remove</span>
							</div>

							<div class="request-meta">
									<span class="request-max-prices">			
										
											<? if ($request['price'] != 0 ):	// Need to explicity check because 0 result is the string "0.00", which PHP doesn't equate to 0 (it does for "0" though) ?>
											<span class="hourly-prices"><?= $request['currency_sign'].$request['price'] ?><span class="per-hour"> / hour</span></span> <span class="currencies">(<?= $request['currency'] ?>)</span>
											<? else: ?>
											<span class="hourly-prices">Price not listed</span>
											<? endif; ?>
									</span>
									|
									<span class="request-posteds">
										<?= time_elapsed_string($request['posted'], ' old') ?>
									</span>
							</div>
						</article>
					<? 
							endforeach;
						if ($requests['more_distance']): ?>
							<div class="see-more-items">
								<?= anchor('find/distance/requests/?sort=new', 'See more distance requests'); ?>
							</div>
					<? endif; ?>
				<?
					else:
				?>
					<header>
						<h1>No new distance requests</h1>
					</header>
						<article class="requests no-requests">
							<header>Distance requests for any subject will appear here.</header>
						</article>

					<? endif; ?>
				</section>
				-->
			</div>
		</div>
	

</section>

<script>

$(function()
{
	$('.remove-request-buttons').click(function()
	{
		var $this = $(this),
			$item = $this.parents('.requests'),
			$overlay = $item.find('.ajax-overlays'),
			count = $item.siblings('.requests').not('.no-requests').length + 1;

		$overlay.fadeIn(<?= FAST_FADE_SPEED ?>);
		$.ajax({
			url: '<?= base_url("account/hide_request_from_dashboard") ?>',
			type: "POST",
			data: {
				'id': $item.attr('data-id')
			},	
			dataType: 'json'
		}).done(function(response) 
		{
			if (response.success == true)
			{		
				if (count == 1)
				{

					if ($('#requests-cont').attr('data-more-local') == 1)
					{
						$item.siblings('header').find('h1').text('Refresh to see new local requests');
					}
					else
					{
						$item.siblings('header').find('h1').text('No new local requests');						
						$item.siblings('.see-more-items').remove();
						$('.no-requests').slideDown();
					}
	
				}

				$item.slideUp(function() 
				{
					$item.remove();
				});
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
	});

	$('.profile-notice-close').click(function()
	{
		var $this = $(this),
			$notice = $this.parents('.profile-notices'),
			$overlay = $notice.find('.ajax-overlays'),
			$count = $('#profile-notices-count');

		$overlay.fadeIn(<?= FAST_FADE_SPEED ?>);
		$.ajax({
			url: '<?= base_url("account/delete_profile_notice") ?>',
			type: "POST",
			data: {
				'tpn-id': $notice.attr('data-tpn-id')
			},	
			dataType: 'json'
		}).done(function(response) 
		{
			if (response.success == true)
			{		
				if ($count.text() == '1')
				{
					$count.parent().text('No Notices');
					$('.no-notices').slideDown();
				}
				else
				{
					$count.text(+$count.text()-1);						
				}

				$notice.slideUp(function() 
				{
					$notice.remove();
				});
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
	});

	$.post("<?= base_url('account/send_welcome_if_needed') ?>");

	<? if (isset($password_changed_user_logged)): 
		$noty = '<b>Password changed.</b><hr> We\'ve also logged you in.';
	?>
		noty({
			text: <?= json_encode($noty) ?>,
			timeout: 5000,
			type: 'success'
		});
	<? endif; ?>

	<? if (isset($password_created)): 
		$noty = '<b>Welcome to Tutorical!</b>';
	?>
		noty({
			text: <?= json_encode($noty) ?>,
			timeout: 2500,
			type: 'success'
		});
	<? endif; ?>

	<? if (isset($password_changed_already_logged)): 
		$noty = '<b>Password changed.</b><hr> After logging out, you can use your new password to log in.';
	?>
		noty({
			text: <?= json_encode($noty) ?>,
			type: 'success'
		});
	<? endif; ?>
	
});

</script>