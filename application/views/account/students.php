<?

//	var_dump($user_id);

$decline = array(
	'name'	=> 'message',
	'id' => 'decline-reason',
	'placeholder' => "Tell us what's wrong here..."
);

$decline_button = array(
	'value' => 'Decline', 
	'class' => 'buttons color-3-buttons',
);

?>
<!--
<div id="dropdown-decline" data-additional-height="30" class="dropdown dropdown-tip dropdown-anchor-right dropdown">
	<div class="ajax-overlays">
		<div class="ajax-overlays-bg"></div>
		<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
	</div>
	<div class="boxes dropdown-panel">
		<div class="form-elements">
			<form>
				<input type="hidden" name="id" id="student-id">
			
				<?= form_label('Reason for Declining', $decline['id'], array('class' => 'block-labels')); ?>
				<div class="form-inputs block-inputs">
					<?= form_textarea($decline); ?>
					<div class="form-input-notes error-messages"></div>
				</div>
				<div class="right-aligned submit-conts">
					<?= form_submit($decline_button); ?>
				</div>
			</form>
		</div>
	</div>
</div>
-->
<section id="account" class="cf pages containers">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

	<div id="students-content" class="account-subpage-conts">

	<div id="contacts-header" style="<? if (!$has_hidden) { echo 'display: none;'; } ?>">
		<? if ($show_hidden): ?>
			<a href="<?= base_url('account/students') ?>" title="Hide previously hidden students">Hide hidden</a>
		<? else: ?>
			<a href="<?= base_url('account/students') ?>/?hidden=show" title="Show previously hidden students">Show hidden</a>
		<? endif; ?>
	</div>

<div class="default-page-text <?= ($contact_count ? 'hidden' : '') ?>" id="" style="">
	<h2 class="table-headings" id="no-count-heading">
		Your Students
	</h2>
	
	<p>Students that have contacted you through your profile or accepted your application to their tutor request appear here.</p>	
</div>

<div id="contacts-cont" class="contacts-table-conts <?= ($contact_count ? '' : 'hidden') ?>">

	<h2 class="table-headings <?= ($contact_count ? '' : 'hidden') ?>" id="count-heading">
		Your Students (<span class="counts" id="contact-count"><?= $contact_count ?></span>)
	</h2>

	<table class="contact-tables" id="students-table">
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
//				var_dump($contact);
			?>
			<tr data-id="<?= $contact['id'] ?>" class="contact-rows <?= $contact['type'] ?>" data-type="request" data-contact-id="<?= $contact['id'] ?>" data-contact-message="<?= nl2br(htmlspecialchars($contact['message'])) ?>" data-notes="<?= htmlspecialchars($contact['student_notes']) ?>" data-review="<?= htmlspecialchars($contact['review']) ?>" data-worked-with="<?= $worked_with ?>">
				<td class="status-cells">
					<? if ($contact['type'] == 'pending'): ?>
						<span title="This student contacted you through your profile">Pending</span>
					<? elseif ($contact['type'] == 'active'): ?>
						<span title="You're currently working with this student">Active</span>
					<? else: ?>
						<span title="You've worked with this student in the past">Past</span>
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
					<?= ($contact['email'] ? mailto($contact['email'], $contact['email'], 'title="'.$contact['email'].'"') : '') ?>
				</td>
				<td class="notes-cells">
					<div class="show-link-conts">
						 <span class="same-page-links show-links" data-template-row-class="notes-rows" data-switch-content="Hide">Show</span>
					</div>
				</td>
				<td class="review-cells">
				<? if ($rating): ?>
					<div class="review-displays">
						<form>
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
				<? else: ?>
					<div class="review-displays">
						<form>
						</form>
					</div>
					 <span title="This person hasn't posted a review of you yet." class="same-page-links no-pointer">n/a</span>
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
						<span class="action-buttons action-accept" title="Accept this student">Accept</span> | <span class="action-buttons action-decline" title="Decline this student">Decline</span>
					<? elseif ($contact['type'] == 'active'): ?>
						<span class="action-buttons action-remove" title="Finish working with this student">Remove</span>
					<? else: ?>
						<? if ($contact['hidden']): ?>
						<span class="action-buttons action-show" title="Make this student visible when normally visiting this page">Show</span>
						<? else: ?>
						<span class="action-buttons action-hide" title="Hide this student from this list">Hide</span>
						<? endif; ?>
					<? endif; ?>
				</td>
			</tr>
			<? endforeach; ?>
		</tbody>
	</table>

</div>
		<table id="templates-table">
			<tr class="notes-rows temp-rows">
				<td></td>
				<td colspan="5" class="notes-content-cells active">
					<div class="ajax-overlays">
						<div class="ajax-overlays-bg"></div>
						<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
					</div>

					<h4>Student's initial message:</h4>
					<div class="contact-message">
						<span class='no-message'>(No message provided)</span>
					</div>
					
					<h4>Your notes <span class="debold">(<span class="same-page-links no-pointer" title="Click text to edit. These notes are private; your student cannot see them.">?</span>)</span>:</h4>
					<textarea class="notes" placeholder="e.g. phone, address, session notes"></textarea>

					<div class="submit-conts">
						<a href="javascript:void(0);" class="same-page-links save-cancel-buttons save-buttons">Save Changes</a> | 
						<a href="javascript:void(0);" class="same-page-links save-cancel-buttons cancel-buttons danger-page-links">Cancel</a>
					</div>
				</td>
				<td colspan="1"></td>
			</tr>
		</table>

		<?// $pending_table ?>
		<?// $current_table ?>
		<?// $past_table ?>

	</div>

</section>

<script>

var showHidden = <?= ($show_hidden ? 'true' : 'false') ?>,
	contactType = 'student';

function removeRow($row)
{
	var $count = $('#contact-count'),
		count = +$count.text();

	$row.add($row.next('.temp-rows')).fadeOut(<?= FAST_FADE_SPEED ?>);
	
	count -=1;
	$count.text(count);

	if (count == 0)
	{
		$('#students-table, #count-heading').slideUp(function()
		{
			showDefaultIfEmpty();
		});
	}
}

function declineContact($row)
{
	if (confirm('Decline this '+contactType+'?'))
	{
		cancelContact($row);
	}
}

function acceptContact($row)
{
	var id = $row.attr('data-id');

	if (confirm('Accept this '+contactType+'?'))
	{
		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('account/update_tutors_student_status') ?>",
			data: {
				student_id: id,
				status: <?= STUDENT_STATUS_ACTIVE ?>
			},
			dataType: 'json'
		});

		$row.removeClass('pending active past', <?= FAST_FADE_SPEED ?>).addClass('active', <?= FAST_FADE_SPEED ?>)
			.find('.status-cells').html('<span title="You\'re currently working with this student">Active</span>').end()
			.find('.actions-cells').html('<span class="action-buttons action-remove" title="Finish working with this student">Remove</span>')
				.find('.action-remove').click(function()
				{
					pastenContact($row);
				});
	}
}

function cancelContact($row)
{
	var workedWith = $row.attr('data-worked-with'),
		id = $row.attr('data-id');

	if (workedWith)
	{
		pastenContact($row);
	}
	else
	{
		$.ajax(
			{
				type: "POST",
				url: "<?= base_url('account/update_tutors_student_status') ?>",
				data: {
					student_id: id,
					status: <?= STUDENT_STATUS_TEMP ?>
				},
				dataType: 'json'
			});
		removeRow($row);
	}
}

function hideContact($row)
{
	var id = $row.attr('data-id');

	$('#contacts-header').slideDown(<?= FAST_FADE_SPEED ?>);

	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/show_hide_student') ?>",
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
			.attr('title', 'Make this '+contactType+' visible when normally visiting this page')
			.text('Show')
			.unbind('click')
			.click(function()
			{
				showContact($row);
			});		
	}
	else
	{
		removeRow($row);		
	}
}

function showContact($row)
{
	var id = $row.attr('data-id');

	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/show_hide_student') ?>",
		data: {
			'id' : id,
			'action': 'show'
		},
		dataType: 'json'
	});

	$row.find('.actions-cells .action-show')
		.removeClass('action-show').addClass('action-hide')
		.attr('title', 'Hide this '+contactType+' from this list')
		.text('Hide')
		.unbind('click')
		.click(function()
		{
			hideContact($row);
		});
}

function removeContact($row)
{
	if (confirm("Are you sure you're done tutoring this student? To tutor them again, ask them to request you through their 'Tutors' page."))
	{
		pastenContact($row);
	}
}

function pastenContact($row)
{
	var id = $row.attr('data-id');

	$.ajax(
	{
		type: "POST",
		url: "<?= base_url('account/update_tutors_student_status') ?>",
		data: {
			student_id: id,
			status: <?= STUDENT_STATUS_PAST ?>
		},
		dataType: 'json'
	});

	$row.removeClass('pending active past', <?= FAST_FADE_SPEED ?>).addClass('past', <?= FAST_FADE_SPEED ?>);

	$row.find('.status-cells').html('<span title="You\'ve worked with this student in the past">Past</span>').end()
		.find('.actions-cells').html('<span class="action-buttons action-hide" title="Hide this student from this list">Hide</span>');

	$row.find('.action-hide').click(function()
	{
		hideContact($row);
	});
}

$(function()
{
/*	
	$('body').prepend($('#dropdown-decline'));

	$('#dropdown-decline').on('show', function()
	{
		var $this = $(this);
		$this.find('textarea').focus().val('');

		scrollAndFocus($this);
	});
*/
	$('.action-remove').click(function()
	{
		removeContact($(this).parents('tr'));
	});

	$('.action-accept').click(function()
	{
		acceptContact($(this).parents('tr'));
	});

	$('.action-decline').click(function()
	{
		declineContact($(this).parents('tr'));
	});

	$('.action-hide').click(function()
	{
		hideContact($(this).parents('tr'));
	});

	$('.action-show').click(function()
	{
		showContact($(this).parents('tr'));
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
				url: "<?= base_url('account/save_student_notes') ?>",
				data: {
					'student-id' : contactId,
					'student-notes' : notes
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
		}
	});

//			log(review.rating, $row.find('[name=rating]').filter('[value='+review.rating+']').length);
/*
	$('.status-change-links').click(function()
	{
		var $this = $(this),
			destinationSelector = '#'+$this.attr('data-destination-id'),
			$row = $this.parents('tr');

		if (destinationSelector == '#the-twisting-nether')
		{
			if (confirm('Are you sure you want to decline this student?'))
			{
				resetRowAndRemoveTemp($row);
		        moveToTable(destinationSelector, $row);
			}
		}
		else
		{
			resetRowAndRemoveTemp($row);
	        moveToTable(destinationSelector, $row);     			
		}
     
    });
 */
        /*     var $tables =
$('.contact-tables').dataTable(     {         'bPaginate': false,
'bLengthChange': false,         'bInfo': false,         'bSort': false,
'asStripeClasses': []     });
	
	$("#tables-search").keyup(function() {
		// Filter on the column (the index) of this element
		$tables.fnFilterAll(this.value);
	});
*/
});

function resetRowAndRemoveTemp($row)
{
	$row.children().each(function()
	{
		resetCell($(this));
	});
	$row.next('.temp-rows').remove();
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

	}
}

function addToTable(tableSelector, $row)
{
	var $table = $(tableSelector),
		rowType = $row.attr('data-row-type'),
		$count = $('#'+rowType+'-count'),
		count = +$count.text(),
		$tableCont = $('#'+rowType+'-table-cont');

	$count.text(+$count.text()+1);
	$table.append($row);
	$row.fadeIn(<?= FAST_FADE_SPEED ?>, function()
	{
		$(this).removeClass('template-rows');
	});

	if (count == 0)
	{
		$tableCont.slideDown(300, function()
		{
		});
	}
}

function removeFromTable($row)
{
	var rowType = $row.attr('data-row-type'),
		$table = $('#'+rowType+'-table'),
		$count = $('#'+rowType+'-count'),
		count = +$count.text(),
		$tableCont = $('#'+rowType+'-table-cont');

	if (count == 1)		// Means after deletion table will be empty
	{
		$tableCont.slideUp(300, function()
		{
			$count.text(count-1);
			$row.remove();

			showDefaultIfEmpty();
		});
	}
	else
	{
		$count.text(count-1);
		$row.fadeOut(<?= FAST_FADE_SPEED ?>, function()
		{
			$(this).remove();
		});

		showDefaultIfEmpty();
	}

}


function fillContactRow($old, $new)
{
	$new.find('.contacted-cells').html($old.find('.contacted-cells').html());
	$new.find('.name-cells').html($old.find('.name-cells').html());
	$new.find('.email-cells').html($old.find('.email-cells').html());
	$new.attr('data-contact-id', $old.attr('data-contact-id'));
	$new.attr('data-contact-message', $old.attr('data-contact-message'));
	$new.attr('data-notes', $old.attr('data-notes'));

	$new.find('.review-cells').html($old.find('.review-cells').children().clone(true));

	$new.attr('data-review', $old.attr('data-review'));
}

function showDefaultIfEmpty()
{
	log('logged');
	var show = true;
	$('.counts').each(function()
	{
		log($(this).text());
		if ($(this).text() != '0')
			show = false;
	});

	if (show)
	{
		$('.default-page-text').slideDown(<?= FAST_FADE_SPEED ?>);
	}
}

</script>	