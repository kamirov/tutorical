<?

if ($is_modal)
{
	$additional_classes = 'reveal-modal';
	$id = 'signup-student-modal';
	$close_sign = '<a class="close-reveal-modal">&#215;</a>';
}
else
{
	$additional_classes = 'on_page';
	$id = 'signup-student';
	$close_sign = '';
}

$contact_name = ($this->session->userdata('contact_name') ?: $this->session->userdata('display_name'));
$contact_email = ($this->session->userdata('contact_email') ?: $this->session->userdata('email'));

$email = array(
	'name'			=> 'email',
	'id'			=> $id.'-email',
	'type' 			=> 'email',
	'placeholder'	=> '',
	'value'			=> $contact_email,
	'tabindex' => 5100
);

$display_name = array(
	'name'			=> 'display-name',
	'id'			=> $id.'-display-name',
	'placeholder'	=> 'e.g. Simon Tam, Salman K.',
	'maxlength' 	=> 40,
	'class'			=> 'display-name-fields',
	'value'			=> $contact_name,
	'tabindex'		=> 5250
);

$new_password = array(
	'name'	=> 'new-password',
	'class'	=> 'signup-student-password password',
	'id'	=> $id.'-new-password',
	'autocomplete' => 'off',
	'maxlength' => 80,
	'data-typetoggle' => '#'.$id.'-show-password',
	'tabindex' => 5300
);

$password = array(
	'name'	=> 'password',
	'class'	=> 'signup-student-password password',
	'id'	=> $id.'-password',
	'maxlength' => 80,
	'tabindex' => 5300
);

$show_password = array(
	'id'	=> $id.'-show-password',
	'class'	=> 'show-password',
	'value'	=>	'',
	'tabindex' => 5400
);

$remember = array(
	'name'	=> 'remember',
	'class'	=> 'remember',
	'value'	=> 1,
	'tabindex' => 5400
);

$finish_button = array(
	'value' => 'Finish »', 
	'class' => 'buttons login-signup-button color-3-buttons large-submit-on-mobile', 
	'name' => 'submit',
	'tabindex' => 5500
);

?>

<section id="<?= $id ?>" class="cf signup-students <?= $additional_classes ?>" data-reveal>
	<p class="pre-popups needs-request">
		<a href="javascript:void(0);" data-reveal-id="request-modal" tabindex="5000">&laquo; Edit your request</a>
	</p>
	<section class="popup cf">
		<div class="pre-send" id="student-signup">
			<form autocomplete='off' class='ased'>
				<header>
					<h2>Are you a Tutorical student?</h2>
				    <?= $close_sign ?>
				</header>

				<div class="ajax-overlays">
					<div class="ajax-overlays-bg"></div>
					<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
				</div>

				<div class="popup-body cf">
				<input type="hidden" name="request-id" value="">
				<input type="hidden" name="auth-type" id="signup-student-auth-type" value="login">

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
					
					<div class="form-elements">
						<div class="form-inputs block-inputs no-top-margin-inputs">
							<?= form_label('Do you have an account?', NULL, array('class' => 'block-labels')) ?>						
							<label class="radio-option-conts inline-block first">
								<input type="radio" name="have-account" value="1" checked="checked" tabindex="5200" /> Yes
							</label>
							<label class="radio-option-conts inline-block">
								<input type="radio" name="have-account" value="0" tabindex="5200" /> No
							</label>
							<div class="form-input-notes radio-notes error-messages"></div>
						</div>
					</div>

					<div class="signup-student-new-account-fields">
						<div class="form-elements">
							<?= form_label('Name', $display_name['id'], array('class' => 'block-labels')) ?>						

							<div class="form-inputs block-inputs">
								<?= form_input($display_name); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>

						<div class="form-elements">		
							<?= form_label('New Password', $new_password['id'], array('class' => 'block-labels')) ?>						

							<div class="form-inputs block-inputs">				
								<?= form_password($new_password); ?>
								<div class="form-input-notes error-messages"></div>
								<div class="form-input-notes info-messages"></div>
							</div>
						</div>
					</div>

					<div class="signup-student-old-account-fields">				
						<div class="form-elements">
							<?= form_label('Password', $password['id'], array('class' => 'block-labels')) ?>						

							<div class="form-inputs block-inputs">
								<?= form_password($password); ?>
								<div class="form-input-notes error-messages"></div>
								<div class="form-input-notes info-messages"></div>
							</div>
						</div>
					</div>

					<div class="cf">
						<div class="signup-student-new-account-fields">				
							<div class="show-pass-div">			
								<?= form_label(form_checkbox($show_password) . "show password"); ?>
							</div>
						</div>
						<div class="signup-student-old-account-fields">				
							<div class="remember-me-div">
								<?= form_label(form_checkbox($remember) . 'remember me'); ?>
							</div>
						</div>
						<?= form_submit($finish_button); ?>
					</div>

					<span class="reset-pass-links">
						<a href="javascript:void(0);" tabindex="5500">Reset password</a> (enter email first)
					</span>

				</div>  <!-- /.popup-body -->
			</form>
		</div>
		<div class="post-send send-success">
			<header>
				<h2>Email sent!</h2>
			    <?= $close_sign ?>
			</header>
			<div class="popup-body">
				<p>Check <a class="user-email" href="">your email</a> for resetting instructions. <span class="needs-request">After reseting, your request will be automatically posted.</span></p>
			</div>
		</div>
		<div class="post-send send-fail">
			<header>
				<h2>Problem sending email!</h2>
			    <?= $close_sign ?>
			</header>
			<div class="popup-body">
				<p><b>Sorry, something went wrong!</b></p>
				<p>Check to make sure that the email below is correct. If it is, then please email us directly at <?= safe_mailto(SITE_EMAIL, SITE_EMAIL); ?> and we'll reset your password as soon as we can.
				</p>
				<div class="email-copy">

				</div>
				<p class="send-another-email-cont"><span class="same-page-links send-another-email">Try again</span></p>

			</div>
		</div>
	</section>
</section>

<script>

function setStudentSignupToAccount()
{
	var $modal = $('.signup-students');
	$modal.find(':input').showErrors('', false);
	$modal.find('.signup-student-new-account-fields').hide().end()
		  .find('.signup-student-old-account-fields').show()
		  .find('.signup-student-password').focus();
	$modal.find('[name=auth-type]').val('login');
}

function setStudentSignupToNoAccount()
{
	var $modal = $('.signup-students');
	$modal.find(':input').showErrors('', false);
	$modal.find('.signup-student-new-account-fields').show().end()
		  .find('.signup-student-old-account-fields').hide().end()
		  .find('.display-name-fields').focus();
	$modal.find('[name=auth-type]').val('signup');
}



$(function()
{

	$('#<?= $new_password["id"] ?>').showPasswordOnToggle().passStrength();

<? if ($is_modal): ?>

	$('.send-another-email', '.signup-students').click(function() 
	{
		var $modal = $(this).parents('.signup-students');

		$modal.find('.email-copy').html('').end()
		$modal.find('.pre-send').slideDown(<?= STANDARD_FADE_SPEED ?>).end()
			  .find('.post-send').slideUp(<?= STANDARD_FADE_SPEED ?>).end()
			  .find('[name=email]').focus();
	});

	$('[name=have-account]', '.signup-students').change(function()
	{
		var $modal = $(this).parents('.signup-students');

		if (this.value == true)
		{
			setStudentSignupToAccount();
		}
		else
		{
			setStudentSignupToNoAccount();
		}
	});

	$('.reset-pass-links a', '.signup-students').click(function()
	{
		var $modal = $(this).parents('.signup-students');
		var $form = $modal.find('form'),
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>),
			$email = $form.find('[name=email]'), 
			email = $email.val(),
			data = {
				email: email,
				as_f: $form.find('[name=as_f]').val(),
				as_e: $form.find('[name=as_e]').val(),
				as_h: $form.find('[name=as_h]').val(),
				open_requests: 1
			};

			$form.find(':input').prop('disabled', true);


		$.ajax(
		{
			type: "POST",
			url: baseUrl("auth/send-recovery"),
			data: data,
			dataType: 'json'
		}).done(function(response)
		{
			// console.log(response);

			$form.validate(response.errors);

			if (response.success == true)
			{
				var postSendClass = 'send-success';
			}
			else
			{
				var postSendClass = 'send-fail';
				$email.prop('disabled', false).focus();
			}
			
			if (response.status != <?= STATUS_VALIDATION_ERROR ?>)
			{
				$modal.find('.pre-popups, .post-popups').hide().end()
					  .find('.email-copy').text(email).end()
					  .find('.pre-send').slideUp(<?= STANDARD_FADE_SPEED ?>).end()
					  .find('.'+postSendClass).slideDown(<?= STANDARD_FADE_SPEED ?>);

				if (response.data && response.data.hasOwnProperty('domain'))
					$modal.find('.user-email').attr('href', 'http://'+response.data.domain);
			}
		}).always(function() 
		{
			$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);
			$form.find(':input').prop('disabled', false);
		}).fail(function() 
		{
			ajaxFailNoty();
		});
		return false;		
	})

	$('form', '.signup-students').submit(function() 
	{
		var $form = $(this),
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>),
			authType = $form.find('[name=auth-type]').val(),
			data = {
				email: $form.find('[name=email]').val(),
				as_f: $form.find('[name=as_f]').val(),
				as_e: $form.find('[name=as_e]').val(),
				as_h: $form.find('[name=as_h]').val(),
				open_requests: 1
			};

		if (authType == 'login')
		{
			data['password'] = $form.find('[name=password]').val();
			data['remember'] = ($form.find('[name=remember]').is(':checked') ? 1 : 0);
		}
		else
		{
			data['display-name'] = $form.find('[name=display-name]').val();
			data['new-password'] = $form.find('[name=new-password]').val();
			data['role'] = <?= ROLE_STUDENT ?>;				
		}

		$.ajax(
		{
			type: "POST",
			url: baseUrl("auth/attempt_"+authType),
			data: data,
			dataType: 'json'
		}).done(function(response)
		{
			// console.log(response);

			$form.validate(response.errors);

			if (response.success == true)
			{
				if (response.data && response.data.hasOwnProperty('requestId'))
					window.location = "<?= base_url('requests') ?>/"+response.data.requestId;
				else
					window.location = "<?= base_url('account') ?>";

			}
			else
			{
				$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);					
			}	
		}).always(function() 
		{
		}).fail(function() 
		{
			$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);
			ajaxFailNoty();
		});
		return false;
	});

<? endif; ?>

});

</script>