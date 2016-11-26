<?

$reject_message = array(
	'name'	=> 'message',
	'id' => 'reject-message',
	'placeholder' => ""
);

$reject_button = array(
	'value' => 'Proceed »', 
	'class' => 'buttons color-3-buttons',
	'id' => 'final-reject-button'
);

?>

<div id="dropdown-reject" class="dropdown dropdown-tip dropdown dropdown-anchor-right">
	<div class="ajax-overlays">
		<div class="ajax-overlays-bg"></div>
		<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
	</div>
	<div class="boxes dropdown-panel">
		<div class="form-elements">
			<form>
				<input type="hidden" name="id" id="application-id">

				<?= form_label('Reason for Rejection', $reject_message['id'], array('class' => 'block-labels')); ?>
				<div class="form-inputs block-inputs">
					<?= form_textarea($reject_message); ?>
					<div class="form-input-notes error-messages"></div>
				</div>
				<div class="right-aligned submit-conts">
					<?= form_submit($reject_button); ?>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
$(function()
{
	$('#dropdown-reject').on('show', function()
	{
		var $this = $(this);
		$this.find('textarea').focus().val('');

		scrollAndFocus($this);
	});
});
</script>