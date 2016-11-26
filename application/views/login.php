<?

if ($is_modal)
{
	$additional_classes = 'reveal-modal';
	$id = 'login-modal';
	$close_sign = '<a class="close-reveal-modal">&#215;</a>';
}
else
{
	$additional_classes = 'on_page';
	$id = 'login';
	$close_sign = '';
}

$email = array(
	'name'	=> 'email',
	'class'	=> 'email',
	'type' => 'email',
	'id' => $id.'-email',
	'maxlength' => 80,
	'tabindex' => 2000
);

$password = array(
	'name'	=> 'password',
	'class'	=> 'password',
	'id'	=> $id.'-password',
	'maxlength' => 80,
	'tabindex' => 2100
);

$remember = array(
	'name'	=> 'remember',
	'class'	=> 'remember',
	'value'	=> 1,
	'tabindex' => 2200
);

$login_button = array(
	'value' => 'Log in', 
	'class' => 'buttons login-button color-3-buttons large-submit-on-mobile', 
	'name' => 'login-submit',
	'tabindex' => 2300
);

if ($is_modal)
{
	$action = site_url();
}
else
{
	$action = site_url('account');
}

//if (!$is_modal):
//	var_dump(validation_errors(), $errors);
?>

	<section id="<?= $id ?>" class="cf logins <?= $additional_classes ?>" data-reveal>
		<section class="popup cf">
			<form class="login-form ased" method="POST" action="<?= $action ?>">
			<header>				
				<h2>Log in to your account</h2>
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
							<span class="suggestions email-suggestion">Did you mean <span class="same-page-links suggested-values" tabindex="750"><span class="address-part"></span>@<span class="domain-part"></span></span>?</span>
						</div>
					</div>
				</div>				

				<div class="form-elements">
					<?= form_label('Password', $password['id'], array('class' => 'block-labels')) ?>					
					<div class="form-inputs block-inputs">
						<?= form_password($password); ?>
						<div class="form-input-notes error-messages"></div>
						<div class="form-input-notes info-messages"></div>
					</div>
				</div>
				
				<div class="cf">
					<div class="remember-me-div">
						<?= form_label(form_checkbox($remember) . 'remember me'); ?>
					</div>

					<?= form_submit($login_button); ?>
				</div>

				<span class="reset-pass-links">
					<a href="javascript:void(0);" data-reveal-id="recovery-modal" tabindex="2350">Reset your password</a>
				</span>
			</div>
			</form>
		</section>
		<p class="post-popups">
			Don't have an account? <a href="javascript:void(0);" data-reveal-id="signup-tutor-modal" tabindex="2400">Become a Tutor &raquo;</a>
		</p>

	</section>
<script>

$(function()
{

	$('.reset-pass-links a, <?= "#".$id ?>').click(function() 
	{
		$('.recoveries [name=email]').val($('<?= "#".$id ?>').find('[name=email]').val());
	});

// We use $is_modal because the modal version is loaded on every page, including the actual /login page
<? if ($is_modal): ?>

	var loginFormCanSubmit = false;

	$('.login-form').submit(function() 
	{
		// We use this to save credentials
		if (loginFormCanSubmit)
			return true;

		var $form = $(this),
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>),
			data = {
				email: $form.find('[name=email]').val(),
				password: $form.find('[name=password]').val(),			
				remember: ($form.find('[name=remember]').is(':checked') ? 1 : 0),			
				as_f: $form.find('[name=as_f]').val(),
				as_e: $form.find('[name=as_e]').val(),
				as_h: $form.find('[name=as_h]').val()
			}

		$form.find(':input').prop('disabled', true);
		$.ajax(
		{
			type: "POST",
			url: baseUrl("auth/attempt_login"),
			data: data,
			dataType: 'json'
		}).done(function(response)
		{
			$form.validate(response.errors);

			if (response.success == true)
			{
				loginFormCanSubmit = true;
				$form.submit();
			}
			else
			{
				$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);					
			}	
		}).always(function() 
		{
			$form.find(':input').prop('disabled', false);
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