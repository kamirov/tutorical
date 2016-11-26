<?
$import_sections_name = 'import-sections[]';

$first_name = array(
	'name'			=> 'first-name',
	'id'			=> 'first-name',
	'maxlength' 	=> 40,
	'class'			=> '',
	'value'			=> $user['first_name']
);

$last_name = array(
	'name'			=> 'last-name',
	'id'			=> 'last-name',
	'maxlength' 	=> 40,
	'class'			=> '',
	'value'			=> $user['last_name']
);

$abbreviate_last_name = array(
	'name' => 'abbreviate-last-name',
	'id' => 'abbreviate-last-name',
	'class' => 'checkbox-inputs',
	'value' => TRUE,
	'checked' => ($user['name_type'] == NAME_TYPE_SHORT ? 'checked' : '')
);

$update_profile_link = array(
	'name' => 'update-profile-link',
	'id' => 'update-profile-link',
	'class' => 'checkbox-inputs',
	'value' => TRUE
);

if ($user['role'] != ROLE_STUDENT)
{
	$external_reviews = array(
		'reviewer' => array(
			'name'			=> 'reviewer',
			'class'			=> 'er-reviewers',
			'placeholder'	=> "",
		),
		'url' => array(
			'name'			=> 'url',
			'class'			=> 'er-urls',
			'placeholder'	=> "e.g. mysite.com, http://www.mysite.com",
		),
		'content' => array(
			'name'			=> 'content',
			'class'			=> 'er-contents',
			'placeholder'	=> "",
		)
		// ER-Rating is done with multiple inputs and a jQuery plugin, so its taken care of separately
	);

	$links = array(
		'label' => array(
			'name'			=> 'label',
			'class'			=> 'links-labels',
			'placeholder'	=> "e.g. My Blog, Company Site, Twitter",
		),
		'url' => array(
			'name'			=> 'url',
			'class'			=> 'links-urls',
			'placeholder'	=> "e.g. mysite.com, http://www.mysite.com",
		),
		'description' => array(
			'name'			=> 'description',
			'class'			=> 'links-descriptions',
			'placeholder'	=> "e.g. See more testimonials on my site!",
		)
	);

	$location = array(
		'name'			=> 'location',
		'id'			=> 'location',
		'class'			=> 'no-enter-submit',
		'placeholder'	=> 'Please include a street or landmark name',
		'value'			=> $user['location']
	);

	$price_name = 'price-type';

	$price_hourly = array(
		'value'	=> 'per_hour',
		'is_default' => FALSE
	);

	if ($user['price_type'] == $price_hourly['value'] || !$user['price_type'])
		$price_hourly['is_default'] = TRUE;

	$hourly_rate = array(
		'name'	=> 'hourly-rate',
		'id' => 'hourly-rate',
		'placeholder' => '0.00',
		'class' => 'hourly-rates',
		'value'	=> $user['hourly_rate']
	);

	$hourly_rate_high = array(
		'name'	=> 'hourly-rate-high',
		'id' => 'hourly-rate-high',
		'placeholder' => '0.00',
		'class' => 'hourly-rates',
		'value'	=> $user['hourly_rate_high']
	);
	/*
	if (form_error($hourly_rate['name']) || isset($errors[$hourly_rate['name']]))
		$hourly_rate['class'] .= ' form-errors ';
	*/
	$price_custom = array(
		'value'	=> 'per_student',
		'is_default' => FALSE
	);

	if ($user['price_type'] == $price_custom['value'])
		$price_custom['is_default'] = TRUE;

	// Free is NOT represented as per_hour with value of 0. Should it be?
	$price_free = array(
		'value'	=> 'free',
		'is_default' => FALSE
	);

	if ($user['price_type'] == $price_free['value'])
		$price_free['is_default'] = TRUE;

	$reason = array(
		'name' => 'reason',
		'id' => 'reason',
		'class'			=> '',
		'maxlength' 	=> 600,
		'placeholder'	=> 'e.g. Looking for experience, volunteering, just feel like it, etc.',
		'value'			=> $user['reason']
	);

	$currency_name = 'currency';
	$currency = $currencies_for_selects;
/*
	$currency = array(
		'Frequently Used' => array(
			'CAD' => 'CAD',
			'USD' => 'USD',
			'EUR' => 'EUR',
			'GBP' => 'GBP',
			'AUD' => 'AUD'
		),
		'Alphabetical' => array(
			'AFN' => 'AFN',
			'ALL' => 'ALL',
			'DZD' => 'DZD',
			'ARS' => 'ARS',
			'BSD' => 'BSD',
			'BHD' => 'BHD',
			'BDT' => 'BDT',
			'BBD' => 'BBD',
			'BMD' => 'BMD',
			'BRL' => 'BRL',
			'BGN' => 'BGN',
			'XOF' => 'XOF',
			'XAF' => 'XAF',
			'CLP' => 'CLP',
			'CNY' => 'CNY',
			'CNY' => 'CNY',
			'COP' => 'COP',
			'XPF' => 'XPF',
			'CRC' => 'CRC',
			'HRK' => 'HRK',
			'CZK' => 'CZK',
			'DKK' => 'DKK',
			'DOP' => 'DOP',
			'XCD' => 'XCD',
			'EGP' => 'EGP',
			'EEK' => 'EEK',
			'FJD' => 'FJD',
			'HKD' => 'HKD',
			'HUF' => 'HUF',
			'ISK' => 'ISK',
			'INR' => 'INR',
			'IDR' => 'IDR',
			'IRR' => 'IRR',
			'IQD' => 'IQD',
			'ILS' => 'ILS',
			'JMD' => 'JMD',
			'JPY' => 'JPY',
			'JOD' => 'JOD',
			'KES' => 'KES',
			'KRW' => 'KRW',
			'KWD' => 'KWD',
			'LBP' => 'LBP',
			'MYR' => 'MYR',
			'MUR' => 'MUR',
			'MXN' => 'MXN',
			'MAD' => 'MAD',
			'NZD' => 'NZD',
			'NOK' => 'NOK',
			'OMR' => 'OMR',
			'PKR' => 'PKR',
			'PEN' => 'PEN',
			'PHP' => 'PHP',
			'PLN' => 'PLN',
			'QAR' => 'QAR',
			'RON' => 'RON',
			'RUB' => 'RUB',
			'SAR' => 'SAR',
			'SGD' => 'SGD',
			'ZAR' => 'ZAR',
			'KRW' => 'KRW',
			'LKR' => 'LKR',
			'SDG' => 'SDG',
			'SEK' => 'SEK',
			'CHF' => 'CHF',
			'TWD' => 'TWD',
			'THB' => 'THB',
			'TTD' => 'TTD',
			'TND' => 'TND',
			'TRY' => 'TRY',
			'AED' => 'AED',
			'VEF' => 'VEF',
			'VND' => 'VND',
			'ZMK' => 'ZMK'
		)
	);
*/

	$years = array();
	$current_year = date('Y');
	$first_year = 1950;
	for($year = $current_year; $year >= $first_year; $year--)
	{
		$years[$year] = $year;
	}

	$graduated = array();
	$graduated = $years;

	// Need to do this work around to get NOT_GRADUATED_TEXT to appear at the top of the select box
    $graduated = array_reverse($graduated, true); 
    $graduated['0'] = NOT_GRADUATED_TEXT; 
    $graduated = array_reverse($graduated, true); 

	$months = array
	(
		'(Month)',
		'January',
		'February',
		'March',
		'April',
		'May',
		'June',
		'July',
		'August',
		'September',
		'October',
		'November',
		'December'
	);

	/* Gender attributes */

	$gender_name = 'gender';

	$gender_male = array(
		'name'	=> $gender_name,
		'value'	=>	'm',
		'is_default' => FALSE
	);

	if ($user['gender'] == 'Male')
		$gender_male['is_default'] = TRUE;

	$gender_female = array(
		'name'	=> $gender_name,
		'value'	=>	'f',
		'is_default' => FALSE
	);

	if ($user['gender'] == 'Female')
		$gender_female['is_default'] = TRUE;

	$gender_undisclosed = array(
		'name'	=> $gender_name,
		'value'	=>	'u',
		'is_default' => FALSE
	);

	if (!($user['gender'] == 'Male' || $user['gender'] == 'Female'))
		$gender_undisclosed['is_default'] = TRUE;

	$about = array(
		'name'			=> 'about',
		'id'			=> 'about-input',
		'value'			=> $user['about']
	);

	$snippet = array(
		'name'			=> 'snippet',
		'id'			=> 'snippet-input',
		'maxlength' => SNIPPET_MAX_LENGTH,
		'value'			=> $user['snippet']
	);

	$education = array(
		'school' => array(
			'name' => 'school',
			'id' => 'education-school',
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'schools',
			'maxlength' => 100
		),
		'field' => array(
			'name' => 'field',
			'id' => 'education-field',
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'fields',
			'maxlength' => 100
		),
		'degree' => array(
			'name' => 'degree',
			'id' => 'education-degree',
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'degrees',
			'maxlength' => 100,
			'placeholder' => 'e.g. BA, MEng, Certificate, etc.'
		),
		'start-year' => array(
			'name' => 'start-year',
			'class' => 'year-selects'
		),
		'end-year' => array(
			'name' => 'end-year',
			'class' => 'year-selects'
		),
		'notes' => array(
			'name' => 'notes',
			'id' => 'education-notes',
			'placeholder' => 'e.g. Graduated with honors, GPA of 3.0/4.0, President of Table-Top Games Club, etc.'
		)
	);

	$experience = array
	(
		'company' => array(
			'name' => 'company',
			'id' => 'experience-company',
/*
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'companies',
*/
			'maxlength' => 100
		),
		'position' => array(
			'name' => 'position',
			'id' => 'experience-position',
/*
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'positions',
*/
			'maxlength' => 100
		),
		'location' => array(
			'name' => 'location',
			'class' => ' autocompleted',
			'data-autocomplete-source' => 'locations',
			'id' => 'experience-location',
			'maxlength' => 100
		),
		'start-month' => array(
			'name' => 'start-month',
			'class' => 'month-selects'
		),
		'start-year' => array(
			'name' => 'start-year',
			'class' => 'year-selects'
		),
		'end-month' => array(
			'name' => 'end-month',
			'class' => 'month-selects'
		),
		'end-year' => array(
			'name' => 'end-year',
			'class' => 'year-selects'
		),
		'current' => array(
			'name'	=> 'current',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE
		),
		'description' => array(
			'name' => 'description',
			'id' => 'experience-description',
			'placeholder' => 'What did you do while working here?'
		)
	);	

	$can_meet = array(
		'students_home' => array(
			'name'	=> 'can-meet-students-home',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE,
			'checked' => $user['can_meet']['students_home']
		),
		'tutors_home' => array(
			'name'	=> 'can-meet-tutors-home',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE,
			'checked' => $user['can_meet']['tutors_home']
		),
		'centre' => array(
			'name'	=> 'can-meet-centre',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE,
			'checked' => $user['can_meet']['centre']
		),
		'public' => array(
			'name'	=> 'can-meet-public',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE,
			'checked' => $user['can_meet']['public']
		),
		'online_local' => array(
			'name'	=> 'can-meet-online-local',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE,
			'checked' => $user['can_meet']['online_local']
		),
		'online_distant' => array(
			'name'	=> 'can-meet-online-distant',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE,
			'checked' => $user['can_meet']['online_distant']
		)
	);


	$volunteering = array(
		'company' => array(
			'name' => 'company',
			'id' => 'volunteer-company',
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'companies',
			'maxlength' => 100
		),
		'position' => array(
			'name' => 'position',
			'id' => 'volunteer-position',
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'positions',
			'maxlength' => 100
		),
		'location' => array(
			'name' => 'location',
			'class' => '',
			'id' => 'volunteer-location',
			'class' => 'autocompleted',
			'data-autocomplete-source' => 'locations',
			'maxlength' => 100
		),
		'start-month' => array(
			'name' => 'start-month',
			'class' => 'month-selects'
		),
		'start-year' => array(
			'name' => 'start-year',
			'class' => 'year-selects'
		),
		'end-month' => array(
			'name' => 'end-month',
			'class' => 'month-selects'
		),
		'end-year' => array(
			'name' => 'end-year',
			'class' => 'year-selects'
		),
		'current' => array(
			'name'	=> 'current',
			'class'	=> 'checkbox-inputs',
			'value'	=> TRUE
		),
		'description' => array(
			'name' => 'description',
			'id' => 'volunteer-description',
			'placeholder' => 'What did you do while volunteering here?'
		)
	);

	$travel_notes = array(
		'name'			=> 'travel-notes',
		'id'			=> 'travel-notes-input',
		'value'			=> $user['travel_notes'],
		'placeholder'	=> 'e.g. Up to 15 minutes away at no extra cost'
	);

	$subjects = array(
		'name'			=> 'subjects',
		'id'			=> 'subjects-input',
		'value'			=> $user['subjects_string']
	);

//	var_dump($user['main_subject']);

	$no_main_subject_text = 'None';
	$subjects_array_for_main['0'] = $no_main_subject_text;

	foreach($subjects_array as $subject)
	{
		$subjects_array_for_main[$subject] = $subject;
	}

	$main_subject = array(
		'name'			=> 'main-subject',
		'id'			=> 'main-subject-input',
		'default'		=> ($user['main_subject']['name'] ? $user['main_subject']['name'] : $no_main_subject_text)
	);
	
}

$tips = array(
	'links-description' => "This will appear when someone mouses over the link",
	'links-label' => "If you leave this blank, then the Web Address/URL will be used as the label",
	'er-url' => "All external reviews must be listed on another website. Write that website's address/URL here. This lets students see a review's original site and determine its trustworthiness.",
	'update-profile-link' => "Check this to update your profile link (URL) with your new name. Remember to update any links that you've made to your profile (e.g. from your blog, on your Twitter, in your email signature, etc.)",
	'tutor-center' => "In a tutoring company's building",
	'online-distant' => "Online over long distances (e.g. different countries)",
	'online-local' => "Online with a local student",
	'display-name' => "This name will show up on your profile. Your profile address/URL will also be based off of this.",
	'location' => "We never reveal your exact address. Your marker will show up on the map, but only in an approximate location.",
	'price' => "Leave the second price blank if you only offer one price",
	'education' => "e.g. college/university programs, work-related courses, workshops, etc.",
	'experience' => "e.g. years tutoring, current and previous jobs, etc.",
	'volunteering' => "e.g. overseas relief efforts, one-day volunteer work, weekly volunteering in your community, etc.",
	'about' => "e.g. hobbies and interests, teaching philosophy, notes about yourself, etc.<hr>The label 'Long Version' doesn't appear on your profile.",
	'availability' => "
	<ul>
		<li>&raquo; <b class='underlined'>Add times</b> - Click and drag from any box below to add times of each day to your availability.</li><br>
		<li>&raquo; <b class='underlined'>Remove times</b> - Click and drag from a <span style='color: #6f99b1;'>blue</span> box to remove times from your availability.</li><br>
		<li>&raquo; <b class='underlined'>Overnight hours</b> - Click 'Show overnight times'.</li>
	</ul>
	",
	'snippet' => "About you in ~5-15 words. This doesn't appear on your profile, but goes near your name in search results.
		<hr>
		Examples:
		<ul style='margin-left: 10px;'>
			<li>&raquo; Math and science tutor in Toronto</li>
			<li>&raquo; Award winning tutor, 10 years of experience</li>
			<li>&raquo; Love to teach, great with children</li>
		</ul>
	",
	'profile-edit-help' => "
	<ul>
		<li>&raquo; Edit your profile by clicking the <img class='edit-icons no-action' src=".base_url(ICON_EDIT)."> icons.</li>
		<li>&raquo; Edit your photo by clicking it.</li>
		<li>&raquo; Use the <img class='vert-move-icons no-action' src=".base_url(ICON_VERT_MOVE)."> icons to rearrange profile sections containing multiple items (e.g. Education).</li>
	</ul>
	<hr>
	All changes are updated when you click 'Save'.
	",
	'live-profile-button' => "This button only works on the actual profile."

);

if ($user['role'] != ROLE_STUDENT)
{
	$tips['main-subject'] = "Prioritize a subject to: 
			<ul class='main-subject-qtip-list'>
				<li>&raquo; Point out that you teach it on any profile shares (e.g. Facebook), and</li>
				<li>&raquo; List it with your name in any public search engines (e.g. Google)</li>
			</ul>
		Select '".$no_main_subject_text."' to not prioritize anything.<hr>As you add/remove subjects above, they will appear as options here.";
}
else
{
	$tips['profile-edit-help'] = "
	<ul>
		<li>&raquo; Edit your name by clicking the <img class='edit-icons no-action' src=".base_url(ICON_EDIT)."> icon.</li>
		<li>&raquo; Edit your photo by clicking it.</li>
	</ul>
	<hr>
	All changes are updated when you click 'Save'.
	";
}

$element_submit_container = 
'
<div class="submit-conts">
	<input type="submit" value="Save" class="buttons color-3-buttons"><input type="button" class="buttons cancel-buttons" value="Cancel">
</div>
';

$element_submit_delete_container =
'
<div class="submit-conts">
	<input type="submit" value="Save" class="buttons color-3-buttons"><input type="button" class="buttons cancel-buttons" value="Cancel"> <span class="remove-item-links danger-page-links">remove this item</span>
</div>
';

?>

<section id="account" class="cf pages containers">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

	<section class="edit-profile-headers <? if ($user['role'] == ROLE_STUDENT) echo 'student-profile-headers'; ?>">

	<? if ($user['role'] == ROLE_STUDENT): ?>
			<div class="post-profile-made">
				<div class="edit-profile-side-buttons">
					<span class="left-qtipped same-page-links no-pointer" title="<?= $tips['profile-edit-help'] ?>">Help</span>
				</div>

				
				<div class="profile-link-cont">
					Profile link: <?= anchor($user['profile_link'], 'tutorical.com/students/'.$this->session->userdata('username'), 'id="profile-link"') ?>
				</div>
			</div>
	<? else: ?>
		<? if (!$user['profile_made']): ?>
			<div class="pre-profile-made">
				<div class=" edit-profile-message">
					<div class='boxes'>
						<h2>Your profile is hidden from the public!</h2>
						<p>Edit your profile by clicking the <img class="edit-icons no-action" src="<?= base_url(ICON_EDIT) ?>"> icons.</p>

						<ul>To make your profile visible, fill in the following parts below:
							<li id="price-indicator" class="<? if ($user['price_type']) echo 'set'; ?>">&raquo; Price 
								<img id="edit-price" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="price-element" src="<?= base_url(ICON_EDIT) ?>">
								<span class="check-marks" title="Completed!">&#10004;</span>
							</li>
							<li id="subjects-indicator" class="<? if ($user['subjects_string']) echo 'set'; ?>">&raquo; Subjects 
								<img id="edit-subjects" title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="subjects-element" src="<?= base_url(ICON_EDIT) ?>" data-reference-id="subjects-item">
								<span class="check-marks" title="Completed!">&#10004;</span>
							</li>
							<li id="location-indicator" class="<? if ($user['location']) echo 'set'; ?>">&raquo; Location 
								<img id="edit-location" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="location-element" src="<?= base_url(ICON_EDIT) ?>">
								<span class="check-marks" title="Completed!">&#10004;</span>
							</li>
						</ul>
					</div>
					<div id="or-import">
						<p class="tiny-aftertext"><a href="javascript:void(0);" class="same-page-links" data-reveal-id="import-profile-modal" title="Import sections from your UniversityTutor.com or Acadam profiles"><b>Import a profile</b></a> (Acadam / UniversityTutor.com)</p>
					</div>
				</div>
				
			</div>
		<? else: ?>
			<div class="post-profile-made">
				<div class="edit-profile-side-buttons tutor-side-buttons">
					<? if ($user['role'] != ROLE_STUDENT): ?>
					<a href="javascript:void(0);" class="same-page-links" data-reveal-id="import-profile-modal" title="Import sections from your UniversityTutor.com or Acadam profiles">Import</a> | 
					<? endif; ?>
					<span class="help-links same-page-links no-pointer" title="<?= $tips['profile-edit-help'] ?>">Help</span>
				</div>

				<div class="show-hide-profile-cont-cont">

					<div class="hide-profile-cont show-hide-profile-conts <? if ($user['is_active']) echo 'active'; ?>">
						<span>
							<span class="reg-visible">Your profile is <b>visible</b> to the public.</span><span class="mob-visible">Profile <b>visible</b>.</span> <span class="buttons color-3-buttons show-hide-profile-links hide-profile">Hide Profile</span></span>
					</div>
					<div class="show-profile-cont show-hide-profile-conts <? if (!$user['is_active']) echo 'active'; ?>">
						<span class="reg-visible">Your profile is <b>hidden</b> to the public.</span><span class="mob-visible">Profile <b>hidden</b>.</span> <span class="buttons color-3-buttons show-hide-profile-links show-profile">Show Profile</span></span>
					</div>
					<img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="toggle-profile-loader"> 
				</div>
				<div class="profile-link-cont">
					Profile link: <?= anchor($user['profile_link'], 'tutorical.com/tutors/'.$this->session->userdata('username'), 'id="profile-link"') ?>
				</div>
			</div>
		<? endif; ?>
	<? endif; ?>
	</section>

	<section id="import-profile-modal" class="cf large-modals reveal-modal" data-reveal>
		<section class="popup cf">
			<header>				
				<h2>Import a profile</h2>
			    <a class="close-reveal-modal">&#215;</a>
			</header>

			<div class="ajax-overlays">
				<div class="ajax-overlays-bg"></div>
				<img alt="Loading..." class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
			</div>

			<div class="popup-body cf">
				<p>Select a website from which to import a profile:</p>
				<div class="import-items">
					<span class="toggle-buttons buttons large-submit-on-mobile no-top-margin-on-mobile" data-toggle-container="#import-universitytutor-options">UniversityTutor.com</span>
					<br>
					<div id="import-universitytutor-options" class="import-options boxes" data-additional-height="55">
						<form class="import-forms">
							<div class="form-elements">
								<?= form_label('Import the following <span class="tiny-aftertext">(<a href="javascript:void(0);" class="same-page-links check-all" title="Check all of the below options">all</a> | <a href="javascript:void(0);" class="same-page-links check-none" title="Uncheck all of the below options">none</a>)</span>', '', array('class' => 'block-labels')) ?>
								<div class="form-inputs block-inputs no-top-margin-inputs">
									<div class="checkbox-rows">
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="display_name" /> Name
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="photo" /> Photo (<span class="same-page-links no-pointer" title="This will not import the default UniversityTutor photo (if you haven't changed it)">?</span>)
											</label>
										</div>
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="subjects" /> Subjects
											</label> 
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="price" /> Price
											</label>
										</div>
									</div>
									<div class="checkbox-rows">
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="gender" /> Gender
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="availability" /> Availability (<span class="same-page-links no-pointer" title="Taken from 'Days/Times' on your UniversityTutor profile">?</span>)
											</label>
										</div>
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="location" /> Location
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="travel_notes" /> Travel Notes (<span class="same-page-links no-pointer" title="Taken from 'Can Meet' on your UniversityTutor profile">?</span>)
											</label>
										</div>
									</div>
									<div class="checkbox-rows">
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="about" data-change-selector="#about-import-element" /> About (<span class="same-page-links no-pointer" title="Taken from 'Education', 'Experience', or 'Hobbies' on your UniversityTutor profile. Pick which one below.">?</span>)
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="reviews" /> Reviews (<span class="same-page-links no-pointer" title="These are listed as External Reviews. They don't affect your Tutorical rating, but can be seen on your profile.">?</span>)
											</label>
										</div>
									</div>
									<div class="form-input-notes checkbox-notes error-messages"></div>
								</div>
							</div>
							
							<div class="form-elements change-conts" id="about-import-element">
								<?= form_label('For <i>About</i>, use <span class="debold">(<span class="same-page-links no-pointer" title="While UniversityTutor doesn\'t have an About section, Tutorical does. We can import one of the other text sections into your profile\'s About section">?</span>)</span>', '', array('class' => 'block-labels')) ?>
								<div class="form-inputs block-inputs no-top-margin-inputs">
									<div class="radio-rows">
										<label class="radio-option-conts">
											<input type="radio" name="import-for-about" value="education" /> Education
										</label>
										<label class="radio-option-conts">
											<input type="radio" name="import-for-about" value="experience" /> Experience
										</label>
										<label class="radio-option-conts">
											<input type="radio" name="import-for-about" value="hobbies" checked="checked" /> Hobbies
										</label>
									</div>
									<div class="form-input-notes checkbox-notes error-messages"></div>
								</div>
							</div>
							<div class="form-elements">
								<label for="universitytutor-url">Profile URL/Address</label>

								<div class="form-inputs block-inputs">
									<input type="text" id="universitytutor-url" class="" name="import-url" value="" placeholder="e.g. universitytutor.com/tutors/1234">
									<div class="form-input-notes error-messages"></div>
								</div>
							</div>
							<input type="hidden" value="universitytutor" name="import-type">
							<input type="submit" value="Import" class="buttons color-3-buttons large-submit-on-mobile no-top-margin-on-mobile">
						</form>
					</div>
				</div>
				<div class="import-items">
					<span class="toggle-buttons buttons large-submit-on-mobile no-top-margin-on-mobile" data-toggle-container="#import-acadam-options">Acadam</span>
					<br>
					<div id="import-acadam-options" class="import-options boxes" data-additional-height="55">
						<form class="import-forms">
							<div class="form-elements">
								<?= form_label('Import the following <span class="tiny-aftertext">(<a href="javascript:void(0);" class="same-page-links check-all" title="Check all of the below options">all</a> | <a href="javascript:void(0);" class="same-page-links check-none" title="Uncheck all of the below options">none</a>)</span>', '', array('class' => 'block-labels')) ?>
								<div class="form-inputs block-inputs no-top-margin-inputs">
									<div class="checkbox-rows">
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="display_name" /> Name
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="photo" data-change-selector="#photo-import-element" /> Photo (<span class="same-page-links no-pointer" title="This will not import the default Acadam photo (if you haven't changed it)">?</span>)
											</label>
										</div>
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="subjects" /> Subjects
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="price" /> Price
											</label>
										</div>
									</div>
									<div class="checkbox-rows">
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="gender" /> Gender
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="availability" /> Availability
											</label>
										</div>
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="location" /> Location
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="can_meet" /> Can Meet (<span class="same-page-links no-pointer" title="Taken from 'Teaching Locations' on your Acadam profile">?</span>)
											</label>
										</div>
									</div>
									<div class="checkbox-rows">
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="education" /> Education (<span class="same-page-links no-pointer" title="Acadam doesn't list the 'Start Year' of your degree, so we estimate it at 4 years before the completion date. After importing, you can change this value.">?</span>)
											</label>
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="reviews" /> Reviews (<span class="same-page-links no-pointer" title="These are listed as External Reviews. They don't affect your Tutorical rating, but can be seen on your profile.">?</span>)
											</label>
										</div>
										<div class="two-checkbox-items">
											<label class="checkbox-option-conts">
												<input type="checkbox" name="<?= $import_sections_name ?>" class="import-sections" value="about" /> About
											</label>
										</div>
									</div>
									<div class="form-input-notes checkbox-notes error-messages"></div>
								</div>
							</div>
							<div class="form-elements change-conts" id="photo-import-element">
								<?= form_label('For non-square photos <span class="debold">(<span class="same-page-links no-pointer" title="&raquo Crop - We\'ll cut the sides of the photo to make it a square
								&raquo Fit - We\'ll add space around your photo to make it a square">?</span>)</span>', '', array('class' => 'block-labels')) ?>
								<div class="form-inputs block-inputs no-top-margin-inputs">
									<div class="radio-rows">
										<label class="radio-option-conts">
											<input type="radio" name="import-photo-manipulation" value="crop" checked="checked" /> Crop
										</label>
										<label class="radio-option-conts">
											<input type="radio" name="import-photo-manipulation" value="fit" /> Fit
										</label>
									</div>
									<div class="form-input-notes checkbox-notes error-messages"></div>
								</div>
							</div>
							<div class="form-elements">
								<label for="acadam-url">Profile URL/Address</label>
								<div class="form-inputs block-inputs">
									<input type="text" id="acadam-url" class="" value="" name="import-url" placeholder="e.g. acadam.com/eng/1234/english-tutor-in-toronto">
									<div class="form-input-notes error-messages"></div>
								</div>
							</div>
							<input type="hidden" value="acadam" name="import-type">
							<input type="submit" value="Import" class="buttons color-3-buttons large-submit-on-mobile no-top-margin-on-mobile">
						</form>
					</div>
				</div>
			</div>
		</section>
	</section>

	<section id="edit-profile" class="profiles cf pages containers <? if ($user['role'] == ROLE_STUDENT) echo 'student-profiles'; ?>">
	
		<section id="jcrop-interface" class="reveal-modal" data-reveal>
			<section class="popup cf">
				<header>
					<h2>Crop this picture of yourself</h2>
				    <a class="close-reveal-modal">&#215;</a>
				</header>
				<div class="ajax-overlays">
					<div class="ajax-overlays-bg"></div>
					<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
				</div>
				<div class="popup-body">
					<p id="jcrop-intro-text">Drag the box below to select the crop area, and use the handle to resize it. <span class="same-page-links" id="no-handles-link">Don't see the box?</span></p>
					
					<img src="" id="jcrop-target" />

					<div id="jcrop-preview-cont-parent"> <!-- lulwut? -->	
						<div id="jcrop-preview-cont" style="">
							<img src="" id="jcrop-preview" alt="Preview" class="jcrop-preview" />
						</div>
						<span id="preview-label">Preview</span>					
					</div>

					<input type="hidden" id="x1" name="x1">
					<input type="hidden" id="y1" name="y1" >
					<input type="hidden" id="w" name="w">
					<input type="hidden" id="h" name="h">
					<div id="jcrop-confirm-cancel-cont">
						<button type="button" class="buttons color-3-buttons" id="confirm-jcrop">Crop and Save</button>
						<span class="same-page-links cancel-buttons" id="cancel-jcrop">Cancel</span>
					</div>
				</div>
			</section>
		</section>

		<? if ($user['role'] != ROLE_STUDENT): ?>
			<div class="absolute-profile-edit-elements" data-item-type="availability" data-edit-type="only" id="availability-element">
				<?= form_open() ?>
					<div class="ajax-overlays">
						<div class="ajax-overlays-bg"></div>
						<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
					</div>
					<input type="hidden" name="update-key" value="availability">
					<div class="form-elements">
						<?= form_label('When would you like to teach? <span class="tiny-aftertext">(<span class="same-page-links no-pointer downwards-qtipped" title="'.$tips['availability'].'">how to use this</span>)</span>', '', array('class' => 'block-labels')) ?>
						<?= $user['availability']; ?>
					</div>
					<?= $element_submit_container ?>
				<?= form_close() ?>
			</div>
		<? endif; ?>

		<div class="col-1">

			<section id="user-intro" class="profile-secs cf">
				<div id="review-cover"></div>
				<div id="user-avatar-cont" data-dropdown="#dropdown-avatar-edit">
					<? // Use session to avoid importing caching error ?>
					<div class="ajax-overlays">
						<div class="ajax-overlays-bg"></div>
						<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
					</div>
					<div id="avatar-edit-overlay">
						<div class="bgs"></div>
						<span class="avatar-edit-text">Change<span class="hide-on-small-view"> Photo</span></span>
					</div>
					<div class="vertically-aligning-ghost"></div><img id="user-avatar" src="<?= $this->session->userdata('avatar_url') ?>">
					<div id="avatar-edit"></div>
					<input type="hidden" id="avatar-path" name="avatar-path" value="<?= $user['avatar_path'] ?>">
				</div>
				<div id="dropdown-avatar-edit" class="dropdown dropdown-relative dropdown-tip">
					<div class="ajax-overlays">
						<div class="ajax-overlays-bg"></div>
						<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
					</div>
					<ul class="dropdown-menu">
						<li id="avatar-edit-cont">
							<span>
								<span id="avatar-edit-link" class="" title=""></span>
								Upload photo
							</span>
						</li>
						<li class="dropdown-divider"></li>
						<li>
							<span id="remove-photo-link">Remove photo</span>
						</li>
					</ul>
				</div>
				<h1 id="user-name">
					<span>
						<span id="user-name-content"><?= $display_name ?></span>
						<img id="edit-display-name" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="display-name-element" src="<?= base_url(ICON_EDIT) ?>">
					</span>
				</h1>
				<div class="absolute-profile-edit-elements form-elements" id="display-name-element">
					<?= form_open() ?>
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>
						<input type="hidden" name="update-key" value="display_name">

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
							</div>
						</div>
						<div class="form-elements">
							<div class="form-inputs block-inputs checkbox-divs">
								<?= form_label(form_checkbox($abbreviate_last_name) . "Abbreviate Last Name"); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<div class="form-elements">
							<div class="form-inputs block-inputs checkbox-divs">
								<?= form_label(form_checkbox($update_profile_link) . "Update Profile Link"); ?> <span>(<span class="same-page-links no-pointer" title="<?= $tips['update-profile-link'] ?>">?</span>)</span>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<?= $element_submit_container ?>
					<?= form_close() ?>
				</div>

				<div id="user-left-intro-bits">
				<? if ($user['role'] == ROLE_STUDENT): ?>
					<span id="user-joined">Joined: <span><?= $user['joined'] ?></span></span>
				<? else: ?>
					<div id="text-location">
						<img id="flag" src="<?= $user['flag_url'] ?>">
						<span id="city-country">
							<span id="city"><?= $user['city'] ?: '<span class="default-values">( City )</span>' ?></span>, <span id="country"><?= $user['country'] ?: '<span class="default-values">( Country )</span>' ?></span>
						</span>
						<img id="edit-location" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="location-element" src="<?= base_url(ICON_EDIT) ?>">
					</div>
					<div class="absolute-profile-edit-elements form-elements" id="location-element">
						<?= form_open() ?>

							<div class="ajax-overlays">
								<div class="ajax-overlays-bg"></div>
								<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
							</div>
							<input type="hidden" name="update-key" value="location">

							<div id="location-text">

								<div class="form-elements">
									<?= form_label('Location <span class="tiny-aftertext">(<span class="same-page-links no-pointer" title="'.$tips['location'].'">privacy</span>)</span>', $location['id'], array('class' => 'block-labels')); ?>
									<div id="location-gmap"></div>
									<div class="form-inputs block-inputs">
										<?= form_input($location); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>

								<div id="location-examples">
									<div class="examples-conts">
										<span class="example-label good">Good:</span>
										<span class="examples">"10 Bath St, London"</span>
										<span class="examples">"Ryerson University, Toronto, Canada"</span>
									</div>
									<div class="examples-conts">
										<span class="example-label bad">Bad:</span>
										<span class="examples">"England"</span>
										<span class="examples">"Toronto, Canada"</span>
									</div>
								</div>

							</div>
							
							<input type="hidden" id="lat" name="lat" value="<?= $user['lat'] ?>">
							<input type="hidden" id="lon" name="lon" value="<?= $user['lon'] ?>">
							<input type="hidden" id="country-val" name="country" value="<?= $user['country'] ?>">
							<input type="hidden" id="city-val" name="city" value="<?= $user['city'] ?>">
							<input type="hidden" id="specific-val" name="specific" value="<?= $user['specific_location'] ?>">
						<?= $element_submit_container ?>
					<?= form_close() ?>
					</div>
					<div id="gender-age">
						<? if ($user['gender']): ?>
							<span id="gender"><?= $user['gender'] ?></span>
						<? else: ?>
							<span id="gender"><span class="default-values">( Gender )</span></span>
						<? endif; ?>
						<img id="edit-gender-age" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="gender-element" src="<?= base_url(ICON_EDIT) ?>">
					</div>
					<div class="form-elements absolute-profile-edit-elements" id="gender-element">
						<?= form_open() ?>
							<div class="ajax-overlays">
								<div class="ajax-overlays-bg"></div>
								<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
							</div>
							<input type="hidden" name="update-key" value="gender">

							<div class="form-elements">
								<?= form_label('Gender', '', array('class' => 'block-labels', 'id' => 'gender-label')) ?>	
								<div class="form-inputs block-inputs no-top-margin-inputs">
									<label class="radio-option-conts">
										<input type="radio" name="<?= $gender_name ?>" value="<?= $gender_undisclosed['value'] ?>" <?= set_radio($gender_name, $gender_undisclosed['value'], $gender_undisclosed['is_default']); ?> /> Undisclosed/Other <span class="tiny-aftertext">(won't show up on profile)</span>
									</label>
									<label class="radio-option-conts">
										<input type="radio" name="<?= $gender_name ?>" value="<?= $gender_male['value'] ?>" <?= set_radio($gender_name, $gender_male['value'], $gender_male['is_default']); ?> /> Male
									</label>
									<label class="radio-option-conts">
										<input type="radio" name="<?= $gender_name ?>" value="<?= $gender_female['value'] ?>" <?= set_radio($gender_name, $gender_female['value'], $gender_female['is_default']); ?> /> Female
									</label>
									<div class="form-input-notes radio-notes error-messages"></div>
								</div>
							</div>

							<?= $element_submit_container ?>

						<?= form_close() ?>
					</div>
					<div id="user-right-intro-bits">
						<div class="price-conts">
							<div class="prices">
								<span class="price-val">
								<? if ($user['hourly_rate_high'] > 0): ?>

									<span class="hourly-prices"><?= $currency_sign.$user['hourly_rate'] ?> &#8211; <?= $currency_sign.$user['hourly_rate_high'] ?><span class="per-hour"> / hour</span> <span class="currencies">(<?= $user['currency'] ?>)</span></span>

								<? elseif ($user['price_type'] == 'per_hour'): ?>

									<span class="hourly-prices"><?= $currency_sign.$user['hourly_rate'] ?><span class="per-hour"> / hour</span> <span class="currencies">(<?= $user['currency'] ?>)</span></span>

								<? elseif ($user['price_type'] == 'free'): ?>

										<span class="frees">Free</span>

										<? if ($user['reason']): ?>
											<span class="reason-for-frees"><span class="tiny-aftertext">(<span class="same-page-links no-pointer" title="<?= nl2br($user['reason']) ?>">why?</span>)</span></span>
										<? endif; ?>

								<? else: ?>
										<span class="default-values">( Price )</span>
								<? endif; ?>
								</span>
							</div>
							<img id="edit-prices" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="price-element" src="<?= base_url(ICON_EDIT) ?>">
						</div>

						<div class="form-elements absolute-profile-edit-elements" id="price-element">
							<?= form_open() ?>
								<div class="ajax-overlays">
									<div class="ajax-overlays-bg"></div>
									<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">		
								</div>
								<input type="hidden" name="update-key" value="price">

								<div class="form-elements">
									<?= form_label('Price <span class="tiny-aftertext">(<span class="same-page-links no-pointer" title="'.$tips["price"].'">note</span>)</span>', $hourly_rate['id'], array('class' => 'block-labels')) ?>	
									<div class="form-inputs block-inputs no-top-margin-inputs">
										<label class="radio-option-conts">
											<input type="radio" name="<?= $price_name ?>" value="<?= $price_hourly['value'] ?>" <?= set_radio($price_name, $price_hourly['value'], $price_hourly['is_default']); ?> /> <?= form_input($hourly_rate); ?> to <?= form_input($hourly_rate_high) ?> per hour in <?= form_dropdown($currency_name, $currency, $user["currency"], 'id="currency" class="currency-selects"') ?>
										</label>
										<label class="radio-option-conts">
											<input type="radio" name="<?= $price_name ?>" value="<?= $price_free['value'] ?>" <?= set_radio($price_name, $price_free['value'], $price_free['is_default']); ?> /> Free
										</label>
										<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $price_name ?>"></div>
										<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $hourly_rate['name'] ?>"></div>
										<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $hourly_rate_high['name'] ?>"></div>
										<div class="form-input-notes radio-notes error-messages" data-input-name="<?= $currency_name ?>"></div>
									</div>
								</div>

								<div class="form-elements radio-subfields" id="reason-cont">
										<?= form_label('Why free? <span class="tiny-aftertext">(<span>optional</span>)</span>', $reason['id'], array('class' => 'block-labels')) ?>
										<div class="form-inputs block-inputs">
											<?= form_textarea($reason) ?>
											<div class="form-input-notes error-messages"></div>
										</div>

								</div>

								<?= $element_submit_container ?>
							<?= form_close() ?>
						</div>
					</div>
				<? endif; ?>

				</div>
			</section>

			<div id="profile-section-selection-cont">
				<span class="link-like active" title="" data-target="#user-details">Details</span> | 
				<span class="link-like" title="" data-target="#tutor-location">Location</span> | 
				<span class="link-like" title="" data-target="#tutor-availability">Availability</span> | 

				<span class="link-like" title="" data-target="#user-external-reviews-sec">Reviews</span> |
				<span class="link-like" title="" data-target="#tutor-links">Links</span> | 
				<span class="link-like" title="" data-target="#user-contact">Contact</span>
			</div>

			<? if ($user['role'] != ROLE_STUDENT): ?>

			<section id="user-details" class="profile-secs">

				<div id="subjects-cont" class="details-secs">
					<div class="profile-labels">
						<img class="profile-label-images" id="subjects-image" src="<?= base_url('assets/images/profile/subjects.png') ?>">
						<span class="profile-label-content">Subjects</span>
						<img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="subjects-element" data-reference-id="subjects-item" src="<?= base_url(ICON_EDIT) ?>">
					</div>

					<div id="subjects" class="profile-sec-contents">
						<div class="block-items" id="subjects-item" data-item-type="subjects">
							<div class="block-items" id="subjects-item" data-item-type="subjects">
								<? foreach($user['subjects_array'] as $subject): 
								?><span class="subject-names"><?= $subject ?></span><? endforeach; ?>
							</div>
						</div>

						<div class="block-profile-edit-elements" data-item-type="subjects" id="subjects-element" data-additional-height="100">
							<?= form_open() ?>
								<div class="ajax-overlays">
									<div class="ajax-overlays-bg"></div>
									<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
								</div>
								<input type="hidden" name="update-key" value="subjects">
		
								<div class="form-elements">
									<?= form_label('Which subjects do you teach? <span class="tiny-aftertext">(separate with commas)</span>', $subjects['id'], array('class' => 'block-labels')) ?>
									<div class="form-inputs block-inputs">
								        <input type="hidden" id="<?= $subjects['id'] ?>" name="<?= $subjects['name'] ?>" value="<?= $subjects['value'] ?>"/>
								        <div class="form-input-notes error-messages"></div>
								    </div>
								</div>
								<div class="form-elements" id="main-subject-element">
									<?= form_label('Priority Subject <span class="debold">(<span class="same-page-links no-pointer qtipped" title="'.$tips['main-subject'].'">?</span>)</span>', $main_subject['id'], array('class' => 'block-labels')) ?>
									<div class="form-inputs block-inputs">
										<?= form_dropdown($main_subject['name'], $subjects_array_for_main, $main_subject['default'], "id='main-subject-input'") ?>
								        <div class="form-input-notes error-messages"></div>
								    </div>
								</div>
								<?= $element_submit_container ?>
							<?= form_close() ?>
						</div>
					</div>
				</div>

				<div id="education-cont" class="details-secs">
					<div class="profile-labels">
						<img class="profile-label-images" id="education-image" src="<?= base_url('assets/images/profile/education.png') ?>">
						<span class="profile-label-content">Education</span><br>
						<span class="tiny-aftertext">
							(<span class="same-page-links no-pointer" title="<?= $tips['education'] ?>">what?</span>)
						</span>
					</div>

					<div id="education" class="profile-sec-contents">
						<div id="education-item-cont" data-type="education" class="item-conts">
						<? foreach($user['education'] as $item): ?>
							<?// var_dump($item); ?>
							<div class="education-items block-items" id="education-<?= $item['id'] ?>" data-item-type="education" data-item-id="<?= $item['id'] ?>">
			
								<div class="school-conts">
									<span class="schools"><?= $item['school'] ?></span>
									<span class="icon-conts">
										<img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="education-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>">
									</span>
								</div>
								<div class="studied">
									<div class="degrees-fields" style="<? if (!($item['degree'] || $item['field'])) echo ' display: none; '; ?> ">
										<span class="degrees"><?= $item['degree'] ?></span><span class="degree-field-divs" style=" <? if (!($item['degree'] && $item['field'])) echo ' display: none; '; ?> "> - </span><span class="fields"><?= $item['field'] ?></span>
									</div>
									<div class="starts-ends">
										<span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span>
									</div>
								</div>

								<div class="education-notes" style="<?= ($item['notes'] ? '' : 'display: none;') ?>"><?= nl2br($item['notes']) ?></div>
							</div>
						<? endforeach; ?>

						</div>

						<div title="Add an education item" class="add-profile-item-links buttons" data-type="education" data-edit-element-id="education-element"><span class="">» Add</span></div>

						<div class="block-profile-edit-elements" data-item-type="education" id="education-element">
							<?= form_open() ?>

								<div class="required-text tiny-aftertext"><span class="required-markers">*</span> = required</div>
								<div class="ajax-overlays">
									<div class="ajax-overlays-bg"></div>
									<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
								</div>
								<input type="hidden" name="update-key" value="education">
								<input type="hidden" name="item-id" value="">
								<div class="form-elements first">
									<?= form_label('School <span class="required-markers">*</span>', $education['school']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($education['school']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Field', $education['field']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($education['field']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Degree', $education['degree']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($education['degree']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('From', '', array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_dropdown($education['start-year']['name'], $years, '', "class='{$education['start-year']['class']}'") ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('To', '', array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
											<?= form_dropdown($education['end-year']['name'], $graduated, null, "class='{$education['end-year']['class']}'") ?>
											<div class="form-input-notes error-messages"></div>
									</div>
								</div>

								<div class="form-elements">
									<?= form_label('Notes', $education['notes']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_textarea($education['notes']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<?= $element_submit_delete_container ?>
							<?= form_close() ?>
						</div>
					</div>
				</div>

				<div id="experience-cont" class="details-secs">
					<div class="profile-labels">
						<img class="profile-label-images" id="experience-image" src="<?= base_url('assets/images/profile/experience.png') ?>">
						<span class="profile-label-content">Experience</span><br>
						<span class="tiny-aftertext">
							(<span class="same-page-links no-pointer" title="<?= $tips['experience'] ?>">what?</span>)
						</span>
					</div>

					<div id="experience" class="profile-sec-contents">
						<div id="experience-item-cont" data-type="experience" class="item-conts">
						<? foreach($user['experience'] as $item): ?>
							<div class="experience-items block-items" id="experience-<?= $item['id'] ?>" data-item-type="experience" data-item-id="<?= $item['id'] ?>">

								<div class="companies">
									<? if ($item['company']): ?>
										<span class="company-values"><?= $item['company'] ?></span>
									<? else: ?>
										<span class="company-values self-employed">Self-Employed</span>
									<? endif; ?>
									<span class="icon-conts">
										<img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="experience-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>">
									</span>
								</div>
								<div class="positions-dates-locations">
									<span class="positions"><?= $item['position'] ?></span>
									<div class="dates-locations">
										<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span></span>
									</div>
								</div>

								<div class="descriptions" style="<?= ($item['description'] ? '' : 'display: none;') ?>"><?= nl2br($item['description']) ?></div>
							</div>
						<? endforeach; ?>
						</div>

						<div title="Add an experience item" class="add-profile-item-links buttons" data-type="experience" data-edit-element-id="experience-element"><span class="">» Add</span></div>

						<div class="block-profile-edit-elements" data-item-type="experience" id="experience-element">
							<?= form_open() ?>
								<div class="required-text tiny-aftertext"><span class="required-markers">*</span> = required</div>
								<div class="ajax-overlays">
									<div class="ajax-overlays-bg"></div>
									<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
								</div>
								<input type="hidden" name="update-key" value="experience_volunteering">
								<input type="hidden" name="diff-key" value="experiences">
								<input type="hidden" name="item-id" value="">
								<div class="form-elements first">
									<?= form_label('Company', $experience['company']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($experience['company']); ?>
										<div class="form-input-notes error-messages"></div>
										<div class="form-input-notes focus-messages">Leave blank for "Self-Employed"</div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Position <span class="required-markers">*</span>', $experience['position']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($experience['position']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Location <span class="required-markers">*</span>', $experience['location']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($experience['location']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('From', '', array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_dropdown($experience['start-month']['name'], $months, $months[0], "class='{$experience['start-month']['class']}'") ?>
										<?= form_dropdown($experience['start-year']['name'], $years, '', "class='{$experience['start-year']['class']}'") ?>
										<div class="form-input-notes error-messages" data-input-name="<?= $experience['start-month']['name'] ?>"></div>
										<div class="form-input-notes error-messages" data-input-name="<?= $experience['start-year']['name'] ?>"></div>
									</div>
								</div>
								<div class="form-elements" style="margin-bottom: 4px;">
									<?= form_label('To', '', array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<div class="reg-time-ends">
											<?= form_dropdown($experience['end-month']['name'], $months, $months[0], "class='{$experience['end-month']['class']}'") ?>
											<?= form_dropdown($experience['end-year']['name'], $years, null, "class='{$experience['end-year']['class']}'") ?>
											<div class="form-input-notes error-messages" data-input-name="<?= $experience['end-month']['name'] ?>"></div>
											<div class="form-input-notes error-messages" data-input-name="<?= $experience['end-year']['name'] ?>"></div>
										</div>

										<div class="current-time-ends">Present</div>

									</div>
								</div>							
								<div class="form-elements">
									<?= form_label(' ', '', array('class' => 'line-labels')); ?>
									<div class="current-time-div form-inputs line-inputs">
										<?= form_label(form_checkbox($experience['current']) . 'I currently work here'); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Description', $experience['description']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_textarea($experience['description']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<?= $element_submit_delete_container ?>
							<?= form_close() ?>
						</div>
					</div>
				</div>
				<div id="volunteering-cont" class="details-secs">
					<div class="profile-labels">
						<img class="profile-label-images" id="volunteer-image" src="<?= base_url('assets/images/profile/volunteer.png') ?>">
						<span class="profile-label-content">Volunteer <br>Work</span>
						<br>
						<span class="tiny-aftertext">
							(<span class="same-page-links no-pointer" title="<?= $tips['volunteering'] ?>">what?</span>)
						</span>
					</div>

					<div id="volunteering" class="profile-sec-contents">
						<div id="volunteering-item-cont" data-type="volunteering" class="item-conts">
						<? foreach($user['volunteering'] as $item): ?>
							<div class="volunteering-items block-items" id="volunteering-<?= $item['id'] ?>" data-item-type="volunteering" data-item-id="<?= $item['id'] ?>">

								<div class="companies">
									<span class="company-values"><?= $item['company'] ?></span>
									<span class="icon-conts">
										<img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="volunteering-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>">
									</span>
								</div>
								<div class="positions-dates-locations">
									<span class="positions"><?= $item['position'] ?></span>
									<div class="dates-locations">
										<span class="start-months"><?= $item['start_month'] ?></span> <span class="start-years"><?= $item['start_year'] ?></span> - <span class="end-months"><?= ($item['end_month'] ?: '') ?></span> <span class="end-years"><?= ($item['end_year'] ?: 'Present') ?></span><span class="locations"> | <span class="location-values"><?= $item['location'] ?></span></span>
									</div>
								</div>

								<div class="descriptions" style="<?= ($item['description'] ? '' : 'display: none;') ?>"><?= nl2br($item['description']) ?></div>
							</div>
						<? endforeach; ?>
						</div>

						<div title="Add a volunteering item" class="add-profile-item-links buttons" data-type="volunteering" data-edit-element-id="volunteering-element"><span class="">» Add</span></div>

						<div class="block-profile-edit-elements" data-item-type="volunteering" id="volunteering-element">
							<?= form_open() ?>
								<div class="required-text tiny-aftertext"><span class="required-markers">*</span> = required</div>
								<div class="ajax-overlays">
									<div class="ajax-overlays-bg"></div>
									<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
								</div>
								<input type="hidden" name="update-key" value="experience_volunteering">
								<input type="hidden" name="diff-key" value="volunteerings">
								<input type="hidden" name="item-id" value="">
								<div class="form-elements first">
									<?= form_label('Organization <span class="required-markers">*</span>', $volunteering['company']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($volunteering['company']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Role <span class="required-markers">*</span>', $volunteering['position']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($volunteering['position']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Location <span class="required-markers">*</span>', $volunteering['location']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_input($volunteering['location']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('From', '', array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_dropdown($volunteering['start-month']['name'], $months, $months[0], "class='{$volunteering['start-month']['class']}'") ?>
										<?= form_dropdown($volunteering['start-year']['name'], $years, '', "class='{$volunteering['start-year']['class']}'") ?>
										<div class="form-input-notes error-messages" data-input-name="<?= $volunteering['start-month']['name'] ?>"></div>
										<div class="form-input-notes error-messages" data-input-name="<?= $volunteering['start-year']['name'] ?>"></div>
									</div>
								</div>
								<div class="form-elements" style="margin-bottom: 4px;">
									<?= form_label('To', '', array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<div class="reg-time-ends">
											<?= form_dropdown($volunteering['end-month']['name'], $months, $months[0], "class='{$volunteering['end-month']['class']}'") ?>
											<?= form_dropdown($volunteering['end-year']['name'], $years, null, "class='{$volunteering['end-year']['class']}'") ?>
											<div class="form-input-notes error-messages" data-input-name="<?= $volunteering['end-month']['name'] ?>"></div>
											<div class="form-input-notes error-messages" data-input-name="<?= $volunteering['end-year']['name'] ?>"></div>
										</div>

										<div class="current-time-ends">Present</div>

									</div>
								</div>							
								<div class="form-elements">
									<?= form_label(' ', '', array('class' => 'line-labels')); ?>
									<div class="current-time-div form-inputs line-inputs">
										<?= form_label(form_checkbox($volunteering['current']) . 'I currently volunteer here'); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<div class="form-elements">
									<?= form_label('Description', $volunteering['description']['id'], array('class' => 'line-labels')); ?>
									<div class="form-inputs line-inputs">
										<?= form_textarea($volunteering['description']); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<?= $element_submit_delete_container ?>
							<?= form_close() ?>
						</div>
					</div>
				</div>
				<div id="about-cont" class="details-secs">
					<div class="profile-labels">
						<img class="profile-label-images" id="about-image" src="<?= base_url('assets/images/profile/about.png') ?>">
						<span class="profile-label-content">About</span><br>
						<span class="tiny-aftertext">(<span class="same-page-links no-pointer" title="<?= $tips['about'] ?>">what?</span>)</span>
					</div>
					
					<div id="about" class="profile-sec-contents">
						<div class="profile-sec-subsections">
							<span class="profile-sec-subsection-headings">
								Snippet 
								<span class="debold">(<span class="same-page-links no-pointer downwards-qtipped" title="<?= $tips['snippet'] ?>">?</span>)</span>
								<img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="snippet-element" data-reference-id="snippet-item" src="<?= base_url(ICON_EDIT) ?>">
							</span>
							<div class="block-items" id="snippet-item" data-item-type="snippet">
								<?= nl2br($user['snippet']) ?>
							</div>
							<div class="block-profile-edit-elements in-subsection" data-item-type="snippet" data-edit-type="only" id="snippet-element" data-additional-height="70">
								<?= form_open() ?>
									<div class="ajax-overlays">
										<div class="ajax-overlays-bg"></div>
										<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
									</div>
									<input type="hidden" name="update-key" value="snippet">
									<div class="form-elements">
										<?= form_label('Snippet <span class="debold">(<span class="same-page-links no-pointer downwards-qtipped" title="'.$tips['snippet'].'">?</span>)</span>', $snippet['id'], array('class' => 'block-labels')); ?>
										<div class="form-inputs block-inputs">
											<?= form_input($snippet); ?>
											<div class="form-input-notes error-messages"></div>
										</div>
									</div>
									<?= $element_submit_container ?>
								<?= form_close() ?>
							</div>
						</div>
						<div class="profile-sec-subsections">
							<span class="profile-sec-subsection-headings">
								Long Version
								<span class="debold">(<span class="same-page-links no-pointer downwards-qtipped" title="<?= $tips['about'] ?>">?</span>)</span>
								<img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="about-element" data-reference-id="about-item" src="<?= base_url(ICON_EDIT) ?>">
							</span>

							<div class="block-items" id="about-item" data-item-type="about">
								<?= nl2br($user['about']) ?>
							</div>
							<div class="block-profile-edit-elements in-subsection" data-item-type="about" data-edit-type="only" id="about-element">
								<?= form_open() ?>
									<div class="ajax-overlays">
										<div class="ajax-overlays-bg"></div>
										<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
									</div>
									<input type="hidden" name="update-key" value="about">
									<div class="form-elements">
										<?= form_label('Long Version <span class="debold">(<span class="same-page-links no-pointer downwards-qtipped" title="'.$tips['about'].'">?</span>)</span>', $about['id'], array('class' => 'block-labels')); ?>
										<div class="form-inputs block-inputs">
											<?= form_textarea($about); ?>
											<div class="form-input-notes error-messages"></div>
										</div>
									</div>
									<?= $element_submit_container ?>
								<?= form_close() ?>
							</div>
						</div>
					</div>
				</div>
			</section>  <!-- /#user-details --> 

			<section id="user-external-reviews-sec" class="profile-secs">
				<header class="cf">
					<span class="same-page-links no-pointer left-qtipped" id="external-reviews-what" title="If you have any reviews or testimonials on other sites, you can add them here. They will not affect your average rating on the site, but they can improve your profile's appearance.<hr>For UniversityTutor.com and Acadam, you can use the 'Import' feature above to post all of your reviews from those sites.">What's this?</span>
					<h2>External Reviews</h2>
				</header>
				<div id="er-item-cont" data-type="er" class="item-conts"> 
					<? foreach($user['external_reviews'] as $item): ?>
						<div class="er-items block-items" id="er-<?= $item['id'] ?>" data-item-type="er" data-item-id="<?= $item['id'] ?>">
			
							<div class="er-ratings-and-icons">
								<div class="er-ratings" <? if ($item['rating'] == 0.0) { echo 'style="display: none;"'; } ?>>
									<form>
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 0.5) echo 'checked="checked"' ?> value="0.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 1) echo 'checked="checked"' ?> value="1.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 1.5) echo 'checked="checked"' ?> value="1.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 2) echo 'checked="checked"' ?> value="2.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 2.5) echo 'checked="checked"' ?> value="2.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 3) echo 'checked="checked"' ?> value="3.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 3.5) echo 'checked="checked"' ?> value="3.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 4) echo 'checked="checked"' ?> value="4.0" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 4.5) echo 'checked="checked"' ?> value="4.5" >
										<input name="rating" type="radio" disabled="disabled" class="star {split:2}" <? if ($item['rating'] == 5) echo 'checked="checked"' ?> value="5.0" >
									</form>
								</div>
								<div class="icon-conts">
									<img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="er-element" src="<?= base_url(ICON_EDIT) ?>">
									<img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>">
								</div>
							</div

							><div class="er-content-and-metas">
								<div class="er-contents">
									<?= nl2br($item['content']) ?>
								</div>

								<div class="er-metas">
									<span class="meta-items er-name-items">By: <span class="er-names"><?= $item['reviewer'] ?></span></span>
									|
									<a title="Visit the original website where this review was posted" target="_blank" rel="nofollow" href="<?= $item['url'] ?>" class="meta-items er-url-items">Visit Site</a>
								</div>
							</div>

						</div>
					<?  endforeach; ?>
				</div>
				<div title="Add an external review" class="add-profile-item-links buttons" data-type="er" data-edit-element-id="er-element"><span class="">» Add</span></div>

				<div class="block-profile-edit-elements" data-item-type="er" id="er-element">
					<?= form_open() ?>
						<div class="required-text tiny-aftertext"><span class="required-markers">*</span> = required</div>
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>
						<input type="hidden" name="update-key" value="external_review">
						<input type="hidden" name="item-id" value="">
						<div class="form-elements">
							<?= form_label('Reviewer <span class="required-markers">*</span>', NULL, array('class' => 'line-labels')); ?>
							<div class="form-inputs line-inputs">
								<?= form_input($external_reviews['reviewer']); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<div class="form-elements">
							<?= form_label('Web Address/URL <span class="debold">(<span class="same-page-links no-pointer" title="'.$tips['er-url'].'">?</span>)</span> <span class="required-markers">*</span>', NULL, array('class' => 'line-labels')); ?>
							<div class="form-inputs line-inputs">
								<?= form_input($external_reviews['url']); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<div class="form-elements">
							<?= form_label('Rating', NULL, array('class' => 'line-labels')); ?>
							<div class="form-inputs line-inputs rating-inputs">
								<?
									$max = 5.5; // *n because each star is broken into n; +1 because loop starts at 1, not 0
									for ($i = 0.5; $i < $max; $i+=0.5)
									{
										echo '<input value="'.$i.'" name="rating" type="radio" class="star {split:2}"/>';
									}
								?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<div class="form-elements">
							<?= form_label('Details <span class="required-markers">*</span>', NULL, array('class' => 'line-labels')); ?>
							<div class="form-inputs line-inputs">
								<?= form_textarea($external_reviews['content']); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<?= $element_submit_delete_container ?>
					<?= form_close() ?>
				</div>
			</section>
			<? endif; ?>

		</div>

		<? if ($user['role'] != ROLE_STUDENT): ?>
		<div class="col-2 cf">
			<section id="user-contact" class="profile-secs">
				<span class="buttons color-3-buttons" id="user-contact-button" title="<?= $tips['live-profile-button'] ?>">
					<span id="user-contact-button-content">Contact<span class="user-contact-button-name"> <?= $display_name ?></span></span>
				</span>
				<div id="under-contact-links-cont">
					<a href="javascript:void(0);" title="A student can favourite you by clicking this link. Favourited tutors show up on the student's Tutors page." class="under-contact-links dropdown-padding">Favourite</a> | 
					<a href="javascript:void(0);" data-dropdown="#dropdown-invite-to-tutor-request" class="under-contact-links" title="A student can invite you to one of their requests by clicking this link.">Invite</a>

					<div id="dropdown-invite-to-tutor-request" class="dropdown dropdown-relative dropdown-tip dropdown-anchor-right">
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>
						<div class="dropdown-panel">
							<span class="no-click">Students will see their tutor requests appear in a drop down here. They can then invite you by clicking on one of them.</span>
						</div>
					</div>

				</div>
			</section>
			<section id="tutor-location" class="profile-secs">
				<header>
					<h2>Location <img id="edit-location" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="location-element" src="<?= base_url(ICON_EDIT) ?>"></h2>
				</header>
				<div class="sec-bodies">
					<div id="map"></div>

					<div id="can-meet-cont">
						<div class="profile-labels">
							Can meet...
							<img title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="can-meet-element" data-reference-id="can-meet-item" src="<?= base_url(ICON_EDIT) ?>">
						</div> 
						<div id="can-meet" class="profile-sec-contents">
							<div class="can-meet-item-cols">
								<div class="can-meet-items can-meet-students-home <?= $can_meet['students_home']['checked'] ? 'checked' : '' ?>">
									<span class="check-marks">&#10004;</span> 
									<span class="cross-marks">&times;</span>
									<span class="can-meet-texts">At student's home</span>
								</div>
								<div class="can-meet-items can-meet-tutors-home <?= $can_meet['tutors_home']['checked'] ? 'checked' : '' ?>">
									<span class="check-marks">&#10004;</span> 
									<span class="cross-marks">&times;</span>
									<span class="can-meet-texts">At tutor's home</span>
								</div>
								<div class="can-meet-items can-meet-online-local <?= $can_meet['online_local']['checked'] ? 'checked' : '' ?>">
									<span class="check-marks">&#10004;</span> 
									<span class="cross-marks">&times;</span>
									<span class="can-meet-texts">Online (local) (<span class="same-page-links no-pointer" title="<?= $tips['online-local'] ?>">?</span>)</span>
								</div>
							</div>
							<div class="can-meet-item-cols second">
								<div class="can-meet-items can-meet-public <?= $can_meet['public']['checked'] ? 'checked' : '' ?>">
									<span class="check-marks">&#10004;</span> 
									<span class="cross-marks">&times;</span>
									<span class="can-meet-texts">In public place</span>
								</div>
								<div class="can-meet-items can-meet-centre <?= $can_meet['centre']['checked'] ? 'checked' : '' ?>">
									<span class="check-marks">&#10004;</span> 
									<span class="cross-marks">&times;</span>
									<span class="can-meet-texts">In tutor centre (<span class="same-page-links no-pointer" title="<?= $tips['tutor-center'] ?>">?</span>)</span>
								</div>
								<div class="can-meet-items can-meet-online-distant <?= $can_meet['online_distant']['checked'] ? 'checked' : '' ?>">
									<span class="check-marks">&#10004;</span> 
									<span class="cross-marks">&times;</span>
									<span class="can-meet-texts">Online (distant) (<span class="same-page-links no-pointer" title="<?= $tips['online-distant'] ?>">?</span>)</span>
								</div>
							</div>
						</div>

						<div class="absolute-profile-edit-elements" data-item-type="can-meet" data-edit-type="only" id="can-meet-element">
							<?= form_open() ?>
								<div class="ajax-overlays">
									<div class="ajax-overlays-bg"></div>
									<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
								</div>
								<input type="hidden" name="update-key" value="can_meet">
								<div class="form-elements">
									<?= form_label('Can meet...', NULL, array('class' => 'block-labels')); ?>
									<div class="form-inputs block-inputs">
										<?= form_label(form_checkbox($can_meet['students_home']) . "At student's home"); ?>
										<?= form_label(form_checkbox($can_meet['tutors_home']) . "At tutor's home"); ?>
										<?= form_label(form_checkbox($can_meet['centre']) . "In tutor centre (<span class='same-page-links no-pointer' title=".json_encode($tips['tutor-center']).">?</span>)"); ?>
										<?= form_label(form_checkbox($can_meet['public']) . "In public place"); ?>
										<?= form_label(form_checkbox($can_meet['online_local']) . "Online (local) (<span class='same-page-links no-pointer' title=".json_encode($tips['online-local']).">?</span>)"); ?>
										<?= form_label(form_checkbox($can_meet['online_distant']) . "Online (distant) (<span class='same-page-links no-pointer' title=".json_encode($tips['online-distant']).">?</span>)"); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<?= $element_submit_container ?>
							<?= form_close() ?>
						</div>
					</div>


					<div id="travel-notes-cont">
						<div class="profile-labels">Travel Notes <img title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="travel-notes-element" data-reference-id="travel-notes-item" src="<?= base_url(ICON_EDIT) ?>">
						</div> 
						<div id="travel-notes" class="profile-sec-contents">
							<?= nl2br($user['travel_notes']) ?>
						</div>

						<div class="absolute-profile-edit-elements" data-item-type="travel-notes" data-edit-type="only" id="travel-notes-element">
							<?= form_open() ?>
								<div class="ajax-overlays">
									<div class="ajax-overlays-bg"></div>
									<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
								</div>
								<input type="hidden" name="update-key" value="travel_notes">
								<div class="form-elements">
									<?= form_label('Travel Notes', $travel_notes['id'], array('class' => 'block-labels')); ?>
									<div class="form-inputs block-inputs">
										<?= form_textarea($travel_notes); ?>
										<div class="form-input-notes error-messages"></div>
									</div>
								</div>
								<?= $element_submit_container ?>
							<?= form_close() ?>
						</div>
					</div>
				</div>
			</section>
			<section id="tutor-availability" class="profile-secs">
				<header>
					<h2>Availability <img id="edit-availability" title="Click to edit" class="edit-icons absolute-edit-icons" data-edit-element-id="availability-element" src="<?= base_url(ICON_EDIT) ?>"></h2>
				</header>
				<div id="availability-item">
					<?= $user['availability'] ?>
				</div>
			</section>
			<section id="tutor-links" class="profile-secs">
				<header>
					<h2>Links</h2>
				</header>
				<div id="links-item-cont" data-type="link" class="item-conts"> 
					<? foreach($user['links'] as $item): ?>
						<div class="link-items block-items" id="link-<?= $item['id'] ?>" data-item-type="link" data-item-id="<?= $item['id'] ?>"><div class="link-images regular-link-image"></div><a href="<?= $item['url'] ?>" title="<?= $item['description'] ?>" target="_blank" class="user-links" rel="nofollow"><?= $item['label'] ?></a><span class="icon-conts"><img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="link-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>"></span></div>
					<? endforeach; ?>
				</div>
				<div title="Add a link" class="add-profile-item-links buttons" data-type="link" data-edit-element-id="link-element"><span class="">» Add</span></div>

				<div class="block-profile-edit-elements" data-item-type="link" id="link-element">
					<?= form_open() ?>
						<div class="required-text tiny-aftertext"><span class="required-markers">*</span> = required</div>
						<div class="ajax-overlays">
							<div class="ajax-overlays-bg"></div>
							<img class="ajax-overlay-loaders large" src="<?= base_url('assets/images/'.LOADER_DARK) ?>">
						</div>
						<input type="hidden" name="update-key" value="link">
						<input type="hidden" name="item-id" value="">
						<div class="form-elements">
							<?= form_label('Web Address/URL <span class="required-markers">*</span>', NULL, array('class' => 'block-labels')); ?>
							<div class="form-inputs block-inputs">
								<?= form_input($links['url']); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<div class="form-elements">
							<?= form_label('Label <span class="debold">(<span class="same-page-links no-pointer" title="'.$tips['links-label'].'">?</span>)</span>', NULL, array('class' => 'block-labels')); ?>
							<div class="form-inputs block-inputs">
								<?= form_input($links['label']); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<div class="form-elements">
							<?= form_label('Description <span class="debold">(<span class="same-page-links no-pointer" title="'.$tips['links-description'].'">?</span>)</span>', NULL, array('class' => 'block-labels')); ?>
							<div class="form-inputs block-inputs">
								<?= form_textarea($links['description']); ?>
								<div class="form-input-notes error-messages"></div>
							</div>
						</div>
						<?= $element_submit_delete_container ?>
					<?= form_close() ?>
				</div>
			</section>
		</div>
		<? endif; ?>
	</section>	<!-- /#edit-profile -->
	
<script>

	var doneNoty = null,
		avatarWidth = <?= AVATAR_WIDTH ?>,
		avatarHeight = <?= AVATAR_HEIGHT ?>,
		previewWidth = 130,
		previewHeight = previewWidth;

	var $userIntro = $('#user-intro'),
		$userAvatarCont = $('#user-avatar-cont'),
		$avatarOverlay = $('#avatar-edit-overlay'),
		$avatarOverlayText = $avatarOverlay.find('span'),
		curAvatarHeight;

$(function()
{
	$(window).resize(function()
	{
		if (window.vpWidth > <?= SCREEN_SUB_REGULAR ?>)
		{
			$('.profile-secs').show();
		}
		else
		{
			var $editElement = $('.block-profile-edit-elements.active, .absolute-profile-edit-elements.active'),
				secId;

			if ($editElement.length)
			{
				secId = $editElement.parents('.profile-secs').attr('id');
				$('#profile-section-selection-cont span[data-target=#'+secId+']').click();				
			}
			else
			{
				$('#profile-section-selection-cont span.active').click();				
			}
		}
	});

	$('input', '#price-element .radio-option-conts').focus(function()
	{
		$(this).siblings('input[type=radio]').click();
	});

	$('span', '#profile-section-selection-cont').click(function()
	{
		var $link = $(this),
			$siblings = $link.siblings(),
			target = $link.attr('data-target'),
			speed = 200;

		$siblings.removeClass('active');
		$link.addClass('active');
		$('.profile-secs').not(target + ', #user-meta, #user-intro').hide();
		$(target).fadeIn(speed, function()
		{
			if (target == '#tutor-location')
			{
//				console.log('resized');
				google.maps.event.trigger(map, 'resize');
			}
			else if (target == '#user-contact' && !$('#user-contact-button').hasClass('active'))
			{
				showContactForm();
			}
		});
	});

	// Delete this eventually
	curAvatarHeight = $userAvatarCont.outerHeight();
	//		log(curAvatarHeight);

	// jQuery 1.9 doesn't allow is(':hover'), when we up to 2+ then switch to that
	if ($('#user-intro:hover').length)
	{
		$avatarOverlay.stop(true, true).animate(
		{
			bottom: -curAvatarHeight + 25
		}, <?= FAST_FADE_SPEED ?>);
	}
	// End - delete

	$userIntro.hover(function()
	{
		curAvatarHeight = $userAvatarCont.outerHeight();
//		log(curAvatarHeight);

		if (!$userAvatarCont.hasClass('deactivated'))
		{
			$avatarOverlay.stop(true, true).animate(
			{
				bottom: -curAvatarHeight + 25
			}, <?= FAST_FADE_SPEED ?>);
		}

	}, function()
	{
		if (!$userAvatarCont.hasClass('deactivated'))
		{
			if (!$userAvatarCont.hasClass('active'))
			{
				$avatarOverlay.stop(true, true).animate(
				{
					bottom: -100 	// 100 is max height; if we use image height then when we increase screen size and image size, overlay can be seen
				}, <?= FAST_FADE_SPEED ?>);
			}
		}
	})

	$userAvatarCont.hover(function()
	{
		if (!$userAvatarCont.hasClass('active'))
		{
			$avatarOverlayText.addClass('active');
		}

	}, function()
	{
		if (!$userAvatarCont.hasClass('active'))
		{
			$avatarOverlayText.removeClass('active');
		}
	});

	$('#dropdown-avatar-edit').on('show', function()
	{
		$userAvatarCont.addClass('active');
	}).on('hide', function()
	{
		$userAvatarCont.removeClass('active');
		
		if ($('#user-avatar-cont:hover').length == 0)
		{
			$avatarOverlayText.removeClass('active');
			$userIntro.trigger('mouseleave');
		}

	});
});

<? if ($user['role'] != ROLE_STUDENT): ?>

	var marker = null,
		modalMap,
		profileMap,
		profileMarker,
		$availability = $('#availability-element .availabilities'),
		avail,
		profileLocationHasClientError = false;

$(function()
{

	$('.import-forms').submit(function()
	{
		var $form = $(this),
			$overlay = $form.parents('.reveal-modal').find('.ajax-overlays'),
			data = $form.serialize(),
			message = "Are you sure that you want to import this profile?\n\nAnything you import either overwrites that section of your profile (e.g. Photo, Subjects), or adds to it (e.g. Experience, Education).";

		if (!confirm(message))
		{
			return false;
		}


		$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$form.find(':input').prop('disabled', true);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('import') ?>",
			data: data,
			dataType: 'json'
		}).done(function(response)
		{
			$form.validate(response.errors);

			if (response.success == true)
			{
				window.location = "<?= current_url() ?>";
			}
			else
			{
				$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);					
			}

			if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
			{
				ajaxFailNoty();
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

	$('input[type=radio].star').rating();

//	showEditElement('#subjects-element');

	$('.autocompleted').autocomplete(
	{
		source: function(request, response) 
		{
			var autocompleteSource = $(this.element).attr('data-autocomplete-source');
			$.getJSON("<?= base_url('data') ?>/"+autocompleteSource, request, function(data) 
			{
				response(data);
			});
		}
	});

	makeSortable('#education-item-cont');
	makeSortable('#experience-item-cont');
	makeSortable('#volunteering-item-cont');
	makeSortable('#links-item-cont');
	makeSortable('#er-item-cont');

//	$('#jcrop-interface').prependTo('body');

	<? if (isset($profile_just_made)): 
		$noty = '<b>Great! Your profile is visible!</b><br>You can fill out more of your profile, or get straight to tutoring.<hr>In either case, welcome and good luck!';
	?>
		doneNoty = noty({
			text: <?= json_encode($noty) ?>,
			type: 'success'
		});

	<? endif; ?>
/*
	$('#user-contact-button').qtip(
	{
		position: 
		{
			my: 'bottom center',
			at: 'top center'
		}
	});
*/
	setupOvernightTimeLinks('#edit-profile');

//	showEditElement('#availability-element');

	setupAvailability();

	// Only go ahead if GMaps has loaded
	if (typeof google === 'object' && typeof google.maps === 'object')
	{
		setupTutorMap();
		setupGoogleMaps();
	}
	else
	{
		noty(
		{
			text: "<b>Sorry. Google Maps couldn't load. Please refresh the page.</b><hr>If you see this message again, then don't worry, we're working on it.",
			type: 'warning'
		});
	}

	var $contactButton = $('#user-contact-button');
//	$contactButton.textfill({ maxFontPixels: 16 });

	$('.hide-profile, .show-profile').click(toggleProfile);

	$('#<?= $subjects["id"] ?>').select2(
	{
            allowClear: true,
            tags: <?= json_encode($all_subjects) ?>,
            tokenSeparators: [","],
            openOnEnter: false
    }).on("change", function(e) 
    { 
    	var item,
    		$mainSubject = $('#main-subject-element').find('#main-subject-input'),
    		$options = $mainSubject.find('option'),
    		$option;

    	if (e.added !== undefined)
    	{
    		item = e.added.text;

    		$option = $('<option></option>').attr('value', item).text(item);
    		$mainSubject.append($option);
    	}
    	else if (e.removed !== undefined)
    	{
    		item = e.removed.text;
    		
    		$options.filter(function()
			{
				return $(this).text() == item;
			}).first().remove();	// We use .first() in case a clever chap inputs "No Subject" as their subject

    	}
    	$mainSubject.trigger("liszt:updated");

	});

    if (!window.handheld)
    {
/*
		$('#main-subject-input').show().chosen(
		{
			no_results_text: "Sorry, none of your subjects above match",
			disable_search: true
	    });    	
*/
    }

	showAppropriatePriceSubfield();
	$('[name=<?= $price_name ?>]').change(showAppropriatePriceSubfield);

	if (!window.handheld)
	{
/*
		$('#currency').chosen({
			no_results_text: "Sorry! We don't currently support"			// Plugin appends %query% to this string
		});
*/
	}
});


function showAppropriatePriceSubfield()
{
	var $this = $('[name=<?= $price_name ?>]:checked');
	var val = $this.val();
	// If free price is chosen, then show reason field
	if (val == '<?= $price_free["value"] ?>')
	{
		$('#reason-cont').slideDown(200).find('textarea').focus();
	}
	else
	{
		$('#reason-cont').slideUp(200);

		if (val == '<?= $price_hourly["value"] ?>')
		{
			$('#hourly-rate').focus();
		}
	}
}

function setupTutorMap()
{
	var location = document.getElementById('location');

 	var mapOptions = 
 	{
		center: new google.maps.LatLng(<?= ($user['lat'] ?: 30.238953) ?>, <?= ($user['lon'] ?: 13.554688) ?>),
		zoom: 1,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		panControl: false,
		zoomControl: true,
		mapTypeControl: false,
		scaleControl: false,
		streetViewControl: false,
		overviewMapControl: false,
		keyboardShortcuts: false
    };	
    
    profileMap = new google.maps.Map(document.getElementById('map'), mapOptions);

<? if ($user['lat']): ?>

	profileMarker = new google.maps.Marker({
		map: profileMap,
	    animation: google.maps.Animation.DROP,
	    position: mapOptions.center
	});

	profileMap.setZoom(14);


<? endif; ?>
}

function setupGoogleMaps()
{
	var defaultBounds = new google.maps.LatLngBounds(
	  new google.maps.LatLng(-33.8902, 151.1759),
	  new google.maps.LatLng(-33.8474, 151.2631)
	);

    var mapOptions = 
    {
		center: new google.maps.LatLng(30.238953, 13.554688),
		zoom: 1,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		panControl: false,
		zoomControl: true,
		mapTypeControl: false,
		scaleControl: false,
		streetViewControl: false,
		overviewMapControl: false,
		keyboardShortcuts: false
    };
    modalMap = new google.maps.Map(document.getElementById('location-gmap'), mapOptions);

	var location = document.getElementById('location');
	var autocomplete = new google.maps.places.Autocomplete(location);

	autocomplete.bindTo('bounds', modalMap);

	// Handle initial Google Maps location (if address is set)
	if (location.value != '')
	{
		handleNewLocation(modalMap, location.value);		
	}

	$('#location').blur(function()
	{
        google.maps.event.trigger(autocomplete, 'place_changed');
	})

	// Update Google Map with every change
	google.maps.event.addListener(autocomplete, 'place_changed', function()
	{
		handleNewLocation(modalMap, location.value);
	});

	google.maps.event.addListenerOnce(modalMap, 'idle', function() 
	{
		$('#location-element').hide().css('left', 'auto');
//		google.maps.event.trigger(modalMap, "resize");
		activateEditElement('[data-edit-element-id=location-element]');
	});
}

function recordCoordinates(lat, lon, loc)
{
	$('#lat').val(lat);
	$('#lon').val(lon);
	$('#city').val(loc['city']);
	$('#country').val(loc['country']);
}

function handleNewLocation(map, address)
{
	var enclosers = {
				start: '<div class="error-messages">',
				end: '</div>'
			},
		error = '';

	// Figure out a way to avoid geocoding it again. You're a product of millions of years of evolution for Sagan's sake. Geocoder defined in site script
	geocoder.geocode( { 'address': address}, function(results, status) 
	{
		if (status == google.maps.GeocoderStatus.OK) 
		{
			var loc = parseLocation(results[0].address_components),
				coords = results[0].geometry.location,
				lat = coords.lat(),
				lon = coords.lng();

			var formattedAddress = results[0].formatted_address;
	
			map.fitBounds(results[0].geometry.viewport);
			map.setCenter(coords);

			setMarker(map, coords);
			setNeededLocationComponents(loc);

			if (!lat || !lon)
			{
				error += enclosers.start + 'Sorry, there was a problem.<br>Please check for a typo or try different location.' + enclosers.end;
			}
			else if (!(loc['country'] && loc['city'] && loc['specific']))
			{
				error += enclosers.start + 'Sorry, please include a more specific place (e.g. postal code, street name, nearby establishment).' + enclosers.end;
			}				
			else
			{	
				map.setZoom(14);
			}
		}
		else 	// address is invalid
		{
			if (!address)
			{
//					error += enclosers.start + "Please don't leave Location blank." + enclosers.end;
			}
			else
			{
				error += enclosers.start + 'Sorry, there was a problem.<br>Please check for a typo or try different location.' + enclosers.end;				
			}
		}
	
		if (!error 
			&& loc 
			&& typeof loc['city'] !== undefined
			&& typeof loc['country'] !== undefined)
		{
			recordCoordinates(lat, lon, loc);
			profileLocationHasClientError = false;
		}
		else
		{
			profileLocationHasClientError = true;
		}
	
		$('#location').showErrors(error, false);
	});
}

function setNeededLocationComponents(loc)
{
//	// // console.log(loc);
	$('#country-val').val(loc['country']);
	$('#city-val').val(loc['city']);
	$('#specific-val').val(loc['specific']);
}

function setMarker(map, location)
{
	if (marker != null)
	    marker.setMap(null);
	marker = new google.maps.Marker({
		map: map,
	    animation: google.maps.Animation.DROP,
	    position: location
	});
}

function getCountryCode(country)
{
	var countryCodes = 
	{
		'Afghanistan' : 'AF',
		'Åland Islands' : 'AX',
		'Albania' : 'AL',
		'Algeria' : 'DZ',
		'American Samoa' : 'AS',
		'Andorra' : 'AD',
		'Angola' : 'AO',
		'Anguilla' : 'AI',
		'Antarctica' : 'AQ',
		'Antigua and Barbuda' : 'AG',
		'Argentina' : 'AR',
		'Australia' : 'AU',
		'Austria' : 'AT',
		'Azerbaijan' : 'AZ',
		'Bahamas' : 'BS',
		'Bahrain' : 'BH',
		'Bangladesh' : 'BD',
		'Barbados' : 'BB',
		'Belarus' : 'BY',
		'Belgium' : 'BE',
		'Belize' : 'BZ',
		'Benin' : 'BJ',
		'Bermuda' : 'BM',
		'Bhutan' : 'BT',
		'Bolivia' : 'BO',
		'Bosnia and Herzegovina' : 'BA',
		'Botswana' : 'BW',
		'Bouvet Island' : 'BV',
		'Brazil' : 'BR',
		'British Indian Ocean Territory' : 'IO',
		'Brunei Darussalam' : 'BN',
		'Bulgaria' : 'BG',
		'Burkina Faso' : 'BF',
		'Burundi' : 'BI',
		'Cambodia' : 'KH',
		'Cameroon' : 'CM',
		'Canada' : 'CA',
		'Catalonia' : 'CT',
		'Cape Verde' : 'CV',
		'Cayman Islands' : 'KY',
		'Central African Republic' : 'CF',
		'Chad' : 'TD',
		'Chile' : 'CL',
		'China' : 'CN',
		'Christmas Island' : 'CX',
		'Cocos (Keeling) Islands' : 'CC',
		'Colombia' : 'CO',
		'Comoros' : 'KM',
		'Congo' : 'CG',
		'Zaire' : 'CD',
		'Cook Islands' : 'CK',
		'Costa Rica' : 'CR',
		'Côte D\'Ivoire' : 'CI',
		'Croatia' : 'HR',
		'Cuba' : 'CU',
		'Cyprus' : 'CY',
		'Czech Republic' : 'CZ',
		'Denmark' : 'DK',
		'Djibouti' : 'DJ',
		'Dominica' : 'DM',
		'Dominican Republic' : 'DO',
		'Ecuador' : 'EC',
		'Egypt' : 'EG',
		'El Salvador' : 'SV',
		'Equatorial Guinea' : 'GQ',
		'Eritrea' : 'ER',
		'Estonia' : 'EE',
		'Ethiopia' : 'ET',
		'Falkland Islands (Malvinas)' : 'FK',
		'Faroe Islands' : 'FO',
		'Fiji' : 'FJ',
		'Finland' : 'FI',
		'France' : 'FR',
		'French Guiana' : 'GF',
		'French Polynesia' : 'PF',
		'French Southern Territories' : 'TF',
		'Gabon' : 'GA',
		'Gambia' : 'GM',
		'Georgia' : 'GE',
		'Germany' : 'DE',
		'Ghana' : 'GH',
		'Gibraltar' : 'GI',
		'Greece' : 'GR',
		'Greenland' : 'GL',
		'Grenada' : 'GD',
		'Guadeloupe' : 'GP',
		'Guam' : 'GU',
		'Guatemala' : 'GT',
		'Guernsey' : 'GG',
		'Guinea' : 'GN',
		'Guinea-Bissau' : 'GW',
		'Guyana' : 'GY',
		'Haiti' : 'HT',
		'Heard Island and Mcdonald Islands' : 'HM',
		'Vatican City State' : 'VA',
		'Honduras' : 'HN',
		'Hong Kong' : 'HK',
		'Hungary' : 'HU',
		'Iceland' : 'IS',
		'India' : 'IN',
		'Indonesia' : 'ID',
		'Iran, Islamic Republic of' : 'IR',
		'Iraq' : 'IQ',
		'Ireland' : 'IE',
		'Isle of Man' : 'IM',
		'Israel' : 'IL',
		'Italy' : 'IT',
		'Jamaica' : 'JM',
		'Japan' : 'JP',
		'Jersey' : 'JE',
		'Jordan' : 'JO',
		'Kazakhstan' : 'KZ',
		'KENYA' : 'KE',
		'Kiribati' : 'KI',
		'Korea, Democratic People\'s Republic of' : 'KP',
		'Korea, Republic of' : 'KR',
		'Kuwait' : 'KW',
		'Kyrgyzstan' : 'KG',
		'Lao People\'s Democratic Republic' : 'LA',
		'Latvia' : 'LV',
		'Lebanon' : 'LB',
		'Lesotho' : 'LS',
		'Liberia' : 'LR',
		'Libyan Arab Jamahiriya' : 'LY',
		'Liechtenstein' : 'LI',
		'Lithuania' : 'LT',
		'Luxembourg' : 'LU',
		'Macao' : 'MO',
		'Macedonia, the Former Yugoslav Republic of' : 'MK',
		'Madagascar' : 'MG',
		'Malawi' : 'MW',
		'Malaysia' : 'MY',
		'Maldives' : 'MV',
		'Mali' : 'ML',
		'Malta' : 'MT',
		'Marshall Islands' : 'MH',
		'Martinique' : 'MQ',
		'Mauritania' : 'MR',
		'Mauritius' : 'MU',
		'Mayotte' : 'YT',
		'Mexico' : 'MX',
		'Micronesia, Federated States of' : 'FM',
		'Moldova, Republic of' : 'MD',
		'Monaco' : 'MC',
		'Mongolia' : 'MN',
		'Montenegro' : 'ME',
		'Montserrat' : 'MS',
		'Morocco' : 'MA',
		'Mozambique' : 'MZ',
		'Myanmar' : 'MM',
		'Namibia' : 'NA',
		'Nauru' : 'NR',
		'Nepal' : 'NP',
		'Netherlands' : 'NL',
		'Netherlands Antilles' : 'AN',
		'New Caledonia' : 'NC',
		'New Zealand' : 'NZ',
		'Nicaragua' : 'NI',
		'Niger' : 'NE',
		'Nigeria' : 'NG',
		'Niue' : 'NU',
		'Norfolk Island' : 'NF',
		'Northern Mariana Islands' : 'MP',
		'Norway' : 'NO',
		'Oman' : 'OM',
		'Pakistan' : 'PK',
		'Palau' : 'PW',
		'Palestinian Territory, Occupied' : 'PS',
		'Panama' : 'PA',
		'Papua New Guinea' : 'PG',
		'Paraguay' : 'PY',
		'Peru' : 'PE',
		'Philippines' : 'PH',
		'Pitcairn' : 'PN',
		'Poland' : 'PL',
		'Portugal' : 'PT',
		'Puerto Rico' : 'PR',
		'Qatar' : 'QA',
		'Réunion' : 'RE',
		'Romania' : 'RO',
		'Russian Federation' : 'RU',
		'Russia' : 'RU',
		'Rwanda' : 'RW',
		'Saint Helena' : 'SH',
		'Saint Kitts and Nevis' : 'KN',
		'Saint Lucia' : 'LC',
		'Saint Pierre and Miquelon' : 'PM',
		'Saint Vincent and the Grenadines' : 'VC',
		'Samoa' : 'WS',
		'San Marino' : 'SM',
		'Sao Tome and Principe' : 'ST',
		'Saudi Arabia' : 'SA',
		'Senegal' : 'SN',
		'Serbia' : 'RS',
		'Seychelles' : 'SC',
		'Sierra Leone' : 'SL',
		'Singapore' : 'SG',
		'Slovakia' : 'SK',
		'Slovenia' : 'SI',
		'Solomon Islands' : 'SB',
		'Somalia' : 'SO',
		'South Africa' : 'ZA',
		'South Georgia and the South Sandwich Islands' : 'GS',
		'Spain' : 'ES',
		'Sri Lanka' : 'LK',
		'Sudan' : 'SD',
		'Suriname' : 'SR',
		'Svalbard and Jan Mayen' : 'SJ',
		'Scotland' : 'SS',
		'Swaziland' : 'SZ',
		'Sweden' : 'SE',
		'Switzerland' : 'CH',
		'Syrian Arab Republic' : 'SY',
		'Taiwan, Province of China' : 'TW',
		'Tajikistan' : 'TJ',
		'Tanzania, United Republic of' : 'TZ',
		'Thailand' : 'TH',
		'Timor-Leste' : 'TL',
		'Togo' : 'TG',
		'Tokelau' : 'TK',
		'Tonga' : 'TO',
		'Trinidad and Tobago' : 'TT',
		'Tunisia' : 'TN',
		'Turkey' : 'TR',
		'Turkmenistan' : 'TM',
		'Turks and Caicos Islands' : 'TC',
		'Tuvalu' : 'TV',
		'Uganda' : 'UG',
		'Ukraine' : 'UA',
		'United Arab Emirates' : 'AE',
		'United Kingdom' : 'GB',
		'United States' : 'US',
		'United States Minor Outlying Islands' : 'UM',
		'Uruguay' : 'UY',
		'Uzbekistan' : 'UZ',
		'Vanuatu' : 'VU',
		'Venezuela' : 'VE',
		'Viet Nam' : 'VN',
		'Virgin Islands, British' : 'VG',
		'Virgin Islands, U.S.' : 'VI',
		'Wales' : 'WA',
		'Wallis and Futuna' : 'WF',
		'Western Sahara' : 'EH',
		'Yemen' : 'YE',
		'Zambia' : 'ZM',
		'Zimbabwe' : 'ZW'
	}

	if (countryCodes[country])
		return countryCodes[country];
	return '_noflag';
}

function setupAvailability()
{
	$('#availability-element').disableTextSelect();
    
	<? if (isset($user['avail_json'])): ?>
		avail = <?= $user['avail_json'] ?>;
	<? else: ?>
		avail = 
		{
			'6am': [],
			'7am': [],
			'8am': [],
			'9am': [],
			'10am': [],
			'11am': [],
			'12pm': [],
			'1pm': [],
			'2pm': [],
			'3pm': [],
			'4pm': [],
			'5pm': [],
			'6pm': [],
			'7pm': [],
			'8pm': [],
			'9pm': [],
			'10pm': [],
			'11pm': [],
			'12am': [],
			'1am': [],
			'2am': [],
			'3am': [],
			'4am': [],
			'5am': []
		};
	<? endif; ?>

	var days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
		isDragging = false,
		drag = 
		{
			isDragging: false,
			isActivating: 'activating',
			coords: [0,0,0,0]	// x1, y1, x2, y2
		};

	$availability.find('.availability-grids tr td')
	.on('mousedown touchstart', startDrag)
	.mouseover(continueDrag)
	.on('mouseup touchend', stopDrag);	

	function startDrag()
	{
		drag.isDragging = true;
		drag.coords.length = 0;

		var $this = $(this),
			$tr = $this.parent(),
			time = $.trim($tr.text()),
			numericalTime = $tr.parents('.availability-grids').find('tr').index($tr),
			numericalDay = $tr.children('td').index($this),
			day = days[numericalDay];

		drag.coords[0] = numericalTime;
		drag.coords[1] = numericalDay;

		// If day is not in the time array, then set mode to activating, activate cell, and add day to array
		if (avail[time].indexOf(day) === -1) 
		{
			drag.isActivating = true;
		}
		else 
		{
			drag.isActivating = false;
		}

		$(document).mouseup(resetIfOutOfBounds); 
	}

	function resetIfOutOfBounds()
	{
		$(document).unbind('mouseup', resetIfOutOfBounds);

		removeTempOutline();
		drag.isDragging = false;
	}

	function continueDrag() 
	{
		if (drag.isDragging)
		{
			var $this = $(this),
				$tr = $this.parent(),
				time = $.trim($tr.text()),
				numericalTime = $tr.parents('.availability-grids').find('tr').index($tr),
				numericalDay = $tr.children('td').index($this),
				day = days[numericalDay];

			removeTempOutline();
			drag.coords[2] = numericalTime;
			drag.coords[3] = numericalDay;

			showTempOutline(drag.coords, drag.isActivating);
		}
	}

	function stopDrag()
	{
		$(document).unbind('mouseup', resetIfOutOfBounds);

		removeTempOutline();
		drag.isDragging = false;

		var $this = $(this),
			$tr = $this.parent(),
			time = $.trim($tr.text()),
			numericalTime = $tr.parents('.availability-grids').find('tr').index($tr),
			numericalDay = $tr.children('td').index($this),
			day = days[numericalDay];

		drag.coords[2] = numericalTime;
		drag.coords[3] = numericalDay;

		convertCells(drag.coords, drag.isActivating);
	}

	function removeTempOutline()
	{
		$('.temp-available, .temp-unavailable').removeClass('temp-unavailable').removeClass('temp-available');
	}

	function showTempOutline(coords, activating)
	{
		var minX, minY, maxX, maxY, row, col, time, day, $row, $cell, 
		$table = $availability.find('.availability-grids');

		if (coords[0] == Math.min(coords[0], coords[2])) 
		{
			minX = coords[0],
			maxX = coords[2];
		} 
		else 
		{
			minX = coords[2],
			maxX = coords[0];
		}

		if (coords[1] == Math.min(coords[1], coords[3])) 
		{
			minY = coords[1],
			maxY = coords[3];
		} 
		else 
		{
			minY = coords[3],
			maxY = coords[1];
		}

		for(row = minX ; row <= maxX ; row++) 
		{
			$row = $table.find('tr').eq(row);
			
			for(col = minY ; col <= maxY ; col++) 
			{
				$col = $row.find('td').eq(col);

				if (activating) 
				{
					$col.addClass('temp-available');
					$col.removeClass('temp-unavailable');
				}
				else 
				{
					$col.addClass('temp-unavailable');
					$col.removeClass('temp-available');
				}
			}
		}
	}

	function convertCells(coords, activating)
	{
		var minX, minY, maxX, maxY, row, col, time, day, $row, $cell, 
			$table = $availability.find('.availability-grids');

		if (coords[0] == Math.min(coords[0], coords[2])) {
			minX = coords[0],
			maxX = coords[2];
		} 
		else {
			minX = coords[2],
			maxX = coords[0];
		}

		if (coords[1] == Math.min(coords[1], coords[3])) {
			minY = coords[1],
			maxY = coords[3];
		} 
		else {
			minY = coords[3],
			maxY = coords[1];
		}

		for(row = minX ; row <= maxX ; row++) 
		{
			$row = $table.find('tr').eq(row);
			time = $.trim($row.text());
			
			for(col = minY ; col <= maxY ; col++) {
				$col = $row.find('td').eq(col);
				day = days[col];
				if (activating) 
				{
					$col.addClass('available');
					avail[time].push(day);
				}
				else 
				{
					$col.removeClass('available');
					avail[time].remove(day);
				}
			}
		}

	}

}

function toggleProfile()
{
	if (doneNoty)
	{
		doneNoty.close();
	}

	if (!window.awaitingResponse.general)
	{
		window.awaitingResponse.general = true;
		$('.toggle-profile-loader').fadeIn(<?= FAST_FADE_SPEED ?>)
								   .css('display', 'inline-block');
		$.ajax({
			type: "POST",
			url: baseUrl("profile/toggle_profile"),
			data: {
			}
		}).done(function(response) {
			window.awaitingResponse.general = false;
			$('.toggle-profile-loader').fadeOut(<?= FAST_FADE_SPEED ?>);		
			if (response == 'good')
			{	
				$('.show-hide-profile-conts.active').fadeOut(<?= STANDARD_FADE_SPEED ?>, function() {
					$(this).removeClass('active')
						   .siblings('.show-hide-profile-conts').fadeIn(<?= STANDARD_FADE_SPEED ?>, function() {
						   	  $(this).addClass('active');
						   }).css('display', 'inline-block');
				});
			}
		}).always(function() 
		{
			$('.toggle-profile-loader').fadeOut(<?= FAST_FADE_SPEED ?>);
		}).fail(function() 
		{
			ajaxFailNoty();
		});
	}
}

Array.prototype.inArray = function(el) 
{ 
    for(var i=0; i < this.length; i++) 
    { 
        if(this[i] === el) return true; 
    }
    return false; 
}; 

Array.prototype.pushIfUnique = function(el) 
{ 
    if (!this.inArray(el)) 
    {
        this.push(el);
    }
}; 

Array.prototype.remove = function() 
{
    var what, a = arguments, L = a.length, ax;
    while (L && this.length) 
    {
        what = a[--L];
        while ((ax = this.indexOf(what)) !== -1) 
        {
            this.splice(ax, 1);
        }
    }
    return this;
};

$.fn.disableTextSelect = function() 
{
	return this.each(function() 
	{
		$(this).css(
		{
			'MozUserSelect':'none',
			'webkitUserSelect':'none'
		}).attr('unselectable','on').bind('selectstart', function() 
		{
			return false;
		});
	});
};

<? endif; ?>


$(function()
{

	$('input.star').rating();

//	setAvatarHover();
//    $('#user-name').textfill({ maxFontPixels: 26 });

	$('.help-links').qtip(
	{
//		prerender:  true,
		style:
		{
			classes: 'qtip-tipsy qtip-light'
		},
		position: 
		{
			my: 'right center',
			at: 'left center',
			adjust: 
			{
				x: -5
			},
			container: $('.pages')
		}
	});

	if (!window.handheld)
	{
/*
		$('#experience-element select').chosen();
		$('#education-element select').chosen();
		$('#volunteering-element select').chosen();
*/
	}

	$('.block-profile-edit-elements [name=current]').change(function()
	{
		var $el = $(this).parents('.block-profile-edit-elements');
//		$('#experience-element').find('[name=end-month], [name=end-year]').prop('disabled', this.checked).trigger("liszt:updated");	
		if (this.checked)
		{
			$el.find('.reg-time-ends').hide();
			$el.find('.current-time-ends').show();
		}
		else
		{
			$el.find('.reg-time-ends').show();
			$el.find('.current-time-ends').hide();			
		}
	});

//	$('#global-overlay').click(hideEditElement);

	activateEditElement('.absolute-edit-icons:not([data-edit-element-id=location-element])');
	activateBlockEditElement('.block-edit-icons');
	
	$('.remove-item-links').click(function() 
	{
		var $this = $(this),
			$editElement = $this.parents('.block-profile-edit-elements'),
			itemId = $editElement.find('[name=item-id]').val(),
			type = $editElement.attr('data-item-type'),
			$overlay = $editElement.find('.ajax-overlays');
	
		if (confirm("Are you sure you want to remove this?"))
		{
			$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

			$.ajax(
			{
				type: "POST",
				url: "<?= base_url('profile/delete_item') ?>",
				data: {
					item_id: itemId,
					type: type
				},
				dataType: 'json'
			}).done(function(response) 
			{
				if (response.success == true)
				{
					var $currentlyEditing = $('.currently-editing'),
						itemContSelector = '#'+$currentlyEditing.parents('.item-conts').attr('id');

					$currentlyEditing.remove();

					updateSortable(itemContSelector);

					hideEditElement();
				}
				else
				{
					ajaxFailNoty();
				}
			}).always(function() 
			{
				$overlay.hide();
			}).fail(function() 
			{
				ajaxFailNoty();
			});			
		}
	});
	$('.add-profile-item-links').click(function()
	{
		var $this = $(this),
		 	editElementSelector = '#'+$this.attr('data-edit-element-id'),
		 	type = $this.attr('data-type');

		showBlockEditElement('', editElementSelector, type);
	});

	$('.cancel-buttons').click(function()
	{
		hideEditElement();
	});

	$('.absolute-profile-edit-elements form, .block-profile-edit-elements form').submit(function() 
	{
		var $form = $(this),
			$overlay = $form.find('.ajax-overlays');

		<? if ($user['role'] != ROLE_STUDENT): ?>
		
		// This only does something in availability		
		$(this).find('input[name=availability]').val(JSON.stringify(avail));
		
		<? endif; ?>
		
		var key = $form.find('[name=update-key]').val()

		if (key == 'location' && profileLocationHasClientError)
		{
			$('#location').focus();
			return false;
		}

		$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('profile/update') ?>",
			data: $form.serialize(),
			dataType: 'json'
		}).done(function(response) 
		{
//			log(response.data.temp_data);

			var $editElement = $form.parents('.absolute-profile-edit-elements');

			$form.validate(response.errors);
		
			if (response.success == true)
			{
				switch (key)
				{
					case 'display_name':

						if (response.status == <?= STATUS_NOTHING_HAPPENED ?>)
						{
							break;
						}

						var name = response.data.displayName,
							role,
							profileLink;

						$('#user-name-content, #account-link').text(name);
						$('#user-contact-button-content').text('Contact ' + name);	
//					    $('#user-name, #user-contact-button').textfill({ maxFontPixels: 26 });

			    		<? if ($user['role'] != ROLE_STUDENT): ?>
			    			role = 'tutors';
						<? else: ?>
							role = 'students';
						<? endif; ?>

						if (response.data.hasOwnProperty('username'))
						{
						    profileLink = baseUrl(role+"/"+response.data.username);
						    $('#profile-link').text('tutorical.com/'+role+'/'+response.data.username).attr('href', profileLink);							
						}

					    $('#display-name-indicator').addClass('set');
						break;

				<? if ($user['role'] != ROLE_STUDENT): ?>
					case 'gender':
						var val = $('[name=gender]:checked').val(),
							genderText;

							switch (val)
							{
								case 'm':
									genderText = 'Male';
									break;
								case 'f':
									genderText = 'Female';
									break;
								default:
									genderText = '<span class="default-values">( Gender )</span>';
							}
							// // console.log(val);
						$('#gender').html(genderText);	
						break;

					case 'price':
						var priceType = $('[name=price-type]:checked').val(),
							$price = $('.price-val');

							// This is a switch because we'll be adding 1-2 more payment types
							switch (priceType)
							{
								case 'per_hour':
//									var hourlyPrice = toMoney($('#hourly-rate').val()),
									var currency = $('#currency').val(),
										currencySign = response.data.currencySign,
										hourlyPrice = currencySign + toMoney($('#hourly-rate').val()),
										hourlyPriceHigh = $('#hourly-rate-high').val(),
										price;

										if (hourlyPriceHigh)
										{
											hourlyPriceHigh = currencySign + toMoney(hourlyPriceHigh);
											price = '<span class="hourly-prices price-ranges">' + hourlyPrice + ' &#8211; ' + hourlyPriceHigh + '<span class="per-hour"> / hour</span> <span class="currencies">('+currency+')</span></span>';
										}
										else
										{
											price = '<span class="hourly-prices">' + hourlyPrice + '<span class="per-hour"> / hour</span> <span class="currencies">('+currency+')</span></span>';
										}

									$price.html(price);

									// // console.log(hourlyPrice, currency, price);

									break;
								default:
									var reason = $('#reason').val(),
										price = '<span class="frees">Free</span>';

									if (reason)
									{
										price += '<span class="reason-for-frees"> <span class="tiny-aftertext">(<span class="same-page-links no-pointer" title="'+reason+'">why?</span>)</span></span>';
									}

									$price.html(price);
									
									if (reason)
									{
									  $('.downwards-qtipped').qtip(
									  {
									    position: 
									    {
									      my: 'left top',
									      at: 'right center',
									      adjust: 
									      {
									        x: 5
									      }
									    }
									  });										
									}
							}
					    $('#price-indicator').addClass('set');
						break;

					case 'location':
						var country = $('#country-val').val();
						$('#city', '#text-location').text($('#city-val').val());
						$('#country', '#text-location').text(country);
						$('#flag').attr('src', "<?= base_url('assets/images/flags') ?>"+'/'+getCountryCode(country)+'.gif');

						var bounds = new google.maps.LatLngBounds(
								new google.maps.LatLng($('#sw_lat').val(), $('#sw_lon').val()),
								new google.maps.LatLng($('#ne_lat').val(), $('#ne_lon').val()));

						profileMap.setCenter(new google.maps.LatLng($('#lat').val(), $('#lon').val()));

						if (profileMarker != null)
						    profileMarker.setMap(null);

						profileMarker = new google.maps.Marker(
						{
							map: profileMap,
						    animation: google.maps.Animation.DROP,
						    position: profileMap.center
						});

//						profileMap.fitBounds(bounds);
						profileMap.setZoom(14);

					    $('#location-indicator').addClass('set');

						break;

					case 'subjects':
						var subjectsHTML = '',
							subjects = $('#subjects-input').val().split(','),
							subjectsLength = subjects.length,
							i;

						for (i = 0; i < subjectsLength; i++)
						{
							subjectsHTML += '<span class="subject-names">'+subjects[i]+'</span>';
						}

						$('#subjects-item').html(subjectsHTML);
					    $('#subjects-indicator').addClass('set');
						break;

					case 'availability':
						$('#availability-item').html(response.data.html);
						setupOvernightTimeLinks('#availability-item');

						break;

					case 'can_meet':

						$('.can-meet-items').removeClass('checked');

						$form.find('.form-inputs input').each(function()
						{
							var name = $(this).attr('name');

							if (this.checked)
								$('.'+name, '#can-meet').addClass('checked');
						});

						break;

					case 'education':
						var itemId = $form.find('[name=item-id]').val(),
							$item,
							$tempEl;

						if (!itemId)
						{
							itemId = response.data.rowId;
							$tempEl = $('<div class="education-items block-items" id="education-'+itemId+'" data-item-type="education" data-item-id="'+itemId+'"><div class="school-conts"><span class="schools"></span><span class="icon-conts"><img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="education-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>"></span></div><div class="studied"><div class="degrees-fields"><span class="degrees"></span><span class="degree-field-divs"> - </span><span class="fields"></span></div><div class="start-ends"><span class="start-years"></span> - <span class="end-years"></span></div></div><div class="education-notes" style=""></div></div>');
							
							$('#education-item-cont').append($tempEl);
							updateSortable('#education-item-cont');

						}
						$item = $('.education-items[data-item-id='+itemId+']');

						activateBlockEditElement($item.find('.edit-icons'));

						var values = 
							{
								school: $form.find('[name=school]').val(),
								field: $form.find('[name=field]').val(),
								degree: $form.find('[name=degree]').val(),
								startYear: $form.find('[name=start-year]').val(),
								endYear: $form.find('[name=end-year] option:selected').text(),
								notes: nl2br(stripTags($form.find('[name=notes]').val()))
							};

						var $notes = $item.find('.education-notes'),
							$degreeFieldDiv = $item.find('.degree-field-divs'),
							$degreeFieldCont = $item.find('.degrees-fields');

						$item
							.find('.schools').text(values.school).end()
							.find('.fields').text(values.field).end()
							.find('.degrees').text(values.degree).end()
							.find('.start-years').text(values.startYear).end()
							.find('.end-years').text(values.endYear);

						if (!(values.field && values.degree))
							$degreeFieldDiv.hide();
						else
							$degreeFieldDiv.show();

						if (!(values.field || values.degree))
							$degreeFieldCont.hide();
						else
							$degreeFieldCont.show();

						if (values.notes)
							$notes.show().html(values.notes);
						else
							$notes.hide();

						break;

					case 'experience_volunteering':
						var itemId = $form.find('[name=item-id]').val(),
							type = $form.parent().attr('data-item-type'),
							$item,
							$tempEl;

						if (!itemId)
						{
							itemId = response.data.rowId;
							$tempEl = $('<div class="'+type+'-items block-items" data-item-type="'+type+'" id="experience-'+itemId+'" data-item-id="'+itemId+'"><div class="companies"><span class="company-values"></span><span class="icon-conts"><img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="'+type+'-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>"></span></div><div class="positions-dates-locations"><span class="positions"></span><div class="dates-locations"><span class="start-months"></span> <span class="start-years"></span> - <span class="end-months"></span> <span class="end-years"></span><span class="locations"> | <span class="location-values"></span></span></div></div><div class="descriptions"></div></div>');
//							$tempEl = $('<div class="experience-items block-items" data-item-type="experience" data-item-id="'+itemId+'"><div class="positions"><span class="position-values"></span><img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="experience-element" src="<?= base_url(ICON_EDIT) ?>"></div><div class="companies-dates"><span class="companies"></span><span class="locations"> | <span class="location-values"></span></span><div class="dates"><span class="start-months"></span> <span class="start-years"></span> - <span class="end-months"></span> <span class="end-years"></span></div></div><div class="descriptions"></div></div>');
							$('#'+type+'-item-cont').append($tempEl);
							updateSortable('#'+type+'-item-cont');
						}
						$item = $('.'+type+'-items[data-item-id='+itemId+']');

						activateBlockEditElement($item.find('.edit-icons'));

						var values = 
						{
							company: $form.find('[name=company]').val(),
							position: $form.find('[name=position]').val(),
							location: $form.find('[name=location]').val(),
							startMonth: $form.find('[name=start-month] option:selected').text(),
							startYear: $form.find('[name=start-year]').val(),
							currentlyWork: $form.find('[name=current]').val(),
							description: nl2br(stripTags($form.find('[name=description]').val()))
						};

						if ($form.find('[name=current]').is(':checked'))
						{
							values.endMonth = "";
							values.endYear = "Present";
						}
						else
						{
							values.endMonth = $form.find('[name=end-month] option:selected').text();
							values.endYear = $form.find('[name=end-year]').val();
						}

						if (values.startMonth == '<?= $months[0] ?>')
						{
							values.startMonth = '';
						}

						if (values.endMonth == '<?= $months[0] ?>')
						{
							values.endMonth = '';
						}

						if (values.company == '')
						{
							values.company = 'Self-Employed';
							$item.find('.company-values').addClass('self-employed');							
						}
						else
						{
							$item.find('.company-values').removeClass('self-employed');														
						}

						var $description = $item.find('.descriptions');

//						log(values);

						$item
							.find('.positions').text(values.position).end()
							.find('.company-values').text(values.company).end()
							.find('.location-values').text(values.location).end()
							.find('.start-months').text(values.startMonth).end()
							.find('.start-years').text(values.startYear).end()
							.find('.end-months').text(values.endMonth).end()
							.find('.end-years').text(values.endYear).end()
							.find('.descriptions').html(values.description);

						if (values.description)
							$description.show().html(values.description);
						else
							$description.hide();

						break;

					case 'link':
						var itemId = $form.find('[name=item-id]').val(),
							$item,
							$tempEl;

						if (!itemId)
						{
							itemId = response.data.rowId;
							$tempEl = $('<div class="link-items block-items" id="link-'+itemId+'" data-item-type="link" data-item-id="'+itemId+'"><div class="link-images regular-link-image"></div><a target="_blank" class="user-links" rel="nofollow"></a><span class="icon-conts"><img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="link-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>"></span></div>');
							$('#links-item-cont').append($tempEl);
							updateSortable('#links-item-cont');
						}
						$item = $('.link-items[data-item-id='+itemId+']');

						activateBlockEditElement($item.find('.edit-icons'));

						var values = 
							{
								label: $form.find('[name=label]').val(),
								url: response.data.url,
								description: $form.find('[name=description]').val()
							};

						if (!values.label)
							values.label = $form.find('[name=url]').val()

						$item
							.find('.user-links')
								.attr('href', values.url)
								.attr('title', values.description)
								.text(values.label);

						break;

					case 'about':
						$('#about-item').html(nl2br(stripTags($("<?= '#'.$about['id'] ?>").val())));
						break;

					case 'snippet':
						$('#snippet-item').html(stripTags(nl2br($("<?= '#'.$snippet['id'] ?>").val())));
					    $('#snippet-indicator').addClass('set');
						break;

					case 'travel_notes':
						$('#travel-notes').html(stripTags(nl2br($("<?= '#'.$travel_notes['id'] ?>").val())));
						break;

					case 'external_review':
						var itemId = $form.find('[name=item-id]').val(),
							$item,
							$tempEl;

						if (!itemId)
						{
							itemId = response.data.rowId;
							$tempEl = $('<div class="er-items block-items" id="er-'+itemId+'" data-item-type="er" data-item-id="'+itemId+'"><div class="er-ratings-and-icons"><div class="er-ratings"><form><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="0.5" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="1.0" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="1.5" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="2.0" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="2.5" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="3.0" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="3.5" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="4.0" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="4.5" ><input name="rating" type="radio" disabled="disabled" class="star {split:2}" value="5.0" ></form></div><div class="icon-conts"><img title="Click to edit" class="edit-icons block-edit-icons" data-edit-element-id="er-element" src="<?= base_url(ICON_EDIT) ?>"><img class="vert-move-icons" title="Drag to reorder" src="<?= base_url(ICON_VERT_MOVE) ?>"></div></div><div class="er-content-and-metas"><div class="er-contents"></div><div class="er-metas"><span class="meta-items er-name-items">By: <span class="er-names"></span></span> | <a title="Visit the original website where this review was posted" target="_blank" rel="nofollow" href="" class="meta-items er-url-items">Visit Site</a></div></div></div>');

							$('#er-item-cont').append($tempEl);
							$tempEl.find('input[type=radio].star').rating();

							updateSortable('#er-item-cont');
						}

						$item = $('.er-items[data-item-id='+itemId+']');

						activateBlockEditElement($item.find('.edit-icons'));

						var values = 
							{
								reviewer: $form.find('[name=reviewer]').val(),
								content: nl2br(stripTags($form.find('[name=content]').val())),
								url: response.data.url
							};

						var ratingIndex = $form.find('.star:checked').index()-1
						values.rating = ratingIndex;

						// ratingIndex is -2 if nothing checked since .index() returns -1, then 1 is subtracted
						if (values.rating < 0)
						{
							$item.find('.er-ratings').hide();
						}
						else
						{
							$item.find('.er-ratings').show();							
						}

						$item
							.find('.er-contents').html(values.content).end()
							.find('.er-names').text(values.reviewer).end()
							.find('.er-url-items').attr('href', values.url).end()
							.find('[name=rating]')
								.rating('enable')
								.rating('select', values.rating)
								.rating('disable');

						break;
				<? endif; ?>
				}
				hideEditElement();

				<? if ($user['role'] != ROLE_STUDENT): ?> 
				if (response.data && response.data.profileJustMade)
				{
					window.location = "<?= current_url() ?>";
				}
				<? endif; ?>

				profileUpdatedNoty();
			}
			else if (response.status == <?= STATUS_DATABASE_ERROR ?> || response.status == <?= STATUS_UNKNOWN_ERROR ?>)
			{
				ajaxFailNoty();
			}
		}).always(function() 
		{
			$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);		
		}).fail(function()
		{
			ajaxFailNoty();
		});
		return false;
	});

	// UPLOADIFY + Jcrop CODE //

	var jcrop_api = null,
		$loader = $('#dropdown-avatar-edit .ajax-overlays, #user-avatar-cont .ajax-overlays');

	$('#remove-photo-link').click(function()
	{
		$loader.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$.ajax({
			type: "POST",
			url: "<?= base_url('profile/update') ?>",
			data: {
				x : 0,
				y : 0,
				h : <?= AVATAR_HEIGHT ?>,
				w : <?= AVATAR_WIDTH ?>,
				src : "<?= DEFAULT_AVATAR_PATH ?>",
				'update-key': 'photo'
			},
			dataType: 'json'
		}).done(function(response) {
			// // console.log(response);
			if (response.success == true)
			{
				var avatarPath = '<?= "assets/uploads/images/$user_id/avatar.jpg" ?>';
				$('#avatar-path').val(avatarPath);
		    	$('#user-avatar, #header-avatar img').attr('src', baseUrl(avatarPath) + '?' + Math.random());
		    	
		    	profileUpdatedNoty()
				$('#jcrop-interface').foundation('reveal', 'close');
			}
			else
			{
				ajaxFailNoty();
			}
		}).always(function() 
		{
			$loader.fadeOut(<?= OVERLAY_FADE_SPEED ?>);
		}).fail(function() 
		{
			ajaxFailNoty();
		});	
	})

	$('#avatar-edit-link').uploadify({
	    'swf'				: "<?= base_url('assets/js/uploadify/uploadify.swf') ?>",
	    'uploader' 			: "<?= base_url('assets/js/uploadify/uploadify.php') ?>",
	    'buttonText'		: '',
	    'buttonClass'		: 'uploadify-button',
	    'width'				: '112',
	    'height'			: '24',
	    'multi'				: false,
	    'onUploadStart' 	: function()
	    {
	    	$loader.fadeIn(<?= FAST_FADE_SPEED ?>);
//    		fadeOutAvatarEdit();
		},
	    'onUploadSuccess' 	: function(file, data, response) 
	    {	
	    	data = $.parseJSON(data);

	    	if (data)
			{
				if (data['error'] == 'bad image')
				{
					noty({
						text: "Sorry. We couldn't process that file for a some reason. Please send it as an attachment to <a href='mailto:<?= SITE_EMAIL ?>'><?= SITE_EMAIL ?></a> and we'll add it to your profile.",
						timeout: 14000,
						layout: 'topCenter',
						type: 'warning'
					});
					return;
				}
				else if (data['error'] == 'bad filetype')
				{
					noty({
						text: "Please select a JPG, PNG, or GIF file.",
						timeout: 3000,
						layout: 'topCenter',
						type: 'warning'
					});
					return;
				}

				data['name'] = "<?= base_url() ?>assets/uploads/tmp/" + data['name'];
				switchJcropImage(data);					
			}
			else
			{
				ajaxFailNoty();
			}
	    },
	    'onUploadComplete' 	: function() 
	    {
	    	$loader.fadeOut(<?= FAST_FADE_SPEED ?>);
	    	setAvatarHover();
    		$('#dropdown-avatar-edit').dropdown('hide');
		},
		'onUploadError' : function()
		{
	    	$loader.fadeOut(<?= FAST_FADE_SPEED ?>);
	    	ajaxFailNoty();			
		}
	});

	function switchJcropImage(imgData)
	{
		var jcropWidth; 

		// If insteance of jcrop already exists, destroy it
		if (jcrop_api != null)	
		{
			jcrop_api.destroy();
			$('#jcrop-target').removeAttr('style');
		}	

		var $target = $('#jcrop-target')
					  .attr('src', imgData['name'])
					  .attr('width', imgData['trueWidth'])
					  .attr('height', imgData['trueHeight']),
			targetHeight = $target.height(),
			targetWidth = $target.width(),
			$preview = $('#jcrop-preview').attr('src', imgData['name']),
			$previewCont = $('#jcrop-preview-cont-parent'),
			previewHeightAdjusted = false,
			boundx, 
			boundy;
	    
		$target.Jcrop(
		{
			onChange: updatePreview,
			onSelect: updatePreview,
			onRelease: updatePreview,
			setSelect: [ 0, 0, avatarWidth, avatarHeight ],
			minSize: [33, 33],
			aspectRatio: 1
//			truesize: [imgData['trueWidth'], imgData['trueHeight']]
		}, function() 
		{
			// Use the API to get the real image size
			var bounds = this.getBounds();
			boundx = bounds[0];
			boundy = bounds[1];
			// Store the API in the jcrop_api variable
			jcrop_api = this;
		    jcrop_api.animateTo([ 0, 0, avatarWidth, avatarHeight ]);
			recordPhotoVars(0, 0, avatarWidth, avatarHeight);
		});

	    $('#no-handles-link').unbind('click').click(function() 
	    {
	    	jcrop_api.animateTo([ 0, 0, avatarWidth, avatarHeight ]);
	    })

		function updatePreview(c) 
		{			
			if (!previewHeightAdjusted && $target.height() < $previewCont.height() )
			{
				previewHeightAdjusted = true;
				$previewCont.css('vertical-align', 'top');
			}
			else if (!previewHeightAdjusted)
			{
				$previewCont.css('vertical-align', 'middle');					
			}
	//				// // console.log(previewHeightAdjusted, $previewCont.height(), $target.height());
			if (parseInt(c.w) > 0)
			{
				var rx = previewWidth / c.w;
				var ry = previewHeight / c.h;

				$preview.css(
				{
					width: Math.round(rx * boundx) + 'px',
					height: Math.round(ry * boundy) + 'px',
					marginLeft: '-' + Math.round(rx * c.x) + 'px',
					marginTop: '-' + Math.round(ry * c.y) + 'px'
				});
			}

			recordPhotoVars(c.x, c.y, c.w, c.h);

		};

		$('#confirm-jcrop').unbind('click').click(cropAndUpdate);

		jcropWidth = $('.jcrop-holder').outerWidth() + $('.jcrop-preview-cont-parent').outerWidth();
/*
		if (vpWidth > jcropWidth)
		{
			$('#jcrop-interface').css({
				width: jcropWidth,
				marginLeft: -jcropWidth/2
			});		
		}
*/

		$('#jcrop-interface').foundation('reveal', 'open');
//		log($('.jcrop-holder').outerWidth(), $('.jcrop-preview-cont-parent').outerWidth())

		$('#jcrop-interface .close-reveal-modal, #cancel-jcrop').unbind('click').click(function() 
		{
			$('#jcrop-interface').foundation('reveal', 'close');
		});
/*
	    $('#jcrop-interface').lightbox_me({
	    	centered : true, 
	    	'overlayCSS' : { background: 'black', opacity: .95 },
	    	zIndex: 998
	    });

		$('#jcrop-interface').css('visibility', 'visible');
	    $('#jcrop-interface .close-reveal-modal, #cancel-jcrop').unbind('click').click(function() 
	    {
	    	$('#jcrop-interface').trigger('close');
	    });
*/
	}

	function recordPhotoVars(x, y, w, h)
	{
		$('#x1').val(x);
		$('#y1').val(y);
		$('#w').val(w);
		$('#h').val(h);
	}

	function cropAndUpdate()
	{
		var $overlay = $('#jcrop-interface .ajax-overlays');

		$overlay.fadeIn(<?= OVERLAY_FADE_SPEED ?>);

		$.ajax({
			type: "POST",
			url: "<?= base_url('profile/update') ?>",
			data: {
				x : $('#x1').val(),
				y : $('#y1').val(),
				h : $('#h').val(),
				w : $('#w').val(),
				src : $('#jcrop-target').attr('src'),
				'update-key': 'photo'
			},
			dataType: 'json'
		}).done(function(response) {
			// // console.log(response);
			if (response.success == true)
			{
				var avatarPath = '<?= "assets/uploads/images/$user_id/avatar.jpg" ?>';
				$('#avatar-path').val(avatarPath);
		    	$('#user-avatar, #header-avatar img').attr('src', baseUrl(avatarPath) + '?' + Math.random());

		    	profileUpdatedNoty();

				$('#jcrop-interface').foundation('reveal', 'close');
			}
			else
			{
				ajaxFailNoty();
			}
		}).always(function() 
		{
			$overlay.fadeOut(<?= OVERLAY_FADE_SPEED ?>);	    	
		}).fail(function() 
		{
			ajaxFailNoty();
		});
	}
});

function activateEditElement(selector)
{
	$(selector).click(function() 
	{
		 var editElementSelector = '#'+$(this).attr('data-edit-element-id');
		 showEditElement(editElementSelector);
	});		
}

function activateBlockEditElement(selector)
{
	// // console.log(selector);
	$(selector).click(function() 
	{
		 var $this = $(this),
		 	 editElementSelector = '#'+$this.attr('data-edit-element-id'),
		 	 referenceId = $this.attr('data-reference-id');

		if (referenceId)
		{
			var $blockElement = $('#'+referenceId);
		}
		else
		{
		 	var $blockElement = $this.parents('.block-items');
		}
		
		var type = $blockElement.attr('data-item-type');

//		log(type, $blockElement.length);

		showBlockEditElement($blockElement, editElementSelector, type);
	});		
}

function showBlockEditElement($blockElement, editElementSelector, type)
{	
	if (doneNoty)
		doneNoty.close();

	var $editElement = $(editElementSelector),
		blockItem = getBlockItem($blockElement, type),
		$textareas = $editElement.find('textarea'),
		$addProfileItemLinks = $editElement.siblings('.add-profile-item-links');

	$editElement.find(':input').hideErrors();
	
	if ($addProfileItemLinks.length)
	{
		$addProfileItemLinks.addClass('hidden');
	}	
	
	if ($editElement.hasClass('in-subsection'))
	{
		$editElement.siblings('.profile-sec-subsection-headings').hide().appendTo($editElement.parent());
	}

	if ($blockElement)
	{
		$editElement.find('.remove-item-links').show();
		$blockElement.hide().addClass('currently-editing').before($editElement);			
	}
	else
	{
		$editElement.find('.remove-item-links').hide();
		$('.add-profile-item-links[data-type='+type+']').hide();
		$editElement.parents('.profile-sec-contents').append($editElement);
	}

	// Have to do this because we can't re-autosize()
	$textareas.each(function()
	{
		var $this = $(this),
			$clone;

		$this.css('height', '');
		$clone = $this.clone();
		$this.replaceWith($clone);
	})

	fillEditElementValues(blockItem, type);

	$editElement.addClass('active').css('z-index','1000').slideDown(<?= FAST_FADE_SPEED ?>, function() 
	{
		// Can't use $textareas because of clones
		$editElement.find('textarea').autosize();
		scrollAndFocus($editElement, true);
	});
	$('#global-overlay').fadeIn(<?= FAST_FADE_SPEED ?>);	
}


function fillEditElementValues(values, type)
{
	if (type == 'education')
	{
		var $editElement = $('#education-element');

		$editElement.find('[name=item-id]').val(values.id).end()
					.find('[name=school]').val(values.school).end()
					.find('[name=field]').val(values.field).end()
					.find('[name=degree]').val(values.degree).end()
					.find('[name=notes]').val(values.notes).end()
					.find('[name=start-year]').val(values.startYear).trigger("liszt:updated").end()
					.find('[name=end-year]').val(values.endYear).trigger("liszt:updated");
	}
	else if (type == 'experience' || type == 'volunteering')
	{
		var $editElement = $('#'+type+'-element');
	
		$editElement.find('[name=item-id]').val(values.id).end()
					.find('[name=company]').val(values.company).end()
					.find('[name=position]').val(values.position).end()
					.find('[name=location]').val(values.location).end()
					.find('[name=start-month] > option:contains('+values.startMonth+')').prop('selected', true).trigger("liszt:updated").end()
					.find('[name=start-year]').val(values.startYear).trigger("liszt:updated").end()
					.find('[name=end-year]').val(values.endYear).trigger("liszt:updated").end()
					.find('[name=end-month] > option:contains('+values.endMonth+')').prop('selected', true).trigger("liszt:updated").end()
					.find('[name=description]').val(values.description).end()
					.find('[name=current]').prop('checked', values.currentlyWork);
//			$editElement.find('[name=end-month], [name=end-year]').prop('disabled', values.currentlyWork).trigger("liszt:updated");

		if (values.currentlyWork)
		{
			$editElement.find('.reg-time-ends').hide();
			$editElement.find('.current-time-ends').show();
		}
		else
		{
			$editElement.find('.reg-time-ends').show();
			$editElement.find('.current-time-ends').hide();			
		}
	}
	else if (type == 'link')
	{
		var $editElement = $('#link-element');

		$editElement.find('[name=item-id]').val(values.id).end()
					.find('[name=label]').val(values.label).end()
					.find('[name=url]').val(values.url).end()
					.find('[name=description]').val(values.description);
	}
	else if (type == 'er')
	{
		var $editElement = $('#er-element');

		$editElement.find('[name=item-id]').val(values.id).end()
					.find('[name=reviewer]').val(values.reviewer).end()
					.find('[name=url]').val(values.url).end()
					.find('[name=content]').val(values.content);
		
		if (values.rating)
		{
			$editElement.find('[name=rating]').rating('select', values.rating);
		}
		else
		{
			$editElement.find('.rating-cancel').click();
		}
	}
	else if (type != 'subjects')
	{
		var $editElement = $('#' + type + '-input');
		$editElement.val(stripBrs($.trim(values)));
	}
}

function getBlockItem($blockElement, type)
{
	var blockItem;

	if ($blockElement)
	{
		if (type == 'education')
		{
			blockItem =
			{
				id: $blockElement.attr('data-item-id'),
				school: $blockElement.find('.schools').text(),
				field: $blockElement.find('.fields').text(),
				degree: $blockElement.find('.degrees').text(),
				startYear: $blockElement.find('.start-years').text(),
				endYear: $blockElement.find('.end-years').text(),
				notes: stripBrs($.trim($blockElement.find('.education-notes').text()))
			}

			if (isNaN(blockItem.graduated))
			{
				blockItem.graduated = 0;
			}				
		}
		else if (type == 'experience' || type == 'volunteering')
		{
			blockItem =
			{
				id: $blockElement.attr('data-item-id'),
				company: $blockElement.find('.company-values').text(),
				position: $blockElement.find('.positions').text(),
				location: $blockElement.find('.location-values').text(),
				startMonth: $blockElement.find('.start-months').text(),
				startYear: $blockElement.find('.start-years').text(),
				endMonth: $blockElement.find('.end-months').text(),
				endYear: $blockElement.find('.end-years').text(),
				description: stripBrs($.trim($blockElement.find('.descriptions').text()))
			}

			if (!blockItem.startMonth)
			{
				blockItem.startMonth = '(Month)';
			}
			if (!blockItem.endMonth)
			{
				blockItem.endMonth = '(Month)';
			}

			if (blockItem.endYear == 'Present')
			{
				blockItem.endYear = '<?= date('Y') ?>';
				blockItem.endMonth = '(Month)';
				blockItem.currentlyWork = true;
			}
			else
			{
				blockItem.currentlyWork = false;
			}

			if (blockItem.company == 'Self-Employed')
			{
				blockItem.company = '';
			}
		}
		else if (type == 'link')
		{
			var $link = $blockElement.find('.user-links');

			blockItem =
			{
				id: $blockElement.attr('data-item-id'),
				label: $link.text(),
				url: $link.attr('href'),
				description: $link.attr('title')
			};
		}
		else if (type == 'er')
		{
			blockItem =
			{
				id: $blockElement.attr('data-item-id'),
				reviewer: $blockElement.find('.er-names').text(),
				url: $blockElement.find('.er-url-items').attr('href'),
				content: $.trim($blockElement.find('.er-contents').text())
			};
			var ratingIndex = $blockElement.find('.star:checked').index()-1
			blockItem.rating = ratingIndex;
		}
		else
		{
			blockItem = stripBrs($blockElement.html());
		}
	}
	else
	{
		if (type == 'education')
		{
			blockItem =
			{
				id: '',
				school: '',
				field: '',
				degree: '',
				notes: ''
			}
		}
		else if (type == 'experience' || type == 'volunteering')
		{
			blockItem =
			{
				id: '',
				company: '',
				position: '',
				location: '',
				startMonth: '(Month)',
				startYear: '<?= date('Y') ?>',
				endMonth: '(Month)',
				endYear: '<?= date('Y') ?>',
				description: '',
				currentlyWork: false
			}
		}
		else if (type == 'link')
		{
			blockItem =
			{
				id: '',
				label: '',
				url: '',
				description: ''
			};
		}
		else if (type == 'er')
		{
			blockItem =
			{
				id: '',
				reviewer: '',
				url: '',
				content: '',
				rating: 0
			};
		}
		else if (type != 'subjects')
		{
			blockItem = '';
		}
	}

	return blockItem;
}



function showEditElement(editElementSelector)
{
	if (doneNoty)
		doneNoty.close();

	$userIntro.trigger('mouseleave');
	$userAvatarCont.addClass('deactivated');

	$editElement = $(editElementSelector).css('z-index','1000');
	$editElement.addClass('active');

	$editElement.parent().children(':visible').first().addClass('first');

	$editElement.find(':input').hideErrors();	

	$editElement.add('#global-overlay').fadeIn(<?= FAST_FADE_SPEED ?>, function()
//	$editElement.fadeIn(<?= FAST_FADE_SPEED ?>, function()
	{
		if (editElementSelector == '#location-element')
		{
			google.maps.event.trigger(modalMap, "resize");			
		}

		$editElement.find('textarea').autosize();
		scrollAndFocus($editElement, true);
	});
}

function hideEditElement()
{
	var $absoluteEditElement = $('.absolute-profile-edit-elements:visible'),
		$blockEditElement = $('.block-profile-edit-elements:visible'),
		$addProfileItemLinks = $('.add-profile-item-links.hidden'),
		blockInSubsection = false;

	// // console.log('$blockEditElement', $blockEditElement.length);
	$('.currently-editing').removeClass('currently-editing').slideDown(<?= FAST_FADE_SPEED ?>);

	if ($absoluteEditElement.hasClass('in-subsection'))
	{
		$absoluteEditElement.siblings('.profile-sec-subsection-headings').prependTo($absoluteEditElement.parent()).show();
	}

	if ($blockEditElement.hasClass('in-subsection'))
	{
		blockInSubsection = true;
		$blockEditElement.siblings('.profile-sec-subsection-headings').prependTo($blockEditElement.parent()).show();
	}

	$blockEditElement.removeClass('active').slideUp(<?= FAST_FADE_SPEED ?>, function() 
	{
		if (!blockInSubsection)
		{
			var $parent = $blockEditElement.parents('.profile-sec-contents');

			// For col-2 block edit elements			
			if ($parent.length == 0)
			{
				$parent = $blockEditElement.parents('.profile-secs');
			}	

			$parent.append($blockEditElement).append($addProfileItemLinks);
			$addProfileItemLinks.show().removeClass('hidden');			
		}
		
	});
 
  	$absoluteEditElement.removeClass('active');
    $('#global-overlay').add($absoluteEditElement).fadeOut(<?= FAST_FADE_SPEED ?>, function()
    {
    	setTimeout(function() 
    	{
    		$userAvatarCont.removeClass('deactivated');
    		if ($('#user-intro:hover').length > 0)
    			$userIntro.trigger('mouseenter');
    	}, <?= FAST_FADE_SPEED ?>);

        $absoluteEditElement.css('z-index','1');
    });

}

function setAvatarHover()
{
	$('#user-avatar-cont').hover(fadeInAvatarEdit, fadeOutAvatarEdit);
}

function fadeInAvatarEdit()
{
	// We can't just use fadeIn and fadeOut because Uploadify poops up when the .uploadify element has its display set to none
	$('#avatar-edit').animate(
	{
		'opacity': 0.7
	}, <?= FAST_FADE_SPEED ?>);
}

function fadeOutAvatarEdit()
{
	$('#avatar-edit').animate(
	{
		'opacity': 0
	}, <?= FAST_FADE_SPEED ?>);
}

function makeSortable(selector)
{
	$(selector).sortable(
	{
    	handle: '.vert-move-icons',
    	forcePlaceholderSize: true,
    	cursor: 'move',
		placeholder: 'item-placeholder',
    	revert: 200,
		update: function(event, ui) 
		{
			var $this = $(this);

			var data =
			{
				items: $this.sortable('serialize'),
				type: $this.attr('data-type')
			};

//			log(data);

			$.ajax(
			{
				type: "POST",
				url: "<?= base_url('profile/update_order') ?>",
				data: data,
				dataType: 'json'
			}).done(function(response) 
			{
//				// console.log(response);

				if (response.success != true)
				{
					ajaxFailNoty();
				}
				else
				{
					profileUpdatedNoty();
				}
			}).fail(function()
			{
				ajaxFailNoty();
			});
		}
    });

	updateSortable(selector);
}

function updateSortable(selector)
{
	var $el = $(selector),
		count;
	
	$el.sortable('refresh');

	count = $el.children().not('.block-profile-edit-elements').length;
//	// console.log(selector + ': ' + count)
	if (count > 1)
	{
		$el.find('.vert-move-icons').show();
	}
	else
	{
		$el.find('.vert-move-icons').hide();								
	}

}

function profileUpdatedNoty()
{
	noty({
		text: "<b>Profile updated!</b>",
		timeout: 1500,
		type: 'success'
	});
}


</script>