<?

if ($is_modal)
{
	$additional_classes = 'reveal-modal';
	$id = 'recovery-modal';
	$close_sign = '<a class="close-reveal-modal">&#215;</a>';
}
else
{
	$additional_classes = 'on_page';
	$id = 'recovery';
	$close_sign = '';
}

$recovery_email = ($this->session->userdata('contact_email') ?: $this->session->userdata('email'));

$email = array(
	'name'	=> 'email',
	'class'	=> 'email',
	'type' => 'email',
	'id' => $id.'-email',
	'value' => $recovery_email,
	'maxlength' => 80,
	'tabindex' => 1075
);

$send_button = array(
	'value' => 'Send Email', 
	'class' => 'buttons recovery-button color-3-buttons', 
	'name' => 'submit',
	'tabindex' => 1090
);

?>

	<section id="<?= $id ?>" class="cf recoveries <?= $additional_classes ?>" data-reveal>
		<section class="popup cf">
				<div class="pre-send">
					<form class="ased">
						<header>				
							<h2>Reset your password</h2>
						    <?= $close_sign ?>
						</header>
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>

						<div class="popup-body cf">
							<div class="form-elements">
								<?= form_label('Email', $email['id'], array('class' => 'block-labels')) ?>
								<div class="form-inputs block-inputs">
									<?= form_input($email); ?>
									<div class="form-input-notes error-messages"></div>
									<div class="form-input-notes">
										<span class="suggestions email-suggestion">Did you mean <span class="same-page-links suggested-values"><span class="address-part"></span>@<span class="domain-part"></span></span>?</span>
									</div>
								</div>
							</div>
							<?= form_submit($send_button); ?>
										
						</div>
					</form>
				</div>
				<div class="post-send send-success">
					<header>
						<h2>Email sent!</h2>
					    <?= $close_sign ?>
					</header>
					<div class="popup-body">
						<p class="email-sent-text">Check <a class="user-email" href="">your email</a> for resetting instructions.<br>
							<span class="tiny-aftertext">(it might take a minute to send the email)</span>
						</p>

						<p class="email-queued-text">Check <a class="user-email" href="">your email</a> in <b>about 1 hour</b> for password resetting instructions.<br>
							<span class="tiny-aftertext">(Sorry, our email server is a bit overworked right now)</span>
						</p>

						<p class="send-another-email-cont"><span class="same-page-links send-another-email">Try again</span></p>

					</div>
				</div>
				<div class="post-send send-fail">
					<header>
						<h2>Problem sending email!</h2>
					    <?= $close_sign ?>
					</header>
					<div class="popup-body">
						<p><b>Sorry, something is wrong with our email system!</b></p>
						<p>Check to make sure that the email below is correct. If it is, then please email us directly at <?= safe_mailto(SITE_EMAIL, SITE_EMAIL); ?> and we'll reset your password as soon as we can.
						</p>
						<div class="email-copy">

						</div>
						<p class="send-another-email-cont"><span class="same-page-links send-another-email">Try again</span></p>

					</div>
				</div>
		</section>

		<p class="post-popups back-to-login">
			<? if(!$logged_in && $is_modal): ?>
				<?= anchor('login', 'Back to login', 'data-reveal-id="login-modal" tabindex="1091"'); ?>
			<? endif; ?>
		</p>
	</section>

<script>
$(function()
{

<? if ($is_modal): ?>

	$('form', '.recoveries').submit(function() 
	{
		var $form = $(this),
			$recovery = $form.parents('.recoveries'),
			$email = $form.find('[name=email]'),
			email = $email.val(),
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$form.find(':input').prop('disabled', true);

		$.ajax(
		{
			url: '<?= base_url("auth/send-recovery") ?>',
			type: "POST",
			data: 
			{
				email: email,
				password: $form.find('[name=password]').val(),			
				as_f: $form.find('[name=as_f]').val(),
				as_e: $form.find('[name=as_e]').val(),
				as_h: $form.find('[name=as_h]').val()
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// // console.log(response);
			$form.validate(response.errors);

			if (response.success == true)
			{
				var postSendClass = 'send-success';

				if (response.data.emailStatus == <?= EMAIL_STATUS_SENT ?>)
				{
					$recovery.find('.email-sent-text').show().end()
							 .find('.email-queued-text').hide();
				}
				else
				{
					$recovery.find('.email-queued-text').show().end()
							 .find('.email-sent-text').hide();
				}

				$recovery.find('.user-email').attr('href', 'http://'+email.replace(/.*@/, ""));
			}
			else
			{
				var postSendClass = 'send-fail';
			}
			if (response.status != <?= STATUS_VALIDATION_ERROR ?>)
			{
				$recovery.find('.email-copy').text(email).end()
						.find('.pre-send').slideUp(<?= STANDARD_FADE_SPEED ?>).end()
						.find('.'+postSendClass).slideDown(<?= STANDARD_FADE_SPEED ?>).end();
			}
		}).always(function() 
		{
			$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);
			$form.find(':input').prop('disabled', false);
		}).fail(function()
		{
			ajaxFailNoty();
		});

		return false;
	});

	$('.send-another-email', '.recoveries').click(function() {
		var $recovery = $(this).parents('.recoveries');

		$recovery.find('.email-copy').html('').end()
		$recovery.find('.pre-send').slideDown(<?= STANDARD_FADE_SPEED ?>).end()
			 	 .find('.post-send').slideUp(<?= STANDARD_FADE_SPEED ?>).end()
			 	 .find('[name=email]').focus();
	});

<? endif; ?>

});
</script>