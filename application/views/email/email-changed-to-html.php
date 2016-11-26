<?= $header ?>

<h2 style="<?= $heading_style ?>">This is your Tutorical account's new login/email</h2>
<br>
<p>Previous Email: <b><?= mailto($old_email) ?></b>.</p>
<p>You received this message because your email was used for an account with <a href="<?= base_url(''); ?>" style="color: #3366cc;">Tutorical</a>. If you did this, then ignore this message. If not, then please contact us at <?= mailto(SITE_EMAIL) ?> as someone might have entered your email as their own.</p>

<?= $footer ?>