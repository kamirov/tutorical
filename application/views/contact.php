<?

if ($is_modal)
{
	$additional_classes = ' reveal-modal large-modals ';
	$id = 'contact-modal';
	$close_sign = '<a class="close-reveal-modal">&#215;</a>';
}
else
{
	$additional_classes = ' on_page large-modals ';
	$id = 'contact';
	$close_sign = '';
}

$contact_email = ($this->session->userdata('contact_email') ?: $this->session->userdata('email'));

$email = array(
	'name'	=> 'email',
	'class'	=> 'email',
	'id' => $id.'-email',
	'type' => 'email',
	'tabindex' => 1900,
	'maxlength' => 80,
	'value' => $contact_email
);

$message = array(
	'name'	=> 'message',
	'class' => 'contact-message',
	'id' => $id.'-message',
	'tabindex' => 2000
);

$contact_button = array(
	'value' => 'Send Message', 
	'class' => 'buttons contact-button color-3-buttons large-submit-on-mobile no-top-margin-on-mobile', 
	'name' => 'submit',
	'tabindex' => 2050
);

?>
	<section id="<?= $id ?>" class="cf contacts <?= $additional_classes ?>" data-reveal>
		<section class="popup cf">
				<div class="pre-send">
					<form class="ased">
						<header>
							<h2>Send us a message</h2>
						    <?= $close_sign ?>
						</header>
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>


						<div class="popup-body">
							<div class="form-elements">
								<?= form_label('Email <span class="tiny-aftertext">(optional)</span>', $email['id'], array('class' => 'block-labels')) ?>
								<div class="form-inputs block-inputs">
									<?= form_input($email); ?> 
									<div class="form-input-notes error-messages"></div>
									<div class="form-input-notes">
										<span class="suggestions email-suggestion">Did you mean <span class="same-page-links suggested-values" tabindex="1950"><span class="address-part"></span>@<span class="domain-part"></span></span>?</span>
									</div>
								</div>
							</div>

							<div class="form-elements">
								<?= form_label('Message', $message['id'], array('class' => 'block-labels')) ?>
				
								<div class="form-inputs block-inputs">
									<?= form_textarea($message); ?>
									<div class="form-input-notes error-messages"></div>
								</div>
							</div>

							<?= form_submit($contact_button); ?>							
						</div>
					</form>
				</div>
				<div class="post-send send-success">
					<header>
						<h2>Message Sent!</h2>
					    <?= $close_sign ?>
					</header>
					<div class="popup-body">
						<p>Thanks for the message! We usually respond within 2 days. If we take any longer than that, please send an email to <?= safe_mailto(SITE_EMAIL); ?>.
						</p>
						<p>For reference, here is your message:</p>
						<div class="message-copy">

						</div>
						<p class="send-another-email-cont"><span class="same-page-links send-another-email">Click here to send another email</span></p>
					</div>
				</div>
				<div class="post-send send-fail">
					<header>
						<h2>Problem sending message!</h2>
					    <?= $close_sign ?>
					</header>
					<div class="popup-body">
						<p><b>Sorry, but something is wrong with our email system!</b></p>
						<p>Please copy the message below, and send it to <?= safe_mailto(SITE_EMAIL); ?>.
						</p>
						<div class="message-copy">

						</div>
						<p>An error notice has been posted for us to check and fix the problem. We're very sorry for the inconvenience.</p>
						<p class="send-another-email-cont"><span class="same-page-links send-another-email">Try sending again</span></p>

					</div>
				</div>
		</section>

		<p class="also-email post-popups">
			You can also send an email to <?= safe_mailto(SITE_EMAIL, SITE_EMAIL, 'tabindex="2200"'); ?>.
		</p>
	</section>
<script>
$(function()
{

<? if ($is_modal): ?>

	$('textarea', '.contacts').autosize();

	$('form', '.contacts').submit(function() 
	{
		var $form = $(this),
			$contact = $form.parents('.contacts'),
			$message = $form.find('[name=message]'),
			message = nl2br($message.val()),
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>);

			$form.find(':input').prop('disabled', true);

		$.ajax(
		{
			url: '<?= base_url("contact/send") ?>',
			type: "POST",
			data: 
			{
				email: $form.find('[name=email]').val(),
				message: message,
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
				$message.val('').height(150);		// Find a better way than hardcoding					
			}
			else
			{
				var postSendClass = 'send-fail';
			}

			if (response.status != <?= STATUS_VALIDATION_ERROR ?>)
			{
				$contact.find('.message-copy').html(message).end()
						.find('.pre-send').slideUp(<?= STANDARD_FADE_SPEED ?>).end()
						.find('.'+postSendClass).slideDown(<?= STANDARD_FADE_SPEED ?>).end()
					 	.find('.also-email').fadeOut(<?= STANDARD_FADE_SPEED ?>);
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

	$('.send-another-email', '.contacts').click(function() 
	{
		var $contact = $(this).parents('.contacts');

		$contact.find('.message-copy').html('<em>No Message</em>').end()
				.find('.pre-send').slideDown(<?= STANDARD_FADE_SPEED ?>).end()
			 	.find('.post-send').slideUp(<?= STANDARD_FADE_SPEED ?>).end()
				.find('.also-email').fadeIn(<?= STANDARD_FADE_SPEED ?>).end()
				.find('[name=email]').focus();
	});

<? endif; ?>

});

</script>