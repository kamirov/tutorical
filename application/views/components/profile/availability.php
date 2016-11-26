<?

//var_dump($availability);

$day_iterations = 15;	// 20:00 - 6:00
$day_rows = $night_rows = '';
$available_class = 'available';

if ($availability)
{
	// Handle day rows
	for ($i = 0; $i < $day_iterations; $i++)
	{
		$row = array_shift($availability);
		$day_rows .= '
			<tr>
				<th>'.$row['time'].'</th>
				<td class="'.($row['mon'] ? $available_class : '').'"></td>
				<td class="'.($row['tue'] ? $available_class : '').'"></td>
				<td class="'.($row['wed'] ? $available_class : '').'"></td>
				<td class="'.($row['thu'] ? $available_class : '').'"></td>
				<td class="'.($row['fri'] ? $available_class : '').'"></td>
				<td class="'.($row['sat'] ? $available_class : '').'"></td>
				<td class="'.($row['sun'] ? $available_class : '').'"></td>
			</tr>
		';
	}

	// Handle night rows; we can foreach because we array_shifted all day values in previous loop

	foreach($availability as $row)
	{
		$night_rows .= '
			<tr>
				<th>'.$row['time'].'</th>
				<td class="'.($row['mon'] ? $available_class : '').'"></td>
				<td class="'.($row['tue'] ? $available_class : '').'"></td>
				<td class="'.($row['wed'] ? $available_class : '').'"></td>
				<td class="'.($row['thu'] ? $available_class : '').'"></td>
				<td class="'.($row['fri'] ? $available_class : '').'"></td>
				<td class="'.($row['sat'] ? $available_class : '').'"></td>
				<td class="'.($row['sun'] ? $available_class : '').'"></td>
			</tr>
		';	
	}

}
else
{
	$times = array('6am','7am','8am','9am','10am','11am','12pm','1pm','2pm','3pm','4pm','5pm','6pm','7pm','8pm','9pm','10pm','11pm','12am','1am','2am','3am','4am','5am');

	// Handle day rows
	for ($i = 0; $i < $day_iterations; $i++)
	{
		$row = array_shift($times);
		$day_rows .= '
			<tr>
				<th>'.$row.'</th>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		';
	}

	// Handle night rows; we can foreach because we array_shifted all day values in previous loop

	foreach($times as $row)
	{
		$night_rows .= '
			<tr>
				<th>'.$row.'</th>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		';	
	}

}

?>

<?= form_hidden('availability') ?>

<!-- 
	This has to be 3 tables (instead of a table with 1 thead and 2 tbodies) due to the inability to properly animate a tbody element (see http://stackoverflow.com/a/6883424/1932915). It's easier to just add make 3 tables and style accordingly.
-->
<div class="availabilities">
	<table class="days-cells-table">
			<tr>
				<th></th>
				<th>Mon</th>
				<th>Tue</th>
				<th>Wed</th>
				<th>Thu</th>
				<th>Fri</th>
				<th>Sat</th>
				<th>Sun</th>
			</tr>
	</table>

	<div class='availability-grids'>
		<div class="regular-rows-cont">
			<table class="time-cells-table">
				<?= $day_rows ?> 
			</table>
		</div>
		<div class="overnight-rows-cont">
			<table class="time-cells-table">
				<?= $night_rows ?> 
			</table>
		</div>
	</div>
	
	<div class="under-availability-tables">
		<div class="overnight-larger-cont">
			<span class="same-page-links show-overnight-times-links tiny-links">Show overnight times</span>
		</div>  
		<div class="availability-legends">
			<div class="legend-items" id="available-legend"><div class="availability-cells-display available"></div><span>available</span></div><div class="legend-items" id="unavailable-legend"><div class="availability-cells-display"></div><span>unavailable</span></div>
		</div>
	</div>
</div>