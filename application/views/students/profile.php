<? // var_dump($user); ?>
<section class="cf pages containers profiles student-profiles">

	<div class="col-1">
		<section id="user-intro" class="profile-secs cf">
			<div id="user-avatar-cont">
				<img alt="Student's photo" id="user-avatar" src="<?= $user['avatar_url'] ?>">
			</div>
			<h1 id="user-name">
				<span>
					<span id="user-name-content"><?= $user['display_name'] ?></span>
				</span>
			</h1>
			<div id="user-left-intro-bits">
				<span id="user-joined">Joined: <span><?= $user['joined'] ?></span></span>
			</div>
		</section>

		<? if (!empty($user['requests'])): ?>
		<section id="requests-sec" class="profile-secs">
			<header>
				<h2>Tutor Requests</h2>
			</header>
			<table>
				<!--
				<thead>
					<tr>
						<th class="status-cells">Status</th>
						<th class="name-cells">Subject</th>
						<th class="email-cells">Posted</th>
					</tr>
				</thead>
				-->
				<tbody>
				<? foreach($user['requests'] as $request): ?>
						<tr class="<? if ($request['status'] == REQUEST_STATUS_CLOSED || $request['status'] == REQUEST_STATUS_EXPIRED) echo 'closed' ?>">
							<td class="status-cells">
								<a href="<?= base_url('requests/'.$request['id']) ?>">
								<? if ($request['status'] == REQUEST_STATUS_OPEN): ?>
									<span class="open-links status-change-links">Open</span>
								<? elseif ($request['status'] == REQUEST_STATUS_EXPIRED): ?>
									<span class="closed-links status-change-links">expired</span>
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
							<!--
							<td class="posted-cells">
								<a href="<?= base_url('requests/'.$request['id']) ?>">
									<?= time_elapsed_string($request['posted'], ' old') ?>
								</a>
							</td>
							-->
						</tr>
				<? endforeach; ?>
				</tbody>
			</table>

		</section>  <!-- /#requests-sec --> 
		<? endif; ?>

		<? if (!empty($user['reviews'])): ?>
		<section id="user-reviews-sec" class="profile-secs on-students">
			<header class="cf">
				<h2>Reviewed <?= $user['num_of_reviews'].' '.($user['num_of_reviews'] == 1 ? 'Tutor' : 'Tutors') ?></h2>
			</header>

			<div class="user-reviews-cont">
				<? foreach ($user['reviews'] as $review): ?>
					<div class="user-reviews">
						<form>
							<div class="review-statistics">
								<div class="review-rating">
									<?= $review['rating'] ?>
								</div>
								<div class="review-detailed-ratings-cont">
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['expertise'] ?>
										</div>
										<span class="review-detailed-rating-names">Expertise</span>
									</div>
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['helpfulness'] ?>
										</div>
										<span class="review-detailed-rating-names">Helpfulness</span>
									</div>
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['response'] ?>
										</div>
										<span class="review-detailed-rating-names">Response</span>
									</div>
									<div class="review-detailed-ratings-and-names">
										<div class="review-detailed-ratings">
											<?= $review['clarity'] ?>
										</div>
										<span class="review-detailed-rating-names">Clarity</span>
									</div>
								</div>
							</div><div class="review-thread-conts">
								<ul class="review-threads">
									<li class="review-thread-items">
										<div class="review-thread-item-text-conts">

											<div class="review-thread-item-content by-student">
												<?= nl2br($review['comment']['content']) ?>
											</div>

											<div class="review-thread-item-reviewees">
												<span class="pre-reviewees">Reviewed</span>
												<? if ($review['comment']['contact_id'] == DELETED_ID || !$review['comment']['contact_id']): ?>
													<img alt="Tutor's photo" src="<?= base_url(DELETED_AVATAR_PATH) ?>" class="review-thread-item-reviewee-avatars">
													<span class="review-thread-item-author-names deleted more-info-in-title-text" title="This student has deleted their account"><?= DELETED_NAME ?></span>
												<? else: ?>
													<img alt="Tutor's photo" src="<?= $review['comment']['avatar_url'] ?>" class="review-thread-item-reviewee-avatars">
													<a href="<?= $review['comment']['profile_link'] ?>" class="review-thread-item-author-names"><?= $review['comment']['display_name'] ?></a>
												<? endif; ?>

												<span class="review-thread-item-reviewee-posted">(<?= time_elapsed_string($review['comment']['posted'], ' ago') ?>)</span>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</form>
					</div>
				<? endforeach; ?>
			</div>
		</section>
		<? endif; ?>

		<section id="user-meta" class="profile-secs">
			<? if (empty($user['reviews']) && empty($user['requests'])): ?>
				<div class="empty-profile-notice">
					<span>This student profile is empty.</span>
				</div>
			<? endif; ?>
			
			<? if ($user['role'] != ROLE_STUDENT && $user['profile_made']): ?>
			<a title="This student is also a Tutorical tutor. Click to see their tutor profile." href="<?= base_url('tutors/'.$user['username']) ?>" class="meta-items">See Tutor Profile</a>
			<? endif; ?>
		</section>
	</div>  <!-- /.col-1 -->

	<div class="col-2 cf">

	</div>  <!-- /.col-2 -->

</section>  <!-- /#tutor-profile -->

<? if(isset($previous_page)): ?>
<div id="post-profile" class="containers">
	<?= anchor($previous_page, "&laquo; <span>back to search results</span>", 'class="arrow-link back-to-search-results-links"') ?>
</div>
<? endif; ?>


<script>
$(function() 
{

	$('input[type=radio].star').rating();
//	$('#user-name').textfill({ maxFontPixels: 26 });

});
</script>