<?
	$label_styles = 'font-size: 16px; display: block; margin-top: 30px; margin-bottom: 10px; font-weight: bold;';
	$list_styles = '';
?>

<div id="kijiji-body" style="font-family: Arial, sans-serif; margin-top: 30px; line-height: 24px; font-size: 13px; line-height: 1.6;">
	<div style="line-height: 21px;">
		<span style="font-weight: bold; font-size: 20px;"><?= $tutor['display_name'] ?></span>
		<br>
		<span style="color: #555; font-size: 13px;">
			<? if ($tutor['main_subject']['name']) echo $tutor['main_subject']['name'].' '; ?> Tutor in <?= $tutor['city'] ?>, <?= $tutor['country'] ?>
			<br>
			<span style="color: #999;"><u>tutorical.com/tutors/<?= $tutor['username'] ?></u></span>
		</span>
	</div>
	<span style="<?= $label_styles ?>">Subjects</span>
	<ul style="<?= $list_styles ?>">
		<? 
			foreach($tutor['subjects_array'] as $subject)
			{
				echo "<li>$subject</li>";
			}
		?>
	</ul>

	<span style="<?= $label_styles ?>">Price</span>
	<ul>
		<li>
	<? if ($tutor['hourly_rate_high'] > 0): ?>

		<span class="hourly-prices"><?= $currency_sign.$tutor['hourly_rate'] ?> - <?= $currency_sign.$tutor['hourly_rate_high'] ?><span class="per-hour"> / hour </span><span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

	<? elseif ($tutor['price_type'] == 'per_hour'): ?>

		<span class="hourly-prices"><?= $currency_sign.$tutor['hourly_rate'] ?><span class="per-hour"> / hour </span><span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

	<? elseif ($tutor['price_type'] == 'free'): ?>

			<span class="frees">Free</span>
			
			<? if ($tutor['reason']): ?>
			<span class="reason-for-frees">
				<span class="tiny-aftertext">
					(learn more on my profile - <i>tutorical.com/tutors/<?= $tutor['username'] ?></i>)
				</span>
			</span>
			<? endif; ?>

	<? endif; ?>
		</li>
	</ul>

	<? if (!empty($tutor['education'])): ?>
	
	<span style="<?= $label_styles ?>">Education</span>

		<ul>
		<? foreach($tutor['education'] as $item): 

			if ($item == end($tutor['education']))
				$item_list_styles = 'line-height: 18px;';
			else
				$item_list_styles = 'margin-bottom: 5px; line-height: 18px;';
		?>

			<li>
				<div style="<?= $item_list_styles ?>">
					<span style="font-weight: bold;"><?= $item['school'] ?></span>
					<br>
					<? if ($item['degree'] && $item['field']): ?>
						<span><?= $item['degree'] ?> - <?= $item['field'] ?></span>
						<br>
					<? endif; ?>
						<span style="color: #777;"><?= $item['start_year'] ?> - <?= ($item['end_year'] ?: 'Present') ?></span>
				</div>
			</li>
		<? endforeach; ?>
		</ul>
	<? endif; ?>

	<? if (!empty($tutor['experience'])): ?>
	
	<span style="<?= $label_styles ?>">Experience</span>

		<ul>
		<? foreach($tutor['experience'] as $item): 

			if ($item == end($tutor['experience']))
				$item_list_styles = 'line-height: 18px;';
			else
				$item_list_styles = 'margin-bottom: 5px; line-height: 18px;';
		?>

			<li>
				<div style="<?= $item_list_styles ?>">
					<span style="font-weight: bold;">
						<? if ($item['company']): ?>
							<span><?= $item['company'] ?></span>
						<? else: ?>
							<span><i>Self-Employed</i></span>
						<? endif; ?>
					</span>
					<br>
						<span><?= $item['position'] ?></span>
						<br>
						<span><?= $item['start_month'] ?> <?= $item['start_year'] ?> - <?= ($item['end_month'] ?: '') ?> <?= ($item['end_year'] ?: 'Present') ?> | <?= $item['location'] ?>
						</span>
				</div>
			</li>
		<? endforeach; ?>
		</ul>
	<? endif; ?>

	<? if (!empty($tutor['volunteering'])): ?>
	
	<span style="<?= $label_styles ?>">Volunteering</span>

		<ul>
		<? foreach($tutor['volunteering'] as $item): 

			if ($item == end($tutor['volunteering']))
				$item_list_styles = 'line-height: 18px;';
			else
				$item_list_styles = 'margin-bottom: 5px; line-height: 18px;';
		?>

			<li>
				<div style="<?= $item_list_styles ?>">
					<span style="font-weight: bold;">
						<? if ($item['company']): ?>
							<span><?= $item['company'] ?></span>
						<? else: ?>
							<span><i>Self-Employed</i></span>
						<? endif; ?>
					</span>
					<br>
						<span><?= $item['position'] ?></span>
						<br>
						<span><?= $item['start_month'] ?> <?= $item['start_year'] ?> - <?= ($item['end_month'] ?: '') ?> <?= ($item['end_year'] ?: 'Present') ?> | <?= $item['location'] ?>
						</span>
				</div>
			</li>
		<? endforeach; ?>
		</ul>
	<? endif; ?>

	<? if (!empty($tutor['about'])): ?>
	<span style="<?= $label_styles ?> margin-bottom: 0;">About</span>
	<p><?= nl2br($tutor['about']) ?></p>
	<? endif; ?>

	<span style="<?= $label_styles ?> margin-bottom: 0;">For More...</span>
	<p>For more details, please reply to this ad or visit my Tutorical profile - <b><i>tutorical.com/tutors/<?= $tutor['username'] ?></i></b></p>

</div>