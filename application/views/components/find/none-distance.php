<section id="text-regular" class="cf pages containers">
	<div class="boxes no-results-found-box" id="no-<?= $groups ?>-found-box">
	<? if ($groups == 'tutors'): ?>
		No distance <span class="find-editables"><?= $readable_subject ?> tutors found</span>.
		<br>
		<b>Make a free Tutor Request and we'll find tutors for you.</b>
	<? else: ?>
		No distance <span class="find-editables"><?= $readable_subject ?></span> requests found</span>.
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
