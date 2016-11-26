<? if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$site_name = 'Tutorical';

$config['home'] = meta("$site_name | Find your perfect tutor", 'Tutorical helps you find local and distance tutors. Search by location or make a tutor request and have tutors find you!', 'tutor,tutoring service,tutoring,find tutor,find a tutor,english tutor,math tutor,high school tutor,toronto tutor,advertise tutor,marketing tutor,find tutor students, find students');
$config['sitemap'] = meta("Sitemap | $site_name");
$config['contact'] = meta("Contact | $site_name");
$config['text-page'] = meta("{TITLE} | $site_name");

$config['tutors-profile'] = meta("{NAME} - Tutor in {LOCATION} | $site_name");
$config['tutors-profile-with-main'] = meta("{NAME} - {SUBJECT} Tutor in {LOCATION} | $site_name");

$config['students-profile'] = meta("{NAME} - Student Profile | $site_name");

$config['find'] = meta("Find a Tutor/Request | $site_name");
$config['find-local-tutors-requests'] = meta("{SUBJECT} {GROUP} near {LOCATION} | $site_name", 'Search results for {SUBJECT} tutors near {LOCATION}');
$config['find-local-subjects'] = meta("Subjects taught near {LOCATION} | $site_name", 'A list of all subjects taught by Tutorical tutors near {LOCATION}');

$config['find-distance-tutors-requests'] = meta("{SUBJECT} Distance {GROUP} | $site_name", 'Search results for {SUBJECT} {GROUP_TEXT}');
$config['find-distance-subjects'] = meta("Subjects taught online | $site_name", 'A list of all subjects taught by Tutorical tutors online');

$config['find-none-found'] = meta("No {SUBJECT} {GROUP} near {LOCATION}...yet! | $site_name");
$config['find-none-found-distance'] = meta("Sorry! No distance {GROUP_TEXT} {SUBJECT}...yet! | $site_name");
$config['find-location-not-found'] = meta("Sorry! Google can't find {LOCATION} | $site_name");
$config['find-over-query'] = meta("Sorry! There's a problem with our connection to Google | $site_name");
$config['find-no-subject'] = meta("Sorry! No {SUBJECT} {GROUP}...yet! | $site_name");

$config['requests'] = meta("Request for {SUBJECT} Tutors in {CITY}, {COUNTRY} | $site_name");
$config['requests-new'] = meta("Make a Tutor Request | $site_name");

$config['auth-login'] = meta("Login | $site_name");
$config['auth-signup-tutor'] = meta("Become a Tutor! | $site_name");
$config['auth-signup-student'] = meta("Are you a Tutorical student? | $site_name");
$config['auth-recovery'] = meta("Account Recovery | $site_name");
$config['auth-reset'] = meta("Password Reset | $site_name");	// This is the form that's shown when they click the recovery link in their email

$config['account-admin'] = meta("Admin - Account | $site_name");
$config['account-dashboard'] = meta("Dashboard - Account | $site_name");
$config['account-profile'] = meta("Profile - Account | $site_name");
$config['account-students'] = meta("Students - Account | $site_name");
$config['account-tutors'] = meta("Tutors - Account | $site_name");
$config['account-settings'] = meta("Settings - Account | $site_name");
$config['account-requests'] = meta("Requests - Account | $site_name");
$config['account-marketing'] = meta("Marketing - Account | $site_name");

$config['error-404'] = meta("Page not found | $site_name");
$config['error'] = meta("Sorry! Something happened! | $site_name");

function meta($title = 'Tutorical', $description = '', $keywords = '') 
{
	$meta = array(
		'title' => $title,
		'description' => $description,
		'keywords' => $keywords
	);

	return $meta;
}		
