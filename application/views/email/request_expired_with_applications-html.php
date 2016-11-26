<?= $header ?>

<h2 style="<?= $heading_style ?>">Your <?= $subject ?> request has expired</h2>
<br>
<p style="line-height:1.6; margin: 0;">If you were tutored by one of the tutors that applied, please <?= anchor("requests/$request_id", "accept their application") ?> from your <?= anchor("request/$request_id", "request's page") ?>.</p>
<p style="line-height:1.6; margin: 0;">If you haven't found a tutor, we recommend you:</p>
<ol style="line-height:1.6;">
	<li><b>Make another request</b> with a higher Max Price or a more generic subject (e.g. "English" instead of "Literature")</li>
	<li><b>Invite tutors to your request</b> by clicking "Invite" on their profiles</li>
	<li><b>Search for tutors</b> and contact them through their Tutorical profiles</li>
</ol>

<?= $footer ?>