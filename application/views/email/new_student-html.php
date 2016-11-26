<?= $header ?>

<h2 style="<?= $heading_style ?>">Welcome to Tutorical!</h2>
<br><p>To log in to your student profile and create a new password, just follow this link:</p>

<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?= base_url('reset-password/'.$user_id.'/'.$new_pass_key.'/1'.'/'); ?>" style="color: #3366cc;">Log In / Create a New Password</a></b></big>
<br /><br>
<p>Link doesn't work? Copy the following link to your browser address bar:</p>
<nobr><a href="<?= base_url('reset-password/'.$user_id.'/'.$new_pass_key.'/1'.'/'); ?>" style="color: #3366cc;"><?= base_url('reset-password/'.$user_id.'/'.$new_pass_key.'/1'.'/'); ?></a></nobr><br />

<br />
<p>You received this message because your email was used to contact a tutor via <a href="<?= base_url(''); ?>" style="color: #3366cc;">Tutorical</a>. This is part of the procedure to create a new student profile on Tutorical. If you <b>did not</b> use your email on Tutorical, please contact us at <?= mailto(SITE_EMAIL) ?> and we'll delete the account.</p>
<br />

<?= $footer ?>