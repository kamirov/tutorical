<?
//var_dump($category_columns[0]);

?>

<section class="find-bars containers cf">

	<div class="sort-by-cont">

		<span class="sort-by-description-text">
			<? if ($num_of_items == 0): ?>
				No subjects
			<? elseif ($num_of_items == 1): ?>
			 	1 subject
			<? else: ?>
				<?= $num_of_items ?> subjects
			<? endif; ?>

		<? if ($search_domain == 'local'): ?>
			found
 		<span class="sort-by-description-text">within <a href="javascript:void(0);" data-dropdown="#dropdown-distances" class="sort-options"><?= $distance ?> <span class="sort-arrows">↓</span></a></span>
		<div id="dropdown-distances" class="dropdown dropdown-tip dropdown-relative">
		    <ul class="dropdown-menu">
		        <li><?= anchor(this_url_with_query(array('distance' => 1, 'page' => 1)), '1') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 5, 'page' => 1)), '5') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 10, 'page' => 1)), '10') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 15, 'page' => 1)), '15') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 20, 'page' => 1)), '20') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 25, 'page' => 1)), '25') ?></li>
		        <li><?= anchor(this_url_with_query(array('distance' => 30, 'page' => 1)), '30') ?></li>
		    </ul>
		</div>
		<span class="sort-by-description-text"><a href="javascript:void(0);" data-dropdown="#dropdown-units" class="sort-options"><?= $readable_units ?> <span class="sort-arrows">↓</span></a></span>
		<div id="dropdown-units" class="dropdown dropdown-tip dropdown-relative">
		    <ul class="dropdown-menu">
		        <li><?= anchor(this_url_with_query(array('units' => 'km', 'page' => 1)), 'km') ?></li>
		        <li><?= anchor(this_url_with_query(array('units' => 'miles', 'page' => 1)), $mile_unit) ?></li>
		    </ul>
		</div>
		<? else: ?>
			found.
		<? endif; ?>
		
	</div>

</section>

<?
if ($num_of_items != 0):
?>

<section id="find-results" class="cf containers subject-results">

<? 

foreach($category_columns as $col_subjects_and_categories): ?>

	<div class="subject-find-cols large-col-layout">
	<?
		foreach($col_subjects_and_categories as $category => $subjects): 

		$count = count($subjects);
		if ($count == 0) continue;
		
		$visible = array_slice($subjects, 0, 3);
		$visible_count = count($visible);

		for ($i = 0; $i < $visible_count; $i++)
		{
			unset($subjects[$i]);
		}
	?>
		
		<div class="category-items">
			<h2 class="subject-category-headings"><?= $category ?></h2>
			
			<ul>
				<? foreach($visible as $subject): 
					$title = $subject['tutor_count'].' '.($subject['tutor_count'] == 1 ? 'tutor' : 'tutors').' currently '.($subject['tutor_count'] == 1 ? 'teaches' : 'teach').' this subject';

					if ($search_domain == 'local')
					{
						$href = base_url('find/local/tutors/'.$location_query.'/'.urlencode($subject['name']));
					}
					else
					{
						$href = base_url('find/distance/tutors/'.urlencode($subject['name']));
					}
				?>
				<li><span class="raquos">&raquo;</span> <a class="subject-names" href="<?= $href ?>" title="See tutors that teach <?= $subject['name'] ?>"><?= $subject['name'] ?></a> <span class="subjects-tutor-counts" title="<?= $title ?>">(<?= $subject['tutor_count'] ?>)</span></li>
				<? 
				endforeach; ?>
			</ul>

			<? if ($subjects): ?>
			<ul class="hidden-subjects-list">
				<? foreach($subjects as $subject): 
					$title = $subject['tutor_count'].' '.($subject['tutor_count'] == 1 ? 'tutor' : 'tutors').' currently '.($subject['tutor_count'] == 1 ? 'teaches' : 'teach').' this subject';

					if ($search_domain == 'local')
					{
						$href = base_url('find/local/tutors/'.$location_query.'/'.urlencode($subject['name']));
					}
					else
					{
						$href = base_url('find/distance/tutors/'.urlencode($subject['name']));
					}
				?>
				<li><span class="raquos">&raquo;</span> <a class="subject-names" href="<?= $href ?>" title="See tutors that teach <?= $subject['name'] ?>"><?= $subject['name'] ?></a> <span class="subjects-tutor-counts" title="<?= $title ?>">(<?= $subject['tutor_count'] ?>)</span></li>
				<? 
				endforeach; ?>
			</ul>
			<span class="show-more-less-subjects-links">Show more</span>
			<? endif; ?>
		</div>

	<? endforeach;?>

	</div>
	<?
endforeach; ?>

<?
// This is bad. Works, but must make this less copy-pasted
	foreach($subjects_and_categories as $category => $subjects): 

	$count = count($subjects);
	if ($count == 0) continue;
	
	$visible = array_slice($subjects, 0, 3);
	$visible_count = count($visible);

	for ($i = 0; $i < $visible_count; $i++)
	{
		unset($subjects[$i]);
	}
?>
	
	<div class="category-items mid-col-layout">
		<h2 class="subject-category-headings"><?= $category ?></h2>
		
		<ul>
			<? foreach($visible as $subject): 
				$title = $subject['tutor_count'].' '.($subject['tutor_count'] == 1 ? 'tutor' : 'tutors').' currently '.($subject['tutor_count'] == 1 ? 'teaches' : 'teach').' this subject';

				if ($search_domain == 'local')
				{
					$href = base_url('find/local/tutors/'.$location_query.'/'.urlencode($subject['name']));
				}
				else
				{
					$href = base_url('find/distance/tutors/'.urlencode($subject['name']));
				}
			?>
			<li><span class="raquos">&raquo;</span> <a class="subject-names" href="<?= $href ?>" title="See tutors that teach <?= $subject['name'] ?>"><?= $subject['name'] ?></a> <span class="subjects-tutor-counts" title="<?= $title ?>">(<?= $subject['tutor_count'] ?>)</span></li>
			<? 
			endforeach; ?>
		</ul>

		<? if ($subjects): ?>
		<ul class="hidden-subjects-list">
			<? foreach($subjects as $subject): 
				$title = $subject['tutor_count'].' '.($subject['tutor_count'] == 1 ? 'tutor' : 'tutors').' currently '.($subject['tutor_count'] == 1 ? 'teaches' : 'teach').' this subject';

				if ($search_domain == 'local')
				{
					$href = base_url('find/local/tutors/'.$location_query.'/'.urlencode($subject['name']));
				}
				else
				{
					$href = base_url('find/distance/tutors/'.urlencode($subject['name']));
				}
			?>
			<li><span class="raquos">&raquo;</span> <a class="subject-names" href="<?= $href ?>" title="See tutors that teach <?= $subject['name'] ?>"><?= $subject['name'] ?></a> <span class="subjects-tutor-counts" title="<?= $title ?>">(<?= $subject['tutor_count'] ?>)</span></li>
			<? 
			endforeach; ?>
		</ul>
		<span class="show-more-less-subjects-links">Show more</span>
		<? endif; ?>
	</div>

<? endforeach;?>

	
</section>  <!-- /#find-results -->

<? else: ?>

<section id="text-regular" class="cf pages containers">
	<div class="boxes no-results-found-box" id="no-<?= $groups ?>-found-box">
		No subjects taught within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>.
		<br>
		<b>Make a free Tutor Request and we'll find tutors for you.</b>
	</div>
<!--
	<h1 id="page-heading" class="none-found-heading">
	<? if ($search_domain == 'local'): ?>
		<span>Sorry, there are no subjects being taught within <span class="find-editables"><?= $readable_distance ?></span> of <span class="find-editables"><?= $readable_location ?></span>...yet!</span>
	<? else: ?>
	<span>Sorry, there are no subjects being taught online...yet!</span>
	<? endif; ?>
	</h1>
	<div id="page-content" class="text-content">
		<p>But you might find a tutor if you <a href="javascript:void(0);" data-reveal-id="request-modal">make a Tutor Request</a>. It's free and it'll let tutors come to you!</p>
	</div>
-->
	<?= $extra_make_request ?>
</section>  <!-- /#text-regular -->

<? endif; ?>

<script>

$(function()
{
	<?
	if ($num_of_items == 0):
	?>
//		$('.none-found-heading').textfill({ maxFontPixels: 26 });
	<?
	endif;
	?>

	$('.show-more-less-subjects-links').click(function()
	{
		var $this = $(this),
			$list = $this.siblings('.hidden-subjects-list');

		if ($list.is(':visible'))
		{
			$this.text('Show more');
			$list.slideUp(<?= FAST_FADE_SPEED ?>)
		}
		else
		{
			$this.text('Show less');
			$list.slideDown(<?= FAST_FADE_SPEED ?>)
		}
	});

});

</script>