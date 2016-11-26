<?
//var_dump($contacts);

$review_textarea = array(
	'name'			=> 'content',
//	'id'			=> 'display-name',
//	'maxlength' 	=> 40,
	'class'			=> '',
	'value'			=> ''
);

$tips = array(
	'rating' => "Rate your overall experience with the tutor.",
	'expertise' => 'Did the tutor seem to understand what he/she was teaching you? Would you consider them experts?',
	'helpfulness' => 'Did the tutor try to help you understand the material rather than just telling you the answers?',
	'response' => 'Did the tutor respond quickly to all of your inquiries?',
	'clarity' => 'Was the tutor easy to understand? Did he/she speak and write clearly?',
	'review-content' => 'What are some things you liked/disliked about this tutor? What did they do well? What did they do poorly?'
);

$element_submit_container = 
'
<div class="submit-conts">
	<input type="submit" value="Save" class="buttons color-3-buttons"> <a href="javascript:void(0);" class="remove-item-links danger-page-links">remove review</a>
</div>
';

$favourited_count = count($favourited);

?>


<section id="account" class="cf pages containers">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

	<div id="tutors-content" class="account-subpage-conts" >

	<div id="contacts-header" style="<? if (!$has_hidden) { echo 'display: none;'; } ?>">
		<? if ($show_hidden): ?>
			<a href="<?= base_url('account/tutors') ?>" title="Hide previously hidden tutors">Hide hidden</a>
		<? else: ?>
			<a href="<?= base_url('account/tutors') ?>/?hidden=show" title="Show previously hidden tutors">Show hidden</a>
		<? endif; ?>
	</div>

<!--
	<div id="tables-search-cont">
		<label for="tables-search">Search: </label><input type="text" id="tables-search">
	</div>
-->

<div class="default-page-text <?= ($contact_count || $favourited_count ? 'hidden' : '') ?>" id="" style="">
	<h2 class="table-headings" id="no-count-tutors-heading">
		Your Tutors
	</h2>
	
	<p>Tutors you've contacted, worked with, or favourited will appear here. To work with a tutor, contact them through their tutor profile or accept them to a tutor request. To favourite a tutor, click the "Favourite" link on their profile.</p>
</div>

<div id="tutors-cont" class="contacts-table-conts <?= ($contact_count ? '' : 'hidden') ?>">

	<h2 class="table-headings <?= ($contact_count ? '' : 'hidden') ?>" id="count-tutors-heading">
		Your Tutors (<span class="counts" id="contact-count"><?= $contact_count ?></span>)
	</h2>
		
	<table class="contact-tables" id="tutors-table">
		<thead>
			<tr>
				<th class="status-cells">Status</th>
				<th class="name-cells">Name</th>
				<th class="email-cells">Email</th>
				<th class="notes-cells">Notes</th>
				<th class="review-cells">Review</th>
				<th class="contacted-cells">Contacted</th>
				<th class="actions-cells"></th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($contacts as $contact): 
				$contacted = new DateTime($contact['contacted']);
				$rating = json_decode($contact['review'])->rating;
				$worked_with = in_array($contact['id'], $worked_with_ids);
//				var_dump($worked_with, $contact['id'], $worked_with_ids);
			?>
			<tr data-id="<?= $contact['id'] ?>" class="contact-rows <?= $contact['type'] ?>" data-type="request" data-contact-id="<?= $contact['id'] ?>" data-contact-message="<?= nl2br(htmlspecialchars($contact['message'])) ?>" data-notes="<?= htmlspecialchars($contact['tutor_notes']) ?>" data-review="<?= htmlspecialchars($contact['review']) ?>" data-worked-with="<?= $worked_with ?>">
				<td class="status-cells">
					<? if ($contact['type'] == 'pending'): ?>
						<span title="This tutor has not accepted you as a student yet">Pending</span>
					<? elseif ($contact['type'] == 'active'): ?>
						<span title="You're currently working with this tutor">Active</span>
					<? else: ?>
						<span title="You've worked with this tutor in the past">Past</span>
					<? endif; ?>
				</td>
				<td class="name-cells">
					<img src="<?= $contact['avatar_url'] ?>" class="contact-avatars">
					<? if ($contact['contact_id'] == DELETED_ID): ?> 
						<span title="This person has deleted their account"><?= $contact['display_name'] ?></span>
					<? else: ?>
						<?= anchor($contact['profile_path'], $contact['display_name'], 'title="'.$contact['display_name'].'"') ?>
					<? endif; ?>
				</td>
				<td class="email-cells">
					<? if ($contact['type'] == 'pending' && !$worked_with): ?>
					[<span title="The tutor's email will show up here when they accepts you as a student" class="same-page-links no-pointer">hidden</span>]
					<? else: ?>
					<?= ($contact['email'] ? mailto($contact['email'], $contact['email'], 'title="'.$contact['email'].'"') : '') ?>
					<? endif; ?>
				</td>
				<td class="notes-cells">
					<div class="show-link-conts">
						 <span class="same-page-links show-links" data-template-row-class="notes-rows" data-switch-content="Hide">Show</span>
					</div>
				</td>
				<td class="review-cells">			
					<? if ($contact['type'] == 'pending' && !$worked_with): ?>
						[<span title="You'll be able to write a review of the tutor after they accept you as a student" class="same-page-links no-pointer">hidden</span>]
					<? else: ?>
						<div class="review-displays">
							<form>
						<? if ($rating && $contact['type'] != 'pending'): ?>
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 0.5) echo 'checked="checked"' ?> value="0.5" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 1) echo 'checked="checked"' ?> value="1.0" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 1.5) echo 'checked="checked"' ?> value="1.5" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 2) echo 'checked="checked"' ?> value="2.0" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 2.5) echo 'checked="checked"' ?> value="2.5" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 3) echo 'checked="checked"' ?> value="3.0" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 3.5) echo 'checked="checked"' ?> value="3.5" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 4) echo 'checked="checked"' ?> value="4.0" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 4.5) echo 'checked="checked"' ?> value="4.5" >
									<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($rating == 5) echo 'checked="checked"' ?> value="5.0" >
								</form>
							</div>
							 <span class="same-page-links show-links" data-template-row-class="review-rows" data-switch-content="Hide">Change</span>
						<? else: ?>
								</form>
							</div>
							 <span class="same-page-links show-links" data-template-row-class="review-rows" data-switch-content="Hide">Write</span>
						<? endif; ?>
					<? endif; ?>
				</td>
				<td class="contacted-cells">
					<?= $contacted->format('M d, Y') ?>
				</td>

				<td class="actions-cells">
					<!--
					<span class="hide-buttons" title="Hide this request. This doesn't delete it, but removes it from this list.">&ndash;</span>
					<span class="show-buttons" title="This request is hidden. Click this to show it.">&bull;</span>
					-->
					<? if ($contact['type'] == 'pending'): ?>
						<span class="action-buttons action-cancel" title="Cancel your request for this tutor">Cancel</span>
					<? elseif ($contact['type'] == 'active'): ?>
						<span class="action-buttons action-remove" title="Finish working with this tutor">Remove</span>
					<? else: ?>
						<? if ($contact['hidden']): ?>
						<span class="action-buttons action-show" title="Make this tutor visible when normally visiting this page">Show</span>
						<? else: ?>
						<span class="action-buttons action-hide" title="Hide this tutor from this list">Hide</span>
						<? endif; ?>
						| 
						<span class="action-buttons action-request" title="Inform this tutor that you want to be their student again">Request</span>
					<? endif; ?>
				</td>
			</tr>
			<? endforeach; ?>
		</tbody>
	</table>
</div>

<? if ($favourited_count): ?>
	<div id="favourited-tutor-cont-cont">	<!-- for the love of god, man -->
		<h2 class="table-headings">Favourited Tutors (<span id="favourited-count" class="counts"><?= $favourited_count ?></span>)</h2>

		<section id="favourited-tutor-cont">
		<? foreach($favourited as $tutor): ?>
			<div class="favourited-tutors" data-id="<?= $tutor['id'] ?>">
				<span class="hide-buttons" title="Unfavourite this tutor">&times;</span>
				<a href="<?= base_url('tutors/'.$tutor['username']) ?>"><img class="favourited-tutor-avatars" src="<?= base_url($tutor['avatar_path']) ?>"></a>
				<a href="<?= base_url('tutors/'.$tutor['username']) ?>"><?= $tutor['display_name'] ?></a>
				<img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
			</div>
		<? endforeach; ?>
		</section>
	</div>
<? endif; ?>


		<table id="templates-table">
			<tr class="notes-rows temp-rows">
				<td></td>
				<td colspan="5" class="notes-content-cells active">
					<div class="ajax-overlays">
						<div class="ajax-overlays-bg"></div>
						<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
					</div>

					<h4>Your initial message:</h4>
					<div class="contact-message">
						<span class='no-message'>(No message provided)</span>
					</div>
					
					<h4>Your notes <span class="debold">(<span class="same-page-links no-pointer" title="Click text to edit. These notes are private; your tutor cannot see them.">?</span>)</span>:</h4>
					<textarea class="notes" placeholder="e.g. phone, address, session notes"></textarea>

					<div class="submit-conts">
						<a href="javascript:void(0);" class="same-page-links save-cancel-buttons save-buttons">Save Changes</a> | 
						<a href="javascript:void(0);" class="same-page-links save-cancel-buttons cancel-buttons danger-page-links">Cancel</a>
					</div>
				</td>
				<td colspan="1"></td>
			</tr>
			<tr class="review-rows temp-rows">
				<td></td>
				<td colspan="5" class="review-content-cells active">
					<div class="user-reviews">
						<?= form_open() ?>
						<input type="hidden" name="tutor-id" value="">
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>

						<div class="review-statistics">
							<div class="review-rating">
								<input name="rating" type="radio" class="star {split:2}" value="0.5" title="Terrible. I don't recommend this tutor for anyone!">
								<input name="rating" type="radio" class="star {split:2}" value="1.0" title="Terrible. I don't recommend this tutor for anyone!">
								<input name="rating" type="radio" class="star {split:2}" value="1.5" title="Bad. I'll only hire again if it's an emergency.">
								<input name="rating" type="radio" class="star {split:2}" value="2.0" title="Bad. I'll only hire again if it's an emergency.">
								<input name="rating" type="radio" class="star {split:2}" value="2.5" title="Okay. I'd prefer a different tutor, but I can work with him/her again.">
								<input name="rating" type="radio" class="star {split:2}" value="3.0" title="Okay. I'd prefer a different tutor, but I can work with him/her again.">
								<input name="rating" type="radio" class="star {split:2}" value="3.5" title="Great. There were some problems, but I'd work with him/her again.">
								<input name="rating" type="radio" class="star {split:2}" value="4.0" title="Great. There were some problems, but I'd work with him/her again.">
								<input name="rating" type="radio" class="star {split:2}" value="4.5" title="Fantastic! Recommended for everyone!"> 
								<input name="rating" type="radio" class="star {split:2}" value="5.0" title="Fantastic! Recommended for everyone!">
								<span id="rating-tip">
									(<span class="same-page-links no-pointer" title="<?= $tips['rating'] ?>">?</span>)
								</span>
							</div>
							<div class="review-detailed-ratings-cont">
								<div class="review-detailed-ratings-and-names">
									<div class="review-detailed-ratings box-ratings-conts">
										<input name="expertise" type="radio" class="star box-ratings" value="1" title="Little to no knowledge about the material.">
										<input name="expertise" type="radio" class="star box-ratings" value="2" title="Knew a bit of the material. Very often had to look things up.">
										<input name="expertise" type="radio" class="star box-ratings" value="3" title="Knew some of the material. Had to look things up sometimes. ">
										<input name="expertise" type="radio" class="star box-ratings" value="4" title="Knew most of the material. Rarely had to look anything up.">
										<input name="expertise" type="radio" class="star box-ratings" value="5" title="Very knowledgeable. Almost never had to look anything up.">
									</div>
									<span class="review-detailed-rating-names">Expertise (<span class="same-page-links no-pointer" title="<?= $tips['expertise'] ?>">?</span>)</span>
								</div>
								<div class="review-detailed-ratings-and-names">
									<div class="review-detailed-ratings box-ratings-conts">
										<input name="helpfulness" type="radio" class="star box-ratings" value="1" title="No help at all. Didn't bother trying to help me understand the material.">
										<input name="helpfulness" type="radio" class="star box-ratings" value="2" title="Not very helpful. I have a very light understanding of the material.">
										<input name="helpfulness" type="radio" class="star box-ratings" value="3" title="Helped me get somewhat of a grasp on the material. I could use some more help though.">
										<input name="helpfulness" type="radio" class="star box-ratings" value="4" title="Helpful. I more-or-less understand the material now. I'm not sure about everything though.">
										<input name="helpfulness" type="radio" class="star box-ratings" value="5" title="Very helpful! Helped me completely understand the material.">
									</div>
									<span class="review-detailed-rating-names">Helpfulness (<span class="same-page-links no-pointer" title="<?= $tips['helpfulness'] ?>">?</span>)</span>
								</div>
								<div class="review-detailed-ratings-and-names">
									<div class="review-detailed-ratings box-ratings-conts">
										<input name="response" type="radio" class="star box-ratings" value="1" title="Unresponsive. Took forever to get back to me, if at all.">
										<input name="response" type="radio" class="star box-ratings" value="2" title="Usually responded within 1 week.">
										<input name="response" type="radio" class="star box-ratings" value="3" title="Usually responded within 1-2 days.">
										<input name="response" type="radio" class="star box-ratings" value="4" title="Usually responded on the same day; apologized if he/she took longer.">
										<input name="response" type="radio" class="star box-ratings" value="5" title="Usually responded within 2 hours; apologized if he/she took longer.">

									</div>
									<span class="review-detailed-rating-names">Response (<span class="same-page-links no-pointer" title="<?= $tips['response'] ?>">?</span>)</span>
								</div>
								<div class="review-detailed-ratings-and-names">
									<div class="review-detailed-ratings box-ratings-conts">
										<input name="clarity" type="radio" class="star box-ratings" value="1" title="Writing/typing/talking was impossible to understand!">
										<input name="clarity" type="radio" class="star box-ratings" value="2" title="Difficult to understand (e.g. had to ask him/her to repeat often).">
										<input name="clarity" type="radio" class="star box-ratings" value="3" title="Understood him/her 50% of the time. (e.g. sometimes had to ask him/her to repeat).">
										<input name="clarity" type="radio" class="star box-ratings" value="4" title="Pretty clear. Occasional mispronunciations/typos, but nothing too bad.">
										<input name="clarity" type="radio" class="star box-ratings" value="5" title="Crystal clear communication. No troubles at all.">
									</div>
									<span class="review-detailed-rating-names">Clarity (<span class="same-page-links no-pointer" title="<?= $tips['clarity'] ?>">?</span>)</span>
								</div>
							</div>
						</div>
						<div class="review-thread-conts">
							<ul class="review-threads">
								<li class="review-thread-items">
									<div class="review-thread-item-content">
											<div class="form-elements">
												<?= form_label('Describe your experience with this tutor <span class="debold">(<span class="same-page-links no-pointer" title="'.$tips['review-content'].'">?</span>)</span>', NULL, array('class' => 'block-labels')) ?>						
												<div class="form-inputs block-inputs">
													<?= form_textarea($review_textarea); ?>
													<div class="form-input-notes error-messages"></div>
												</div>
						
												<div class="statistics-error-messages">
													<div class="form-input-notes error-messages" data-input-name="rating"></div>
													<div class="form-input-notes error-messages" data-input-name="expertise"></div>
													<div class="form-input-notes error-messages" data-input-name="helpfulness"></div>
													<div class="form-input-notes error-messages" data-input-name="response"></div>
													<div class="form-input-notes error-messages" data-input-name="clarity"></div>
												</div>
											</div>
											<?= $element_submit_container ?>
									</div>
								</li>
							</ul>
						</div>
						<?= form_close() ?>
					</div>
				</td>
				<td></td>
			</tr>
		</table>

	</div>

</section>

<script>

var showHidden = <?= ($show_hidden ? 'true' : 'false') ?>;

function removeRow($row)
{
	var $count = $('#contact-count'),
		count = +$count.text();

	$row.add($row.next('.temp-rows')).fadeOut(<?= FAST_FADE_SPEED ?>);
	
	count -=1;
	$count.text(count);

	if (count == 0)
	{
		$('#tutors-table, #count-tutors-heading').slideUp(function()
		{
			showDefaultIfEmpty();
		});
	}
}

function cancelTutor($row)
{
	var workedWith = $row.attr('data-worked-with'),
		id = $row.attr('data-id');

	if (workedWith)
	{
		pastenTutor($row);
	}
	else
	{
		$.ajax(
			{
				type: "POST",
				url: "<?= base_url('account/update_students_tutor_status') ?>",
				data: {
					tutor_id: id,
					status: <?= STUDENT_STATUS_TEMP ?>
				},
				dataType: 'json'
			});
		removeRow($row);
	}
}

function hideTutor($row)
{
	var id = $row.attr('data-id');

	$('#contacts-header').slideDown(<?= FAST_FADE_SPEED ?>);

	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/show_hide_tutor') ?>",
		data: {
			'id' : id,
			'action': 'hide'
		},
		dataType: 'json'
	});

	if (showHidden)
	{
		$row.find('.actions-cells .action-hide')
			.removeClass('action-hide').addClass('action-show')
			.attr('title', 'Make this tutor visible when normally visiting this page')
			.text('Show')
			.unbind('click')
			.click(function()
			{
				showTutor($row);
			});		
	}
	else
	{
		removeRow($row);		
	}
}

function showTutor($row)
{
	var id = $row.attr('data-id');

	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/show_hide_tutor') ?>",
		data: {
			'id' : id,
			'action': 'show'
		},
		dataType: 'json'
	});

	$row.find('.actions-cells .action-show')
		.removeClass('action-show').addClass('action-hide')
		.attr('title', 'Hide this tutor from this list')
		.text('Hide')
		.unbind('click')
		.click(function()
		{
			hideTutor($row);
		});
}

function requestTutor($row)
{
	var id = $row.attr('data-id');

	// When we request, we auto-show
	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/show_hide_tutor') ?>",
		data: {
			'id' : id,
			'action': 'show'
		},
		dataType: 'json'
	});

	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/update_students_tutor_status') ?>",
		data: {
			tutor_id: id,
			status: <?= STUDENT_STATUS_PENDING ?>
		},
		dataType: 'json'
	});

	$row.removeClass('pending active past', <?= FAST_FADE_SPEED ?>).addClass('pending', <?= FAST_FADE_SPEED ?>)
		.find('.status-cells').html('<span title="This tutor has not accepted you as a student yet">Pending</span>').end()
		.find('.actions-cells').html('<span class="action-buttons action-cancel" title="Cancel your request for this tutor">Cancel</span>')
			.find('.action-cancel').click(function()
			{
				cancelTutor($row);
			});
}

function pastenTutor($row)
{
	var id = $row.attr('data-id');

	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/update_students_tutor_status') ?>",
		data: {
			tutor_id: id,
			status: <?= STUDENT_STATUS_PAST ?>
		},
		dataType: 'json'
	});

	$row.removeClass('pending active past', <?= FAST_FADE_SPEED ?>).addClass('past', <?= FAST_FADE_SPEED ?>)
		.find('.status-cells').html('<span title="You\'ve worked with this tutor in the past">Past</span>').end()
		.find('.actions-cells').html('<span class="action-buttons action-hide" title="Hide this tutor from this list">Hide</span> | <span class="action-buttons action-request" title="Inform this tutor that you want to be their student again">Request</span>')
			.find('.action-hide').click(function()
			{
				hideTutor($row);
			}).end()
			.find('.action-request').click(function()
			{
				requestTutor($row);
			});
}

$(function()
{
	$('.action-remove').click(function()
	{
		if (confirm('Finished studying with this tutor?'))
		{
			pastenTutor($(this).parents('tr'));
		}
	});

	$('.action-cancel').click(function()
	{
		cancelTutor($(this).parents('tr'));
	});

	$('.action-hide').click(function()
	{
		hideTutor($(this).parents('tr'));
	});

	$('.action-show').click(function()
	{
		showTutor($(this).parents('tr'));
	});

	$('.action-request').click(function()
	{
		requestTutor($(this).parents('tr'));
	});

	$('.favourited-tutors').each(function()
	{
		var $tutor = $(this),
			id = $tutor.attr('data-id'),
			$loader = $tutor.find('.ajax-loaders');

		$tutor.find('.hide-buttons').click(function()
		{
			var data = {
				tutor_id : id,
				favourite : 0
			},
			$count = $('#favourited-count'),
			count = +$count.text();

			$loader.show();

			$.ajax(
			{
				url: "<?= base_url('account/update_favourites') ?>",
				type: "POST",
				data: data,
				dataType: 'json'
			}).done(function(response)
			{
				if (response.success == true)
				{
					noty(
					{
						text: "<b>Unfavourited!</b>",
						type: 'success',
						timeout: 1500
					});

					$tutor.fadeOut();
					
					count -=1;
					$count.text(count);

					if (count == 0)
					{
						$('#favourited-tutor-cont-cont').slideUp(function()
						{
							showDefaultIfEmpty();
						});
					}
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
			});
		})
	});

	$('.notes').focus(function()
	{
		var $this = $(this);
		$this.siblings('.submit-conts').show();
	});

	$('.notes-content-cells .save-cancel-buttons').click(function()
	{
		var $this = $(this),
			$submitConts = $this.parent('.submit-conts'),
			$notes = $submitConts.siblings('.notes'),
			originalNotes = $notes.attr('data-original');

		$submitConts.hide();

		if ($this.hasClass('save-buttons'))
		{
			var $tempRow = $submitConts.parents('.temp-rows'),
				$row = $tempRow.prev('.contact-rows'),
				$overlay = $tempRow.find('.ajax-overlays'),
				contactId = $row.attr('data-contact-id'),
				notes = $notes.val();
			
			$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);
			
			$.ajax(
			{
				type: "POST",
				url: "<?= base_url('account/save_tutor_notes') ?>",
				data: {
					'tutor-id' : contactId,
					'tutor-notes' : notes
				},
				dataType: 'json'
			}).done(function(response) 
			{
				// // console.log(response);

				if (response.success == true)
				{
					$notes.attr('data-original', notes);
					$row.attr('data-notes', notes);

					noty(
					{
						text: "<b>Changes saved!</b>",
						type: 'success',
						timeout: 1500
					});
				}
				else
				{
					ajaxFailNoty();
				}
			}).always(function()
			{
				$overlay.hide();
			}).fail(function()
			{
				ajaxFailNoty();
			});
		}
		else
		{
			$notes.val(originalNotes);
		}

		return false;
	});

	$('.review-cells input[name=rating]').rating();

	$('.user-reviews .remove-item-links').click(function()
	{
		var $form = $(this).parents('.user-reviews').find('form'),
			$overlay = $form.find('.ajax-overlays');

		if (confirm('Are you sure you want to remove this review?'))
		{
			$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

			$.ajax(
			{
				type: "POST",
				url: "<?= base_url('account/delete_review') ?>",
				data: { 'tutor-id' : $form.find('[name=tutor-id]').val() },
				dataType: 'json'
			}).done(function(response) 
			{
				// // console.log(response);

				if (response.success == true)
				{
					var $row = $form.parents('.review-rows').prev('.active-rows'),
						$reviewCell = $row.find('.review-cells'),
						$starRating = $reviewCell.find('.review-displays form');

					$starRating.add($form).empty();

					resetRowAndRemoveTemp($row);

					$reviewCell.find('.show-links').html('Write');

					noty(
					{
						text: "<b>Review removed!</b>",
						type: 'success',
						timeout: 1500
					});

				}
				else if (response.status == <?= STATUS_DATABASE_ERROR ?> || response.status == <?= STATUS_UNKNOWN_ERROR ?>)
				{
					ajaxFailNoty();
				}
			}).always(function()
			{
				$overlay.hide();
			}).fail(function()
			{
				ajaxFailNoty();
			});
		}

		return false;
	});

	$('.user-reviews form').submit(function()
	{
		var $form = $(this);
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		// // console.log($form.serialize());

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('account/update_review') ?>",
			data: $form.serialize(),
			dataType: 'json'
		}).done(function(response) 
		{
			// // console.log(response);

			$form.validate(response.errors);

			if (response.success == true)
			{
				var $rating = $form.find('.review-rating').clone(),
					$row = $form.parents('.review-rows').prev('.active-rows'),
					$reviewCell = $row.find('.review-cells'),
					$reviewForm = $reviewCell.find('.review-displays form');

				// Remove unnecessaries from $rating. This step is necessary because I've no more brains to rack about how to make the star plugin work with rows moving tables
				$rating.find('#rating-tip, .rating-cancel').remove();
				$rating.find('.star-rating').addClass('star-rating-readonly').find('a').removeAttr('title');

				$reviewForm.html($rating);
				
				// // console.log($reviewCell);

				resetRowAndRemoveTemp($row);
				$reviewCell.find('.show-links').html('Change');

				noty(
				{
					text: "<b>Review saved!</b>",
					type: 'success',
					timeout: 1500
				});

			}
			else if (response.status == <?= STATUS_DATABASE_ERROR ?> || response.status == <?= STATUS_UNKNOWN_ERROR ?>)
			{
				ajaxFailNoty();
			}
		}).always(function()
		{
			$overlay.hide();
		}).fail(function()
		{
			ajaxFailNoty();
		});

		return false;
	});

	$('.show-links').click(function() 
	{
		var $this = $(this),
			$row,
			$parentRow = $this.parents('.contact-rows'),
			$parentCell = $this.parents('td'),
			templateSelector = '.'+$this.attr('data-template-row-class'),
			switchContent = $this.attr('data-switch-content'),
			originalToggled = $parentCell.attr('data-toggled');

		resetRowAndRemoveTemp($parentRow);

		//if cell was originally toggled, then now nothing is toggled, so we deactivate the row
		if (originalToggled == 'true')
		{
			$parentRow.removeClass('active-rows');
		}
		// Toggled would be undefined after the children loop above, so we check if the cached variable is undefined; if so, then we activate it
		else
		{
			$this.attr('data-temp-content', $this.html());
			$this.html(switchContent);
			$this.attr('data-switch-content', $this.attr('data-temp-content'));

			$parentCell.attr('data-toggled', true);			

			$row = $(templateSelector, '#templates-table').clone(true);

			$parentCell.addClass('active');
			$parentRow.addClass('active-rows');
			$parentRow.after($row);

			if (templateSelector == '.notes-rows')
			{
				var message = $parentRow.attr('data-contact-message'),
					notes = $parentRow.attr('data-notes');

				if (message)
				{
					$row.find('.contact-message').html(message);
				}

				if (notes)
				{
					$row.find('.notes').val(notes).attr('data-original', notes);
				}
				$row.find('.notes').autosize();
			}
			else if (templateSelector == '.review-rows')
			{
				var review = $.parseJSON($parentRow.attr('data-review'));

				if (!review.rating)
				{
					$row.find('.remove-item-links').remove();
				}

				$row.find('[name=tutor-id]').val($parentRow.attr('data-contact-id'));
				$row.find('[name=rating]').filter('[value="'+review.rating+'"]').prop('checked', true);
				$row.find('[name=expertise]').filter('[value='+review.expertise+']').prop('checked', true);
				$row.find('[name=helpfulness]').filter('[value='+review.helpfulness+']').prop('checked', true);
				$row.find('[name=response]').filter('[value='+review.response+']').prop('checked', true);
				$row.find('[name=clarity]').filter('[value='+review.clarity+']').prop('checked', true);
				$row.find('[name=<?= $review_textarea["name"] ?>]').val(review.content);

				$row.find('input[type=radio].star').rating();
			}
		}
	});


});

function resetRowAndRemoveTemp($row)
{
	$row.children().each(function()
	{
		resetCell($(this));
	});
	$row.removeClass('active-rows').next('.temp-rows').remove();
}

function resetCell($td)
{
	// Fina all toggled and active cells
	if ($td.hasClass('active') && $td.attr('data-toggled') == 'true')
	{
		var $showLink = $td.find('.show-links'),
			selector = '.'+$showLink.attr('data-template-row-class'),
			tdSwitchContent = $showLink.attr('data-switch-content');

		// Change all active text to inactive text
		$showLink.attr('data-temp-content', $showLink.html());
		$showLink.html(tdSwitchContent);
		$showLink.attr('data-switch-content', $showLink.attr('data-temp-content'));

		// Remove toggled and active status
		$td.removeAttr('data-toggled');
		$td.removeClass('active');

		// // console.log(selector);

		// For review cells, stringify and save the review
		if (selector == '.review-rows')
		{
			var $parentRow = $td.parents('.contact-rows'),
				$row = $parentRow.next('.review-rows');

			var review = 
			{
				'rating': $row.find('[name=rating]:checked').val(),
				'expertise': $row.find('[name=expertise]:checked').val(),
				'helpfulness': $row.find('[name=helpfulness]:checked').val(),
				'response': $row.find('[name=response]:checked').val(),
				'clarity': $row.find('[name=clarity]:checked').val(),
				'content': $row.find('[name=<?= $review_textarea["name"] ?>]').val()
			};

			$parentRow.attr('data-review', JSON.stringify(review));
		}

	}
}

function showDefaultIfEmpty()
{
//	log('logged');
	var show = true;
	$('.counts').each(function()
	{
//		log($(this).text());
		if ($(this).text() != '0')
			show = false;
	});

	if (show)
	{
		$('.default-page-text, #no-count-tutors-heading').slideDown(<?= FAST_FADE_SPEED ?>);
	}
}

</script>	