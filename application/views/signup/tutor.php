<?

if ($is_modal)
{
	$additional_classes = 'reveal-modal';
	$id = 'signup-tutor-modal';
	$close_sign = '<a class="close-reveal-modal">&#215;</a>';
}
else
{
	$additional_classes = 'on_page';
	$id = 'signup-tutor';
	$close_sign = '';
}

$form = array(
	'class' => 'signup-tutor-form ased',
	'autocomplete' => 'off'
);

$first_name = array(
	'name'			=> 'first-name',
	'id'			=> 'signup-tutor-first-name',
	'maxlength' 	=> 40,
	'tabindex' 		=> 1000,
	'class'			=> ''
);

$last_name = array(
	'name'			=> 'last-name',
	'id'			=> 'signup-tutor-last-name',
	'maxlength' 	=> 40,
	'tabindex' 		=> 1000,
	'class'			=> ''
);

$email = array(
	'name'	=> 'email',
	'class'	=> 'signup-tutor-email email',
	'id' => $id.'-email',
	'type' => 'email',
	'maxlength' => 80,
	'tabindex' => 1100

);

$password = array(
	'name'	=> 'new-password',
	'class'	=> 'signup-tutor-password password',
	'id'	=> $id.'-password',
	'autocomplete' => 'off',
	'maxlength' => 80,
	'data-typetoggle' => '#'.$id.'-show-password',
	'tabindex' => 1200
);

$show_password = array(
	'id'	=> $id.'-show-password',
	'class'	=> 'show-password',
	'value'	=>	'',
	'tabindex' => 1300
);

$create_account = array(
	'value' => 'Create Account', 
	'class' => 'buttons signup-tutor-button color-3-buttons large-submit-on-mobile', 
	'name' => 'signup-tutor-submit',
	'tabindex' => 1400
);

?>

	<section id="<?= $id ?>" class="cf signup-tutors <?= $additional_classes ?>" data-reveal>
		<section class="popup cf">
			<?= form_open('signup', $form); ?>

			<header>
				<h2>Become a Tutor!</h2>
			    <?= $close_sign ?>
			</header>

			<div class="ajax-overlays">
				<div class="ajax-overlays-bg"></div>
				<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
			</div>

			<div class="popup-body cf">

				<div class="form-elements">
					<?= form_label('First Name', $first_name['id'], array('class' => 'block-labels')) ?>						

					<div class="form-inputs block-inputs">
						<?= form_input($first_name); ?>
						<div class="form-input-notes error-messages"></div>
					</div>
				</div>

				<div class="form-elements">
					<?= form_label('Last Name', $last_name['id'], array('class' => 'block-labels')) ?>						
					<div class="form-inputs block-inputs">
						<?= form_input($last_name); ?>
						<div class="form-input-notes error-messages"></div>
						<div class="form-input-notes focus-messages">Can be abbreviated on your "Edit Profile" page</div>
					</div>
				</div>

				<div class="form-elements">
					<?= form_label('Your Email', $email['id'], array('class' => 'block-labels')) ?>						

					<div class="form-inputs block-inputs">
						<?= form_input($email); ?>
						<div class="form-input-notes error-messages"></div>
						<div class="form-input-notes">
							<span class="suggestions email-suggestion">Did you mean <span class="same-page-links suggested-values" tabindex="1150"><span class="address-part"></span>@<span class="domain-part"></span></span>?</span>	
						</div>
					</div>

				</div>

				<div class="form-elements">		
					<?= form_label('New Password', $password['id'], array('class' => 'block-labels')) ?>						

					<div class="form-inputs block-inputs">				
						<?= form_password($password); ?>
						<div class="form-input-notes error-messages"></div>
						<div class="form-input-notes info-messages"></div>
					</div>
				</div>

				<div class="show-pass-div">			
				<?= form_label(form_checkbox($show_password) . "show password"); ?>
				</div>

				<?= form_submit($create_account); ?>
				
			</div>  <!-- /.popup-body -->
			<?= form_close(); ?>
		</section>
			<!--
			<p class="social-signups">or sign up with: <a href="" tabindex="1500">Facebook</a> | <a href="" tabindex="1600">Twitter</a> | <a href="" tabindex="1700">Google</a> | <a href="" tabindex="1800">Yahoo</a></p>
			-->

		<p class="pre-popups">
			Already have an account? <a href="javascript:void(0);" data-redirect="<?= base_url('account') ?>" data-reveal-id="login-modal" tabindex="1500">Log In &raquo;</a>
		</p>

<!--
		<div class="why-love registration-halves">
		<h2><span>Why you'll love Tutorical</span>:</h2>
		<ul>
			<li><span class="raquos">»</span> Keep 100% of your earnings</li>
			<li><span class="raquos">»</span> Work whenever, wherever</li>
			<li><span class="raquos">»</span> Create a detailed hourly availability</li>
			<li><span class="raquos">»</span> Be both a student and a tutor</li>
			<li><span class="raquos">»</span> View your students on one easy-to-use page</li>
		</ul>
		</div>
-->
	</section>  <!-- /#registration -->

<script>

$(function()
{
	$('#<?= $password["id"] ?>').showPasswordOnToggle()
	.passStrength(
	{
	});

<? if ($is_modal): ?>

	$('.signup-tutor-form').submit(function(e) {
		var $form = $(this),
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$form.find(':input').prop('disabled', true);

		$.ajax({
			type: "POST",
			url: baseUrl("auth/attempt_signup"),
			data: 
			{
				'first-name': $form.find('[name=first-name]').val(),
				'last-name': $form.find('[name=last-name]').val(),
				email: $form.find('[name=email]').val(),
				'new-password': $form.find('[name=new-password]').val(),			
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
				window.location = "<?= base_url('account') ?>";
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