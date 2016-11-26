<?= $header ?>

<p>A new student has contacted you through your <?= anchor('', 'Tutorical', 'style="color: #4A85DD"') ?> profile!</p>
	<div style="float: left; border-top:2px solid #ccc; border-bottom:2px solid #ccc; padding: 10px 0; margin-bottom: 1.35em;">
		<div style="float: left; line-height: 1.6; margin-bottom: 10px;">
			<b><?= $student_name ?></b> (<?= anchor($student_profile_path, 'see profile', 'style="color: #4A85DD;"') ?>)<br>
			<?= mailto($student_email, $student_email, 'style="color: #4A85DD"') ?> 
		</div>
		<div style="clear: both;"></div>

		<div style="clear: both; line-height:1.6; border-top: 1px solid #e6e7e7; padding-top: 10px;">
			<?= nl2br($message) ?>
		</div>
	</div>
	<div style="clear: both;"></div>
<p style="line-height:1.6; margin: 0;">We recommend you:</p>
<ol style="line-height:1.6;">
	<li><b>Reply to this email</b> to contact them directly</li>
	<li><b>Visit the <?= anchor('account/students', 'Students page', 'style="color: #4A85DD;"') ?></b> in your Account to accept/deny them</li>
</ol>

<?= $footer ?>