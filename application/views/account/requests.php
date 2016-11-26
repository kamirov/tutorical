<?
	$show_hidden = ($this->input->get('hidden') == 'show' ? TRUE : FALSE);
?>

<section id="account" class="cf pages containers">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

	<div id="requests-content" class="account-subpage-conts" >

		<div id="requests-header" style="<? if (!$requests['has_hidden']) { echo 'display: none;'; } ?>">
			<? if ($show_hidden): ?>
				<a href="<?= base_url('account/requests') ?>" title="Hide previously hidden requests/applications">Hide hidden</a>
			<? else: ?>
				<a href="<?= base_url('account/requests') ?>/?hidden=show" title="Show previously hidden requests/applications">Show hidden</a>
			<? endif; ?>
		</div>

		<div id="invite-sec" style="<? if (empty($requests['invited'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">You've been invited...</h2>
			<ul>
				<? foreach ($requests['invited'] as $request): ?>
					<li data-id="<?= $request['application_id'] ?>">&raquo; <?= anchor('requests/'.$request['id'], $request['subjects_string']) ?> <span class="invited-posted-by">(Posted <?= time_elapsed_string($request['posted'], ' ago') ?> by <?= anchor('students/'.$request['username'], $request['display_name']) ?>)</span> <span class="invited-hide-buttons" style="<? if ($request['hidden']) { echo 'display: none;'; } ?>" title="Hide this invite. This doesn't delete it, but removes it from this list.">&ndash;</span>
					<span class="invited-show-buttons" style="<? if (!$request['hidden']) { echo 'display: none;'; } ?>" title="This invite is hidden. Click this to show it.">&bull;</span></li>
				<? endforeach; ?>
			</ul>
		</div>

		<div class="requests-secs" style="">
			<h2 class="table-headings">
				Your Requests 
				<? 
					if (($count = count($requests['users'])) > 0)
						echo '<span class="count-conts">(<span id="users-requests-count" class="counts">'.$count.'</span>)</span>';
				?>
			</h2>

			<p class="no-requests-text" style="<? if ($count > 0) { echo 'display: none;'; } ?>">Requests that you've made will show up here. <a href="javascript:void(0);" data-reveal-id="request-modal">Make a Tutor Request</a></p>
				
			<table class="" id="" style="<? if ($count == 0) { echo 'display: none;'; } ?>">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="subjects-cells">Subject</th>
						<th class="posted-cells">Posted</th>
						<th class="hide-cells"></th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($requests['users'] as $request): ?>
					<tr data-id="<?= $request['id'] ?>" class="<? if ($request['status'] == REQUEST_STATUS_CLOSED) echo 'closed' ?>" data-type="request">
						<td class="status-cells">
							<a href="<?= base_url('requests/'.$request['id']) ?>">
								<? if ($request['status'] == REQUEST_STATUS_OPEN): ?>
									<span class="open-links status-change-links">Open</span>
								<? else: ?>
									<span class="closed-links status-change-links">Closed</span>
								<? endif; ?>
							</a>
						</td>
						<td class="subjects-cells">
							<a href="<?= base_url('requests/'.$request['id']) ?>">
								<?= $request['subjects_string'] ?>
							</a>
						</td>
						<td class="posted-cells">
							<a href="<?= base_url('requests/'.$request['id']) ?>">
								<?= date("M d, Y", $request['posted']) ?>
							</a>
						</td>
						<td class="hide-cells">
							<span class="hide-buttons" style="<? if ($request['hidden']) { echo 'display: none;'; } ?>" title="Hide this request. This doesn't delete it, but removes it from this list.">&ndash;</span>
							<span class="show-buttons" style="<? if (!$request['hidden']) { echo 'display: none;'; } ?>" title="This request is hidden. Click this to show it.">&bull;</span>
						</td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
			<div class="after-table-note-conts" style="<? if ($count == 0) { echo 'display: none;'; } ?>">
				<a href="javascript:void(0);" data-reveal-id="request-modal" class="after-table-notes">Make a Tutor Request</a>
			</div>
		</div>

		<? if ($this->session->userdata('role') != ROLE_STUDENT): ?>
		<div class="requests-secs" id="others-requests-secs" style="">
			<h2 class="table-headings">
				Your Applications 
				<? 
					if (($count = count($requests['others'])) > 0)
						echo '<span class="count-conts">(<span id="others-requests-count" class="counts">'.$count.'</span>)</span>';
				?>
			</h2>
				<p class="no-requests-text" style="<? if ($count > 0) { echo 'display: none;'; } ?>">Applications you've made will show up here. You can apply to a request by visiting a it's page and clicking "Apply to this Request".</p>
				<table class="" id="" style="<? if ($count == 0) { echo 'display: none;'; } ?>">
					<thead>
						<tr>
							<th class="status-cells"></th>
							<th class="subjects-cells">Subject</th>
							<th class="posted-cells">Posted</th>
							<th class="hide-cells"></th>
						</tr>
					</thead>
					<tbody>
						<? foreach ($requests['others'] as $request):
						 ?>
						<tr data-type="application" data-id="<?= $request['application_id'] ?>" class="<? if (($request['status'] == REQUEST_STATUS_CLOSED || $request['status'] == REQUEST_STATUS_EXPIRED) && $request['application_status'] != RESPONSE_STATUS_APPROVED) echo 'closed' ?>">
							<td class="status-cells">
								<a href="<?= base_url('requests/'.$request['id']) ?>">
									<? if ($request['application_status'] == RESPONSE_STATUS_APPROVED): ?>
										<span class="approved-links status-change-links">Approved</span>
									<? elseif ($request['status'] == REQUEST_STATUS_CLOSED): ?>
										<span class="closed-links status-change-links">Closed</span>
									<? elseif ($request['status'] == REQUEST_STATUS_EXPIRED): ?>
										<span class="closed-links status-change-links">Expired</span>
									<? elseif ($request['application_status'] == RESPONSE_STATUS_REJECTED): ?>
										<span class="rejected-links status-change-links">Rejected</span>
									<? else: ?>
										<span class="pending-links status-change-links">Pending</span>
									<? endif; ?>
								</a>
							</td>
							<td class="subjects-cells">
								<a href="<?= base_url('requests/'.$request['id']) ?>">
									<?= $request['subjects_string'] ?>
								</a>
							</td>
							<td class="posted-cells">
								<a href="<?= base_url('requests/'.$request['id']) ?>">
									<?= date("M d, Y", $request['posted']) ?>
								</a>
							</td>
							<td class="hide-cells">
								<span class="hide-buttons" style="<? if ($request['hidden']) { echo 'display: none;'; } ?>" title="Hide this application. This doesn't delete it, but removes it from this list.">&ndash;</span>
								<span class="show-buttons" style="<? if (!$request['hidden']) { echo 'display: none;'; } ?>" title="This application is hidden. Click this to show it.">&bull;</span>
							</td>
						</tr>
						<? endforeach; ?>
					</tbody>
				</table>
		</div>

		<? endif; ?>
	</div>

</section>

<script>
$(function()
{
	var showHidden = <?= ($show_hidden ? 'true' : 'false') ?>;

	$('.invited-hide-buttons').click(function()
	{
		var $this = $(this),
			$invite = $this.parents('li'),
			$sec = $invite.parents('#invite-sec'),
			type = 'application',
			id = $invite.attr('data-id'),
			itemCount = $invite.parent().children().length;
		
		$('#requests-header').slideDown(<?= FAST_FADE_SPEED ?>);

		if (!showHidden)
		{
			// if 1 item left, then there will be 0 once we've hidden it
			if (itemCount == 1)
			{
				$sec.slideUp(<?= FAST_FADE_SPEED ?>);
			}
			
			$invite.fadeOut(<?= FAST_FADE_SPEED ?>, function()
			{
				$(this).remove();
			});
		}
		else
		{
			$this.hide();
			$this.siblings('.invited-show-buttons').show();
		}

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('requests/show_hide_request_or_application') ?>",
			data: {
				'type' : type,
				'id' : id,
				'action': 'hide'
			},
			dataType: 'json'
		});

		return false;
	})

	$('.hide-buttons').click(function()
	{
		var $this = $(this),
			$row = $this.parents('tr'),
			$sec = $row.parents('.requests-secs'),
			$count = $sec.find('.counts'),
			count = +$count.text(),
			type = $row.attr('data-type'),
			id = $row.attr('data-id');

		$('#requests-header').slideDown(<?= FAST_FADE_SPEED ?>);
	
		if (!showHidden)
		{
			count--;
			$row.fadeOut(<?= FAST_FADE_SPEED ?>);

			if (count)
			{
				$count.text(count);
			}
			else
			{
				$sec.find('.no-requests-text').slideDown(<?= FAST_FADE_SPEED ?>).end()
					.find('table, .after-table-note-conts').slideUp(<?= FAST_FADE_SPEED ?>);

				$count.parent().hide();
			}
		}
		else
		{
			$this.hide();
			$this.siblings('.show-buttons').show();
		}

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('requests/show_hide_request_or_application') ?>",
			data: {
				'type' : type,
				'id' : id,
				'action': 'hide'
			},
			dataType: 'json'
		});
	});

	$('.show-buttons').click(function()
	{
		var $this = $(this),
			$row = $this.parents('tr'),
			type = $row.attr('data-type'),
			id = $row.attr('data-id');

		$this.hide();
		$this.siblings('.hide-buttons').show();

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('requests/show_hide_request_or_application') ?>",
			data: {
				'type' : type,
				'id' : id,
				'action': 'show'
			},
			dataType: 'json'
		});
	});

	$('.invited-show-buttons').click(function()
	{
		var $this = $(this),
			$invite = $this.parents('li'),
			$sec = $invite.parents('#invite-sec'),
			type = 'application',
			id = $invite.attr('data-id'),
			itemCount = $invite.parent().children().length;
		
		$this.hide();
		$this.siblings('.invited-hide-buttons').show();

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('requests/show_hide_request_or_application') ?>",
			data: {
				'type' : type,
				'id' : id,
				'action': 'show'
			},
			dataType: 'json'
		});

		return false;
	})

});
</script>