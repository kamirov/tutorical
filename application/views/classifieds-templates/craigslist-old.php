<?// var_dump($tutor); 
	$left_cell_width = '30%';
	$right_cell_width = '60%';
	$valign = 'top';
?>

<div style="font-family: Arial, sans-serif; line-height: 1.6;">

<table width="100%" align="center">
	<tr>
		<td align="center">
			<a target="_blank" href="<?= $tutor['profile_link'] ?>">
				<img src="<?= $tutor['avatar_url'] ?>" width="80" style="
				border: 1px solid #B5B5B5;	
				-webkit-border-radius: 2px;
				-webkit-background-clip: padding-box;
				-moz-border-radius: 2px;
				-moz-background-clip: padding;
				border-radius: 2px;
				background-clip: padding-box;">
			</a>
		</td>
	</tr>
	<tr>
		<td align="center">
			<font size="4">
				<a target="_blank" href="<?= $tutor['profile_link'] ?>">
					<?= $tutor['display_name'] ?>
				</a>
			</font>
		</td>
	</tr>
	<tr>
		<td align="center">
			<font size="2">
				<? if ($tutor['main_subject']['name']) echo $tutor['main_subject']['name'].' '; ?>
				Tutor in <?= $tutor['city'] ?>, <?= $tutor['country'] ?>
			</font>
		</td>
	</tr>
</table>
<br>
<table width="100%" height="100%" cellspacing="0">
<tbody>
<tr>
	<td>
	</td>
	<td align="center" width="75%">
		<table width="100%" height="100%" cellspacing="0">
		<tbody>
		  <tr>
		    <td valign="<?= $valign ?>" width="<?= $left_cell_width ?>" style="border-bottom: 1px solid #BBBBBB; border-top: 1px solid #BBBBBB; padding: 10px 0;">
		    	<font size="3"><b>Subjects</b></font>
		    </td>
		    <td valign="<?= $valign ?>"  style="border-bottom: 1px solid #BBBBBB; border-top: 1px solid #BBBBBB; padding: 10px 0;">
				<?= $tutor['subjects_table'] ?>
		    </td>
		  </tr>

		  <tr>
		    <td valign="<?= $valign ?>" width="<?= $left_cell_width ?>" style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
		    	<font size="3"><b>Price</b></font>
		    </td>
		    <td valign="<?= $valign ?>"  style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
		    	<font size="2">
					<? if ($tutor['hourly_rate_high'] > 0): ?>

						<span class="hourly-prices"><?= $currency_sign.$tutor['hourly_rate'] ?> - <?= $currency_sign.$tutor['hourly_rate_high'] ?><span class="per-hour"> / hour </span><span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

					<? elseif ($tutor['price_type'] == 'per_hour'): ?>

						<span class="hourly-prices"><?= $currency_sign.$tutor['hourly_rate'] ?><span class="per-hour"> / hour </span><span class="currencies">(<?= $tutor['currency'] ?>)</span></span>

					<? elseif ($tutor['price_type'] == 'free'): ?>

							<span class="frees">Free</span>
							
							<? if ($tutor['reason']): ?>
							<span class="reason-for-frees">
								<span class="tiny-aftertext">
									(<a target="_blank" href="<?= $tutor['profile_link'] ?>">learn more</a>)
								</span>
							</span>
							<? endif; ?>

					<? endif; ?>
		    	</font>
		    </td>
		  </tr>

		<? if (!empty($tutor['education'])): ?>
		  <tr>
		    <td valign="<?= $valign ?>" width="<?= $left_cell_width ?>" style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
		    	<font size="3"><b>Education</b></font>
		    </td>
		    <td valign="<?= $valign ?>"  style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
			<? foreach($tutor['education'] as $item): ?>
				<div>
					<font size="2">
						<div style="font-weight: bold;">
							<span><?= $item['school'] ?></span>
						</div>
					</font>
					<font color="gray" size="2">
						<div>
							<? if ($item['degree'] && $item['field']): ?>
								<div>
									<span><?= $item['degree'] ?> - <?= $item['field'] ?></span>
								</div>
							<? endif; ?>
							<div style="color: #777; font-size: 13px;">
								<span><?= $item['start_year'] ?> - <?= ($item['end_year'] ?: 'Present') ?></span>
							</div>
						</div>
					</font>
				</div>

				<? 
				// Don't append a <br> to the last item
				if ($item != end($tutor['education']))
					echo '<br>';

				endforeach; 
				?>
		    </td>
		  </tr>
		<? endif; ?>

		<? if (!empty($tutor['experience'])): ?>
		  <tr>
		    <td valign="<?= $valign ?>" width="<?= $left_cell_width ?>" style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
		    	<font size="3"><b>Experience</b></font>
		    </td>
		    <td valign="<?= $valign ?>"  style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
			<? foreach($tutor['experience'] as $item): ?>
				<div>
					<font size="2">
						<div style="font-weight: bold;">
							<? if ($item['company']): ?>
								<span><?= $item['company'] ?></span>
							<? else: ?>
								<span><i>Self-Employed</i></span>
							<? endif; ?>
						</div>
					</font>
					<font color="gray" size="2">
						<div>
							<div>
								<span><?= $item['position'] ?></span>
							</div>
							<div style="color: #777; font-size: 13px;">
								<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span></span>
							</div>
						</div>
					</font>
				</div>

				<? 
				// Don't append a <br> to the last item
				if ($item != end($tutor['experience']))
					echo '<br>';

				endforeach; 
				?>
		    </td>
		  </tr>
		<? endif; ?>

		<? if (!empty($tutor['volunteering'])): ?>
		  <tr>
		    <td valign="<?= $valign ?>" width="<?= $left_cell_width ?>" style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
		    	<font size="3"><b>Volunteering</b></font>
		    </td>
		    <td valign="<?= $valign ?>"  style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
			<? foreach($tutor['volunteering'] as $item): ?>
				<div>
					<font size="2">
						<div style="font-weight: bold;">
							<? if ($item['company']): ?>
								<span><?= $item['company'] ?></span>
							<? else: ?>
								<span><em>Self-Employed</em></span>
							<? endif; ?>
						</div>
					</font>
					<font color="gray" size="2">
						<div>
							<div>
								<span><?= $item['position'] ?></span>
							</div>
							<div style="color: #777; font-size: 13px;">
								<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span></span>
							</div>
						</div>
					</font>
				</div>

				<? 
				// Don't append a <br> to the last item
				if ($item != end($tutor['volunteering']))
					echo '<br>';

				endforeach; 
				?>
		    </td>
		  </tr>
		<? endif; ?>


		<? if (!empty($tutor['about'])): ?>
		  <tr>
		    <td valign="<?= $valign ?>" width="<?= $left_cell_width ?>" style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
		   		<font size="3"><b>About</b></font>
		    </td>
		    <td valign="<?= $valign ?>"  style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
		    	<font size="2"><?= nl2br($tutor['about']) ?></font>
		  <br>
		    </td>
		  </tr>
		<? endif; ?>

		<tr>
			<td colspan="2" align="center" style="padding: 10px 0;">
				<font size="3"><b>Location</b></font>
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2" style="border-bottom: 1px solid #BBBBBB; padding: 0 0 10px 0;">
				
				<a target="_blank" href="<?= $tutor['profile_link'] ?>">
					<img src="http://maps.google.com/maps/api/staticmap?center=<?= $tutor['lat'] ?>,<?= $tutor['lon'] ?>&amp;zoom=15&amp;size=500x300&amp;markers=color:red|<?= $tutor['lat'] ?>,<?= $tutor['lon'] ?>&amp;sensor=false" style="border: 1px solid #B5B5B5;	
				-webkit-border-radius: 2px;
				-webkit-background-clip: padding-box;
				-moz-border-radius: 2px;
				-moz-background-clip: padding;
				border-radius: 2px;
				background-clip: padding-box;">
				</a>
			</td>
		</tr>

		<tr>
			<td align="center" colspan="2" style="border-bottom: 1px solid #BBBBBB; padding: 10px 0;">
				<font size="2">
					For more details, please reply to this ad or <a target="_blank" href="<?= $tutor['profile_link'] ?>">visit my Tutorical profile</a>.
				</font>
			</td>
		</tr>

		</tbody>
		</table>

	</td>
	<td>
	</td>
</tr>
</tbody>
</table>

</div>