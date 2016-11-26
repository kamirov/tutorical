<?= $header ?>

<h2 style="<?= $heading_style ?>"><?= $student_name ?> has accepted your application!</h2>
<br>
<p style="line-height:1.6; margin: 0;">You applied to <?= anchor($student_profile_path, $student_name) ?>'s <?= anchor("request/$request_id", "tutor request") ?> and have been accepted!</p><br>
<p style="line-height:1.6; margin: 0;">We recommend you:</p>
<ol style="line-height:1.6;">
	<li><b>Review the request</b> by visiting the <?= anchor("requests/$request_id", "Request Details page", 'style="color: #4A85DD"') ?></li>
	<li><b>Reply to this email</b> to contact the student directly</li>
	<li><b>Congratulate yourself</b> on a job well done!</li>
</ol>

<?= $footer ?>