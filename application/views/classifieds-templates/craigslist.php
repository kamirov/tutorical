<?// var_dump($tutor); 
	$left_cell_width = '30%';
	$right_cell_width = '60%';
	$valign = 'top';

	$link = "tutorical.com/tutors/".$tutor['username'];
//	var_dump($tutor);
?>
<div class="post-body">
<h3><?= $tutor['display_name'] ?> - <? if ($tutor['main_subject']['name']) echo $tutor['main_subject']['name'].' '; ?>
				Tutor in <?= $tutor['city'] ?>, <?= $tutor['country'] ?></h3>
<h4><?= $link ?></h4>
<hr>

<h4>Subjects</h4>
<?= implode(', ', $tutor['subjects_array']); ?>

<h4>Price</h4>
	<? if ($tutor['hourly_rate_high'] > 0): ?>

		<span class="hourly-prices"><?= $currency_sign.$tutor['hourly_rate'] ?> - <?= $currency_sign.$tutor['hourly_rate_high'] ?><span class="per-hour"> / hour </span><span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

	<? elseif ($tutor['price_type'] == 'per_hour'): ?>

		<span class="hourly-prices"><?= $currency_sign.$tutor['hourly_rate'] ?><span class="per-hour"> / hour </span><span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

	<? elseif ($tutor['price_type'] == 'free'): ?>

			<span class="frees">Free</span>
			
			<? if ($tutor['reason']): ?>
			<span class="reason-for-frees">
				<span class="tiny-aftertext">
					(learn more on my profile)
				</span>
			</span>
			<? endif; ?>

	<? endif; ?>

<? if (!empty($tutor['education'])): ?>
	<h4>Education</h4>
	<ul>
	<? foreach($tutor['education'] as $item): ?>
			<li>
				<span><?= $item['school'] ?></span>
					<? if ($item['degree'] && $item['field']): ?>
							<span><?= $item['degree'] ?> - <?= $item['field'] ?></span>
					<? endif; ?>
						<span>(<?= $item['start_year'] ?> - <?= ($item['end_year'] ?: 'Present') ?>)</span>
			</li>
	<? endforeach; ?>
	</ul>
<? endif; ?>

<? if (!empty($tutor['experience'])): ?>
	<h4>Experience</h4>
	<ul>
	<? foreach($tutor['experience'] as $item): ?>
			<li>
				<? if ($item['company']): ?>
					<span><?= $item['company'] ?></span>
				<? else: ?>
					<span><i>Self-Employed</i></span>
				<? endif; ?>
				 - 
				<span><?= $item['position'] ?></span>
				(<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span>)</span>
			</li>
	<? endforeach; ?>
	</ul>
<? endif; ?>

<? if (!empty($tutor['volunteering'])): ?>
	<h4>Volunteering Work</h4>
	<ul>
	<? foreach($tutor['volunteering'] as $item): ?>
			<li>
				<? if ($item['company']): ?>
					<span><?= $item['company'] ?></span>
				<? else: ?>
					<span><i>Self-Employed</i></span>
				<? endif; ?>
				 - 
				<span><?= $item['position'] ?></span>
				(<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span>)</span>
			</li>
	<? endforeach; ?>
	</ul>
<? endif; ?>

<? if (!empty($tutor['about'])): ?>
	<h4>About</h4>
	<?= nl2br($tutor['about']) ?>
<? endif; ?>
<h4>For more...</h4>
For more details, please reply to this ad or visit my Tutorical profile: 
	<ul>
		<li><?= $link ?></li>
	</ul>
</div>