<?= $header ?>

<h2 style="<?= $heading_style ?>"><?= $student_name ?> has rejected your application :(</h2>
<br>
<p style="line-height:1.6; margin: 0;">You applied to <?= anchor($student_profile_path, $student_name) ?>'s <?= anchor("requests/$request_id", "tutor request") ?> and have been rejected. Sorry about that!</p>
<br>
	<div style="float: left; border-top:2px solid #ccc; border-bottom:2px solid #ccc; padding: 10px 0; margin-bottom: 1.35em;">
		<div style="float: left; line-height: 1.6;">
			<span style="font-weight: bold;">Reason for Rejection: </span><span><?= nl2br($student_response) ?></span>
		</div>
	</div>
	<div style="clear: both;"></div>
<p style="line-height:1.6; margin: 0;">Remember that you can make the changes that the student has requested and reapply to the request.</p>

<?= $footer ?>