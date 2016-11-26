<?= $header ?>

<p>A tutor has applied to your <?= anchor("requests/$request_id", "$subject tutor request", 'style="color: #4A85DD"') ?>!</p>
	<div style="float: left; border-top:2px solid #ccc; border-bottom:2px solid #ccc; padding: 10px 0; margin-bottom: 1.35em;">

		<div style="float: left; line-height: 1.6; margin-bottom: 10px;">
			<b><?= $tutor_name ?></b> (<?= anchor($tutor_profile_path, 'see profile', 'style="color: #4A85DD;"') ?>)<br>
			<?= mailto($tutor_email, $tutor_email, 'style="color: #4A85DD"') ?> 
		</div>
		<div style="clear: both;"></div>

		<div style="clear: both; line-height:1.6; border-top: 1px solid #e6e7e7; padding-top: 10px;">
			<div style="font-weight: bold; margin-bottom: 10px;">
				<span><?= $currency_sign.$price ?> / hour (<?= $currency ?>)</span>
			</div>
			<?= nl2br($application_message) ?>
		</div>
	</div>
	<div style="clear: both;"></div>
<p style="line-height:1.6; margin: 0;">We recommend you:</p>
<ol style="line-height:1.6;">
	<li><b>Review their profile</b> to find out more about them</li>
	<li><b>Reply to this email</b> to ask them any questions</li>
	<li><b><?= anchor("requests/$request_id", 'Visit your request', 'style="color: #4A85DD;"') ?></b> to accept/reject this application</li>
</ol>

<?= $footer ?>