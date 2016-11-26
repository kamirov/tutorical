<?
	
$hidden_fields = array(
	'search-location' => $readable_location,
	'search-subject-id' => $current_subject_id,
	'lat' => '',
	'lon' => '',
	'loc-from' => LOC_FROM_GEOCODE,
	'geocoder-status' => '',
);

?>	
<?= form_open('find', array('id' => 'search-form')) ?>
	<?= form_hidden($hidden_fields) ?>
<?= form_close() ?>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=true"></script>
<script>
	$(function() 
	{
		var $form = $('#search-form'),
			address = $form.find('[name=search-location]').val(),
			geocoder = new google.maps.Geocoder();

		geocoder.geocode({ 'address': address }, function(results, status) 
		{	
			if (status == google.maps.GeocoderStatus.OK) 
			{
				var loc = results[0].geometry.location,
					lat = loc.lat(),
					lon = loc.lng();
					
				$form.find('[name=lat]').val(lat).end()
					 .find('[name=lon]').val(lon);
			}
			else
			{
				$form.find('[name=lat]').val('').end()
					 .find('[name=lon]').val('');

				if (status == 'OVER_QUERY_LIMIT')
					status = 'OVER_QUERY_ON_BOTH';
			}
			$form.find('[name=geocoder-status]').val(status).end()
				 .submit();
		});
	});
</script>