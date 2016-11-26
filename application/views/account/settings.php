<?
/*
$role_attr = 'id="account-role" class="form-inputs chosen-selects"';
$role_options = array(
	ROLE_STUDENT => 'Student',
	ROLE_TUTOR => 'Tutor'
);

$role = $this->session->userdata('role');
if (!in_array($role, array(ROLE_STUDENT, ROLE_TUTOR)))
	$role = ROLE_TUTOR;
*/

$email = array(
	'name'	=> 'email',
	'class'	=> 'email',
	'id'	=> 'settings-email',
	'maxlength'	=> 80,
//	'placeholder' => 'New Email',
	'value' => $this->session->userdata('email'),
	'tabindex' => 2700
);

$password = array(
	'name'	=> 'password',
	'class'	=> 'password',
	'id'	=> 'settings-password',
//	'placeholder' => 'New Password',
	'autocomplete' => 'off',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'data-typetoggle' => '#settings-show-password',
	'tabindex' => 3000
);

$confirm_password = array(
	'name'	=> 'confirm-password',
	'class'	=> 'password',
	'id'	=> 'confirm-password',
	'autocomplete' => 'off',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'tabindex' => 2500
);

$show_password = array(
	'id'	=> 'settings-show-password',
	'class'	=> 'show-password',
	'value'	=>	'',
	'tabindex' => 3200
);

$delete = array(
	'name'	=> 'delete-account-text',
	'class'	=> '',
	'id'	=> 'delete-account-text',
	'maxlength'	=> 80,
	'placeholder' => '',
	'value' => '',
	'tabindex' => 3500
);

?>
<section id="account" class="cf pages containers" data-user-id="<?= '' ?>">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

	<div id="settings-content" class="account-subpage-conts">

		<div class="sections-lists">
			<ul>
				<li class="active">Account</li><li>Notifications</li>
			</ul>
		</div>

		<div id="settings-sections-cont">
			<div class="form-cont settings-sections active" id="settings-account">
				<form class="no-submit">
					<div class="form-elements" id="confirm-element">
						<p>To change account settings, please confirm your current password:</p>
						<label class="line-labels active">Type your password</label>
						<div class="setting-conts">
							<?= form_password($confirm_password) ?><img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="confirm-password-loader">
							<div class="form-input-notes error-messages" data-input-name="confirm-password"></div>
							<div class="form-input-notes info-messages"></div>
						</div>
					</div>
				</form>
				<form>
					<div class="form-elements" id="change-email-element">
						<label class="line-labels" id="change-email-label">Email Address</label>
						<div class="setting-conts">
							<span class="display-settings">
								<span class="same-page-links change-links" id="settings-email-display"><?= $this->session->userdata('email') ?></span>
								(<span class="same-page-links change-links">change</span>)
							</span>
							<span class="change-settings">
								<?= form_input($email) ?>
									<div class="submit-conts">
										<?= form_submit('change-email-button', 'Change', 'class="buttons color-3-buttons change-settings-buttons" tabindex="2800"'); ?>
										<span class="hide-buttons reg-hide-buttons" title="Cancel edit">&times;</span>
										<span class="hide-buttons buttons mobile-hide-buttons" value="Cancel" title="Cancel edit">Cancel</span>
										<img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="change-email-loader">
									</div>
								<div class="form-input-notes error-messages" data-input-name="email"></div>
							</span>
						</div>
					</div>
				</form>
				<form>
					<div class="form-elements" id="change-password-element">
						<label class="line-labels" id="change-password-label">Password</label>
						<div class="setting-conts">
							<span class="display-settings">
								[super secret]
								(<span class="same-page-links change-links">change</span> | <span class="same-page-links" id="reset-password-link">reset</span>)
							</span>
							<span class="change-settings">
								<?= form_password($password) ?>
									<div class="submit-conts">
										<?= form_submit('change-password-button', 'Change', 'class="buttons color-3-buttons change-settings-buttons" tabindex="3300"'); ?>
										<span class="hide-buttons reg-hide-buttons" title="Cancel edit">&times;</span>
										<span class="hide-buttons buttons mobile-hide-buttons" value="Cancel" title="Cancel edit">Cancel</span>
										<img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="change-password-loader">
									</div>
								<div class="form-input-notes-conts">
									<div class="form-input-notes error-messages" data-input-name="password"></div>
									<div class="form-input-notes info-messages"></div>
								</div>
								<div class="show-pass-div">			
									<?= form_label(form_checkbox($show_password) . "show password"); ?>
								</div>
							</span>
						</div>
					</div>
				</form>
		<? if ($this->session->userdata('role') == ROLE_STUDENT): ?>
				<form>
					<div class="form-elements" id="change-role-element">
						<label class="line-labels" id="change-role-label">Account Type</label>
						<div class="setting-conts">
							<span class="display-settings">
								<span class="same-page-links change-links" id="settings-email-display">Student</span>
								(<span class="same-page-links change-links">change</span>)
							</span>
							<span class="change-settings">
								<div class="submit-conts">
									<?= form_submit('change-role-button', 'Become a Tutor', 'class="buttons color-3-buttons change-settings-buttons" id="change-role-button" title="Become a Tutor"'); ?>
									<span class="hide-buttons reg-hide-buttons" title="Cancel edit">&times;</span>
									<span class="hide-buttons buttons mobile-hide-buttons" value="Cancel" title="Cancel edit">Cancel</span>
									<img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="change-role-loader">
								</div>
								<?// form_dropdown('role', $role_options, $role, $role_attr) ?>
								<div class="form-input-notes error-messages" data-input-name="role"></div>
							</span>
						</div>
					</div>
				</form>
		<? endif; ?>
				<form>
					<div class="form-elements" id="delete-account-element">
						<label class="line-labels" id="delete-account-label">Delete Account</label>
						<div class="setting-conts">
							<span class="display-settings">
								<span class="same-page-links danger-page-links change-links" id="delete-account-link" title="How do you go on...when in your heart you begin to understand...there is no going back?">Click to permanently delete your account</span>
							</span>
							<span class="change-settings">
								<p class="change-settings-text">To delete your account, type <span class="danger-text"><?= DELETE_TEXT ?></span> below.</p>
								<?= form_input($delete) ?>
								<div class="submit-conts">
									<?= form_submit('delete-account-button', 'Delete Account', 'class="buttons color-3-buttons change-settings-buttons" tabindex="3550"'); ?>
									<span class="hide-buttons reg-hide-buttons" title="Cancel edit">&times;</span>
									<span class="hide-buttons buttons mobile-hide-buttons" value="Cancel" title="Cancel edit">Cancel</span>
									<img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="delete-account-loader">
								</div>
								<div class="form-input-notes error-messages" data-input-name="delete-account-text"></div>
							</span>
						</div>
					</div>
				</form>
			</div>

			<div class="form-cont settings-sections" id="settings-notifications">
				<p>Check below to receive email notifications for each situation. When you're done, press 'Save Changes'.</p>
				<form id="edit-notifications-form">
					<div class="edit-notifications-conts">
<!--						<h3>Email Notifications</h3>  -->
						<div class="edit-notifications-bodies">
							<? if ($role != ROLE_STUDENT): ?>
							<div class="edit-notification-items">
								<h4>Tutors:</h4>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="tutor_contact" <?= (in_array('tutor_contact', $notification_settings) ? 'checked="checked"' : '') ?> /> A student contacted you through your profile
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="tutor_invite" <?= (in_array('tutor_invite', $notification_settings) ? 'checked="checked"' : '') ?> /> A student invited you to their tutor request
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="tutor_accept" <?= (in_array('tutor_accept', $notification_settings) ? 'checked="checked"' : '') ?> /> A student accepted your application
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="tutor_reject" <?= (in_array('tutor_reject', $notification_settings) ? 'checked="checked"' : '') ?> /> A student rejected your application
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="tutor_local_request" <?= (in_array('tutor_local_request', $notification_settings) ? 'checked="checked"' : '') ?> /> A tutor request for a subject you teach was made within <?= DEFAULT_EMAIL_TUTORS_DISTANCE ?>km (~9 miles) of you
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="tutor_distance_request" <?= (in_array('tutor_distance_request', $notification_settings) ? 'checked="checked"' : '') ?> /> A distance tutor request was made for a subject you teach
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="tutor_tips" <?= (in_array('tutor_tips', $notification_settings) ? 'checked="checked"' : '') ?> /> Tips for tutors using Tutorical
								</label>
							</div>
						<? endif; ?>
							<div class="edit-notification-items">
								<h4>Students:</h4>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="student_applied" <?= (in_array('student_applied', $notification_settings) ? 'checked="checked"' : '') ?> /> A tutor applied to your tutor request
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="student_tips" <?= (in_array('student_tips', $notification_settings) ? 'checked="checked"' : '') ?> /> Tips for students using Tutorical
								</label>
							</div>
							<div class="edit-notification-items">
								<h4>General:</h4>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="general_email_changed" <?= (in_array('general_email_changed', $notification_settings) ? 'checked="checked"' : '') ?> /> Your account email was changed
								</label>
								<label class="checkbox-option-conts">
									<input type="checkbox" name="email-notifications[]" value="general_pass_changed" <?= (in_array('general_pass_changed', $notification_settings) ? 'checked="checked"' : '') ?> /> Your account password was changed
								</label>
							</div>
							<div class="edit-notification-items centered-on-mobile">
								<?= form_submit('save-notification-changes', 'Save Changes', 'class="buttons color-3-buttons large-submit-on-mobile no-top-margin-on-mobile" id="save-notification-changes"'); ?><img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="save-notification-changes-loader">
							</div>
						</div>
					</div>
					<div class="edit-notifications-conts">

					</div>
				</form>
			</div>
		</div>

	</div>

</section>

<script>

var $confirmValidity,
	$confirmPassword = $('#confirm-password'),
	fadeSpeed = <?= FAST_FADE_SPEED ?>;

$(function()
{

	$('#edit-notifications-form').submit(function()
	{
		var $form = $(this),
			$loader = $form.find('.ajax-loaders').show(),
			data = $form.serialize();

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('account/edit_notification_settings') ?>",
			data: data,
			dataType: 'json'
		}).done(function(response)
		{
			$form.validate(response.errors);

			if (response.success == true)
			{
				var notyOptions = {
					timeout: 1800,
					type: 'success',
					text: '<b>Changes saved!</b>'
				};
				noty(notyOptions);
			}
			else
			{
				ajaxFailNoty();
			}
		}).always(function() 
		{
			$.noty.closeAll();
			$loader.hide();
		}).fail(function() 
		{
			ajaxFailNoty();
		});
		return false;
	});

	$('li', '.sections-lists').click(function()
	{
		var $listItem = $(this),
			index = $listItem.index(),
			$siblings = $listItem.siblings(),
			$sections = $('.settings-sections');

		$siblings.removeClass('active');
		$listItem.addClass('active');

		$sections.eq(index).addClass('active')
				 .siblings().removeClass('active');
	})

	$('#account .password').blur(function()
	{
		$('#account ').find('.info-messages').empty().hide();
	});

	if (!window.handheld)
	{
		$('#account-role').chosen(
		{
			disable_search: true,
			single_backstroke_delete: true
		});
	}

	$('#confirm-password').focus();

	$('#reset-password-link').click(function()
	{
		$('<?= "#recovery-modal" ?>').foundation('reveal', 'open')
		.find('form').submit();
	});

	$('#settings-content .hide-buttons').click(function()
	{
		hideChangeSettings($(this));
	});

	$confirmValidity = $('[data-corresponding-field="confirm-password"]');
				
	$('.display-settings .change-links').click(function() {
		var $this = $(this),
			$settings = $this.parents('.display-settings'),
			$label = $settings.parent().siblings('.line-labels');

		$settings.fadeOut(fadeSpeed, function() {
			$label.addClass('active', fadeSpeed);
			$settings.siblings('.change-settings').fadeIn(fadeSpeed).find('input').eq(0).focus();
		});
	});

	$('.change-settings .change-back-links').click(function() {
		var $this = $(this),
			$settings = $this.parents('.change-settings'),
			$label = $settings.parent().siblings('.line-labels');

		$settings.fadeOut(fadeSpeed, function() {
			$settings.siblings('.display-settings').fadeIn(fadeSpeed);
		});
	});

	$('#<?= $password["id"] ?>').showPasswordOnToggle().passStrength()
	.add('#<?= $email["id"] ?>, #<?= $password["id"] ?>-clone').keydown(function(){
//		var $this = $(this);
//		$this.showErrors('', false);
	});									

	$('#change-password-element .strengthresult').prependTo('#change-password-element .form-input-notes-conts');


	$('[name=change-email-button]').parents('form').submit(function(e) {
		return changeEmail();
	})
	$('[name=change-password-button]').parents('form').submit(function(e) {
		return changePassword();
	})
	$('[name=change-role-button]').parents('form').submit(function(e) {
		return changeRole();
	})
	$('[name=delete-account-button]').parents('form').submit(function(e) {
		return deleteAccount();
	})

	$('#delete-account-link').click(function() {
		$('#delete-account-link').qtip('hide');
	});
});

function changeRole()
{
	if (confirm("Becoming a tutor will give you a tutor profile and let you apply to tutor requests. You'll still have all your student powers, but once you upgrade your account type, you won't be able to downgrade again. Are you sure you want to become a tutor?"))
	{
		var $role = $('#change-role-element [name=role]')
			$loader = $('#change-role-loader');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>).css('display', 'inline-block');

		$.ajax(
		{
			type: "POST",
			url: baseUrl("account/change_role"),
			data: 
			{
				'confirm-password': $confirmPassword.val(),
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// console.log(response)
			$('.setting-conts').validate(response.errors);

			if (response.success == true)
			{			
				window.location = "<?= base_url('account') ?>";
			}
			else if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
			{
				ajaxFailNoty();
			}
		}).always(function() 
		{
			$loader.hide();
		}).fail(function() 
		{
			ajaxFailNoty();
		});
	}

	return false;
}

function changeEmail()
{
	var $email = $('#change-email-element input[name=email]')
		$loader = $('#change-email-loader');

	$loader.fadeIn(<?= FAST_FADE_SPEED ?>).css('display', 'inline-block');

	$.ajax(
	{
		type: "POST",
		url: baseUrl("account/change_email"),
		data: 
		{
			email : $email.val(),
			'confirm-password': $confirmPassword.val(),
		},
		dataType: 'json'
	}).done(function(response) 
	{
		// console.log(response)
		$('.setting-conts').validate(response.errors);

		if (response.success == true)
		{
			var $parents = $email.parents('.change-settings');

			$('#settings-email-display').text($email.val());

			$parents.fadeOut(fadeSpeed, function() {
				$parents.siblings('.display-settings').fadeIn(fadeSpeed);
			});
			
			$('#change-email-label').removeClass('active', fadeSpeed);

			var notyOptions = {
				timeout: 1700,
				type: 'success',
				text: '<b>Email address changed!</b>'
			};
			noty(notyOptions);

			sendEmailChangedEmails(response.data);
		}
		else if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
		{
			ajaxFailNoty();
		}
	}).always(function() 
	{
		$loader.hide();
	}).fail(function() 
	{
		ajaxFailNoty();
	});

	return false;
}

function sendEmailChangedEmails(emails)
{
	$.ajax(
	{
		type: "POST",
		url: baseUrl("account/send_email_change_emails"),
		data: emails,
		dataType: 'json'
	}).done(function(response) 
	{
		// console.log(response);	
	});
}

function hideChangeSettings($j)
{
	if (typeof $j == 'undefined')
	{
		$('.change-settings').fadeOut(fadeSpeed, function()
		{
			$this.siblings('.display-settings').fadeIn(fadeSpeed);
		});
	}
	else
	{
		var $changeSettingCont = $j.parents('.change-settings');

		$changeSettingCont.fadeOut(fadeSpeed, function() 
		{
			$changeSettingCont.siblings('.display-settings').fadeIn(fadeSpeed);
		});

		$changeSettingCont.parents('.form-elements').find('.line-labels').removeClass('active', fadeSpeed);
	}
}

function changePassword()
{
	var $password = $('#change-password-element input[name=password]')
		$loader = $('#change-password-loader');

	$loader.fadeIn(<?= FAST_FADE_SPEED ?>).css('display', 'inline-block');

	$.ajax(
	{
		type: "POST",
		url: baseUrl("account/change_password"),
		data: 
		{
			password : $password.val(),
			'confirm-password': $confirmPassword.val(),
		},
		dataType: 'json'
	}).done(function(response) 
	{
		// // console.log(response)
		$('.setting-conts').validate(response.errors);

		if (response.success == true)
		{
			var $parents = $password.parents('.change-settings');

			$('#settings-password-display').text($password.val());

			$parents.fadeOut(fadeSpeed, function() {
				$parents.siblings('.display-settings').fadeIn(fadeSpeed);

				// Reset show-password
				$password.show();
				$('#settings-password-clone').hide()
				.add($password).val('');
				$('#settings-show-password').prop('checked', false);
			});

			$confirmPassword.val($password.val());
			
			$('#change-password-label').removeClass('active', fadeSpeed);

			var notyOptions = {
				timeout: 1500,
				type: 'success',
				text: '<b>Password changed!</b>'
			};
			noty(notyOptions);

			sendPasswordChangedEmail();

		}
		else if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
		{
			ajaxFailNoty();
		}
	}).always(function() 
	{
		$loader.hide();
	}).fail(function() 
	{
		ajaxFailNoty();
	});

	return false;
}

function sendPasswordChangedEmail()
{
	$.ajax(
	{
		type: "POST",
		url: baseUrl("account/send_password_changed_email"),
		dataType: 'json'
	}).done(function(response) 
	{
		// console.log(response);	
	});
}

function deleteAccount()
{
	var deleteText = $("[name=delete-account-text]").val(),
		$loader = $('#delete-account-loader');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>).css('display', 'inline-block');
			
		$.ajax({
			type: "POST",
			url: baseUrl("account/delete"),
			data: 
			{
				'delete-account-text': deleteText,
				'confirm-password': $confirmPassword.val()
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// // console.log(response)
			$('.setting-conts').validate(response.errors);

			if (response.success == true)
			{
				window.location = "<?= base_url() ?>";
			}
			else if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
			{
				ajaxFailNoty();
			}
		}).always(function() 
		{
			$loader.hide();
		}).fail(function() 
		{
			ajaxFailNoty();
		});
	return false;
}

</script>