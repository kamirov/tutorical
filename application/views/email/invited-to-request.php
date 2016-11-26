<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>A student has invited you to their tutor request</title>
	<style>
	p
	{
		margin: 0 0 1.35em 0;
	}
	</style>
</head>
<body>
<div style="max-width: 450px; margin: 0; padding: 0;">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td align="left" width="100%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<p>A student has invited you to their tutor request through your <?= anchor('', 'Tutorical', 'style="color: #4A85DD"') ?> profile!</p>
	<div style="float: left; border-top:2px solid #ccc; border-bottom:2px solid #ccc; padding: 10px 0; margin-bottom: 1.35em;">
		<div style="float: left; line-height: 1.6;">
			<b><?= anchor("requests/$request_id", $request_subjects, 'style="color: #4A85DD;"') ?></b><br>
			<span style="font-style: italic; color: #888;">Posted by: <?= $student_name ?> (<?= anchor($student_profile_path, 'see profile', 'style="color: #4A85DD;"') ?>)</span>
		</div>
	</div>
	<div style="clear: both;"></div>
<p style="line-height:1.6; margin: 0;">We recommend you <b><?= anchor("requests/$request_id", "read the tutor request", 'style="color: #4A85DD;"') ?></b> and apply if you're interested.</p>


</td>
</tr>
</table>
</div>
</body>
</html>