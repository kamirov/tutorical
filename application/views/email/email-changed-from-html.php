<?= $header ?>

<h2 style="<?= $heading_style ?>">Your Tutorical account's login/email has been changed</h2>
<br>
<p>New Email: <b><?= mailto($new_email) ?></b>.</p>
<p>If you were the one who changed it, then ignore this message. If you think that your account may have been compromised by someone that now changed your login/email, please contact us at <?= mailto(SITE_EMAIL) ?> and we'll do our best to resolve the issue.</p>

<?= $footer ?>