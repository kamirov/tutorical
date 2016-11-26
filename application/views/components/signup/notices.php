<script>
	$(function() 
	{
		<? if ($this->session->flashdata('finish_steps')):
			$noty = '<b>Sorry! Please complete the steps in order.</b>';
		?>
			noty({
				text: '<?= $noty ?>',
				type: 'warning',
				timeout: 2500
			});

		<? endif; ?>
	});
</script>