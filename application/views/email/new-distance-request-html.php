<?= $header ?>

<h2 style="<?= $heading_style ?>">New <?= $subject ?> distance request!</h2>
<br>
<div style="float: left; border-top:2px solid #ccc; border-bottom:2px solid #ccc; padding: 10px 0; margin-bottom: 1.35em;">
	<div style="float: left; line-height: 1.6; margin-bottom: 10px;">
		<b><?= anchor("requests/$request_id", "Request: $subject") ?></b><br>
		<span style="font-style: italic; color: #888;">Student: <?= $student_name ?> (<?= anchor($student_profile_path, 'see profile') ?>)</span>
	</div>
	<div style="clear: both;"></div>

	<div style="clear: both; line-height:1.6; border-top: 1px solid #e6e7e7; padding-top: 10px;">
		<?= nl2br($details) ?>
	</div>
</div>
<div style="clear: both;"></div>
<p style="line-height:1.6; margin: 0;">We recommend you <?= anchor("requests/$request_id", "read the tutor request") ?> and apply if you're interested.</p>

<?= $footer ?>