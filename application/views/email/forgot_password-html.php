<?= $header ?>

<h2 style="<?= $heading_style ?>">Reset your Tutorical password</h2>
<br><p>To create a new password, just follow this link:</p>

<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?= base_url('reset-password/'.$user_id.'/'.$new_pass_key.'/0'.'/'.$request_id); ?>" >Create a new password</a></b></big>
<br /><br>
<p>Link doesn't work? Copy the following link to your browser address bar:</p>
<nobr><a href="<?= base_url('reset-password/'.$user_id.'/'.$new_pass_key.'/0'.'/'.$request_id); ?>"><?= base_url('reset-password/'.$user_id.'/'.$new_pass_key.'/0'.'/'.$request_id); ?></a></nobr><br />

<br />
<p>You received this email because it was requested by a <a href="<?= base_url(''); ?>">Tutorical</a> user. This is part of the procedure to create a new password on Tutorical. If you <b>did not</b> request a new password then please ignore this email and your password will remain the same.</p>

<?= $footer ?>