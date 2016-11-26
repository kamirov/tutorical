<?

if ($is_modal)
{
	$additional_classes = 'reveal-modal';
	$id = 'reset-password-modal';
	$close_sign = '<a class="close-reveal-modal">&#215;</a>';
}
else
{
	$additional_classes = 'on_page';
	$id = 'reset-password';
	$close_sign = '';
}


$password = array(
	'name'	=> 'password',
	'class'	=> 'new-password password',
	'id'	=> 'new-password',
	'autocomplete' => 'off',
	'maxlength' => 80,
	'data-typetoggle' => '#show-password',
	'tabindex'	=> 800
);

$show_password = array(
	'id'	=> 'show-password',
	'class'	=> 'show-password',
	'value'	=>	'',
	'tabindex'	=> 900
);

$change = array(
	'value' => 'Change Password', 
	'class' => 'buttons reset-button color-3-buttons large-submit-on-mobile', 
	'name' => 'change',
	'tabindex'	=> 1000
);

if ($is_new)
	$change['value'] = 'Create Password';


?>
	<section id="<?= $id ?>" class="cf reset-password-secs <?= $additional_classes ?>" data-reveal>
		<section class="popup cf">
			<form>
				<header>
					<? if ($is_new): ?>
						<h2>Create a new password</h2>					
					<? else: ?>
						<h2>Change your password</h2>
					<? endif; ?>
					<?= $close_sign ?>
				</header>
				<div class="ajax-overlays">
					<div class="ajax-overlays-bg"></div>
					<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
				</div>	
				<div class="popup-body cf">
					<input type="hidden" name="user-id" value="<?= $reset_user_id ?>">
					<input type="hidden" name="request-id" value="<?= $request_id ?>">
					<input type="hidden" name="is-new" value="<?= $is_new ?>">
					<input type="hidden" name="new-pass-key" value="<?= $reset_new_pass_key ?>">
					<div class="form-elements">
						<?= form_label('New Password', $password['id'], array('class' => 'block-labels')) ?>
						<div class="form-inputs block-inputs">
							<?= form_password($password); ?>
							<div class="form-input-notes error-messages"></div>
							<div class="form-input-notes info-messages"></div>
						</div>
					</div>
					
					<div class="cf">
						<div class="show-pass-div">			
							<?= form_label(form_checkbox($show_password) . "show password"); ?>
						</div>
						<?= form_submit($change); ?>
					</div>
				</div>
			</form>
		</section>
	</section>

<script>

$(function() 
{

<? if (!$is_modal): ?>

	$('#<?= $password["id"] ?>').showPasswordOnToggle()
	.passStrength(
	{});

	$('[name=password]', '#<?= $id ?>').focus();

	$('form', '#<?= $id ?>').submit(function() 
	{
		var $form = $(this),
			$overlay = $form.find('.ajax-overlays').fadeIn(<?= OVERLAY_FADE_SPEED ?>),
			data = $form.serialize();

		$form.find(':input').prop('disabled', true);

		$.ajax(
		{
			url: '<?= base_url("auth/attempt_reset") ?>',
			type: "POST",
			data: data,
			dataType: 'json'
		}).done(function(response) 
		{
			// // console.log(response);
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
				$overlay.fadeOut(<?= FAST_FADE_SPEED ?>);

				if (response.status != <?= STATUS_VALIDATION_ERROR ?>)
				{
					ajaxFailNoty();
				}
			}
		}).always(function() 
		{
			$form.find(':input').prop('disabled', false);
		}).fail(function()
		{
			ajaxFailNoty();
		});
		return false;
	});

<? endif; ?>

});

</script>