<section id="text-regular" class="pages containers page-errors cf">
	<h1 id="page-heading">Sorry, please click "Reset Password" again<span class="404-sad">&nbsp;&nbsp;&nbsp;:(</span></h1>  <!-- I don't understand why CSS just isn't applying here -->

	<div id="page-content" class="text-content">
		<p>After pressing "Reset Password", there's only a limited time that the link in your email is active. This is to prevent anyone who might've hacked in to your email at some later date from changing your Tutorical password.</p>

		<? if ($logged_in): ?>

		<p>Please <?= anchor('#', 'click here to reset your password', 'data-reveal-id="recovery-modal"'); ?>. Sorry for the inconvenience.</p>

		<? else: ?>

		<p>Please <?= anchor('#', 'log in', 'data-reveal-id="login-modal"'); ?>, visit the settings page and reset the password one more time. Sorry for the inconvenience.</p>

		<? endif; ?>
	</div>

</section>