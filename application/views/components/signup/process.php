<? 
$checkmark = '&#x2714;'; 
?>

<div id="signup-process">

	<div class="signup-steps process-divs <? if ($active == 'general') echo 'active'; ?>">
		<? if ($latest_step >= 1): ?>

			<? if ($latest_step == 1): ?>
				<span class="step-numbers">1</span>
			<? else: ?>
				<span class="step-numbers step-checks"><?= $checkmark ?></span>
			<? endif; ?>
			<? if ($active == 'general'): ?>
				<span class="step-names">General</span>
			<? else: ?>
				<span class="step-names"><?= anchor('signup/step-1', 'General') ?></span>
			<? endif; ?>
		<? else: ?>
			<span class="step-numbers">1</span>
			<span class="step-names">General</span>
		<? endif; ?>
	</div>
	<div class="step-arrows process-divs">
		<img src="<?= base_url('assets/images/signup/arrow.png') ?>">
	</div>
	<div class="signup-steps process-divs <? if ($active == 'subjects') echo 'active'; ?>">
		<? if ($latest_step >= 2): ?>

			<? if ($latest_step == 2): ?>
				<span class="step-numbers">2</span>
			<? else: ?>
				<span class="step-numbers step-checks"><?= $checkmark ?></span>
			<? endif; ?>

			<? if ($active == 'subjects'): ?>
				<span class="step-names">Subjects</span>
			<? else: ?>
				<span class="step-names"><?= anchor('signup/step-2', 'Subjects') ?></span>
			<? endif; ?>
		<? else: ?>
			<span class="step-numbers">2</span>
			<span class="step-names">Subjects</span>
		<? endif; ?>
	</div>
	<div class="step-arrows process-divs">
		<img src="<?= base_url('assets/images/signup/arrow.png') ?>">
	</div>
	<div class="signup-steps process-divs <? if ($active == 'availability') echo 'active'; ?>">
		<? if ($latest_step >= 3): ?>

			<? if ($latest_step == 3): ?>
				<span class="step-numbers">3</span>
			<? else: ?>
				<span class="step-numbers step-checks"><?= $checkmark ?></span>
			<? endif; ?>

			<? if ($active == 'availability'): ?>
				<span class="step-names">Availability</span>
			<? else: ?>
				<span class="step-names"><?= anchor('signup/step-3', 'Availability') ?></span>
			<? endif; ?>
		<? else: ?>
			<span class="step-numbers">3</span>
			<span class="step-names">Availability</span>
		<? endif; ?>
	</div>
	<div class="step-arrows process-divs">
		<span class="optionals">(optional)</span>
		<img src="<?= base_url('assets/images/signup/arrow.png') ?>">
	</div>
	<div class="signup-steps process-divs <? if ($active == 'about') echo 'active'; ?>">
		<? if ($latest_step >= 4): ?>

			<? if ($latest_step == 4): ?>
				<span class="step-numbers">4</span>
			<? else: ?>
				<span class="step-numbers step-checks"><?= $checkmark ?></span>
			<? endif; ?>

			<? if ($active == 'about'): ?>
				<span class="step-names">About</span>
			<? else: ?>
				<span class="step-names"><?= anchor('signup/step-4', 'About') ?></span>
			<? endif; ?>
		<? else: ?>
			<span class="step-numbers">4</span>
			<span class="step-names">About</span>
		<? endif; ?>
	</div>

</div>