<div class="contacts-table-conts" id="<?= $contact_type ?>-contacts-table-cont" style="<? if (empty($contacts)) echo ' display: none; '; ?>">
	<h2 class="table-headings"><?= ucfirst($contact_type) ?> Students (<span id="<?= $contact_type ?>-contacts-count" class="counts"><?= count($contacts) ?></span>)</h2>
	<table class="contact-tables" id="<?= $contact_type ?>-contacts-table">
		<thead>
			<tr>
				<th class="status-cells"></th>
				<th class="name-cells">Name</th>
				<th class="email-cells">Email</th>
				<th class="notes-cells">Notes</th>
				<th class="review-cells"><?= ($contact_type == 'pending' ? '' : 'Review') ?></th>
				<th class="contacted-cells">Contacted</th>
			</tr>
		</thead>
		<tbody>
			<tr class="contact-rows template-rows" data-row-type="<?= $contact_type ?>-contacts">
				<td class="status-cells">
					<span class="ajax-enabled">
						<? if ($contact_type == 'pending'): ?>
							<span class="accept-links status-change-links" data-destination-id="current-contacts-table">Accept</span> | <span class="ignore-links status-change-links" data-destination-id="the-twisting-nether">Decline</span>
						<? elseif ($contact_type == 'current'): ?>
							<span class="ignore-links status-change-links" data-destination-id="past-contacts-table">Remove</span>
						<? else: ?>
							<span class="accept-links status-change-links" data-destination-id="current-contacts-table">Re-tutor</span>
							<span class="ignore-links status-change-links" data-destination-id="the-twisting-nether">Delete</span>
						<? endif; ?>
					</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
				</td>
				<td class="name-cells"></td>
				<td class="email-cells"></td>
				<td class="notes-cells">
					<div class="show-link-conts">
						 <span class="same-page-links show-links" data-template-row-class="notes-rows" data-switch-content="Hide">Show</span>
					</div>
				</td>
				<td class="review-cells"></td>
				<td class="contacted-cells"></td>
			</tr>
			<? foreach ($contacts as $contact): 
		        $contacted = new DateTime($contact['contacted']);

		        $rating = json_decode($contact['review'])->rating;
			?>
			<tr class="contact-rows" data-row-type="<?= $contact_type ?>-contacts" data-contact-id="<?= $contact['id'] ?>" data-contact-message="<?= nl2br(htmlspecialchars($contact['message'])) ?>" data-notes="<?= nl2br(htmlspecialchars($contact['student_notes'])) ?>">
				<td class="status-cells">
					<span class="ajax-enabled">
						<? if ($contact_type == 'pending'): ?>
							<span class="accept-links status-change-links" data-destination-id="current-contacts-table">Accept</span> | <span class="ignore-links status-change-links" data-destination-id="the-twisting-nether">Decline</span>
						<? elseif ($contact_type == 'current'): ?>
							<span class="ignore-links status-change-links" data-destination-id="past-contacts-table">Remove</span>
						<? else: ?>
							<span class="accept-links status-change-links" data-destination-id="current-contacts-table">Re-tutor</span>
							<span class="ignore-links status-change-links" data-destination-id="the-twisting-nether">Delete</span>
						<? endif; ?>
					</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
				</td>

				<? if ($contact['contact_id'] == DELETED_ID): ?>
					<td class="name-cells"><img src="<?= $contact['avatar_url'] ?>" class="contact-avatars"> <span title="This person has deleted their account"><?= $contact['display_name'] ?></span></td>
				<? else: ?>
					<td class="name-cells"><img src="<?= $contact['avatar_url'] ?>" class="contact-avatars"> <?= anchor($contact['profile_path'], $contact['display_name']) ?></td>
				<? endif; ?>

				<td class="email-cells"><?= ($contact['email'] ? mailto($contact['email']) : '') ?></td>
				<td class="notes-cells">
					<div class="show-link-conts">
						 <span class="same-page-links show-links" data-template-row-class="notes-rows" data-switch-content="Hide">Show</span>
					</div>
				</td>
				<td class="review-cells <? if ($contact_type == 'pending') { echo 'placeholder-cells'; } ?>">
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
				<td class="contacted-cells"><?= $contacted->format('M d, Y') ?></td>
			</tr>
			<? endforeach; ?>
		</tbody>
	</table>
</div>