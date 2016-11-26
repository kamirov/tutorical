<?
	$requests_title = 'See the status of your tutor requests';

	if ($role == ROLE_STUDENT)
	{
		$requests_title .= ' and applications';
	}

	$items = array(
		'dashboard' => array(
			'path' => 'account',
			'label' => 'Dashboard',
			'requires' => NULL,
			'title' => 'See important account notices'
		),
		'profile' => array(
			'path' => 'account/profile',
			'label' => 'Profile',
			'requires' => NULL,
			'title' => 'Edit and view your profile'
		),
		'requests' => array(
			'path' => 'account/requests',
			'label' => 'Requests',
			'requires' => NULL,
			'title' => $requests_title 
		),
		'students' => array(
			'path' => 'account/students',
			'label' => 'Students',
			'requires' => NULL,
/*
			'requires' => 'has_students',
			'inactive_title' => 'Available when a student contacts you through your profile or accepts you for a tutor request',
*/
			'title' => "View all your current and past students' info, their review of you, and their initial message"
		),
		'tutors' => array(
			'path' => 'account/tutors',
			'label' => 'Tutors',
			'requires' => NULL,
/*					
			'requires' => 'has_tutors',
			'inactive_title' => 'Available when you contact a tutor through their profile or accept them for a tutor request',
*/
			'title' => 'View your current and past tutors'
		),
		'marketing' => array(
			'path' => 'account/marketing',
			'label' => 'Marketing',
			'requires' => 'profile_made',
			'inactive_title' => 'Available when you finish making your profile',
			'title' => 'See useful tools, snippets, and tips to market your profile' 
		),
		'settings' => array(
			'path' => 'account/settings',
			'label' => 'Settings',
			'requires' => NULL,
			'title' => 'Make changes to your account' 
		),
		'admin' => array(
			'path' => 'account/admin',
			'label' => 'Admin',
			'requires' => NULL,
			'title' => NULL
		)
	);

	$nav = '';
	$active_nav_item = '';
	
	foreach ($items as $item_name => $item)
	{
		if ($item_name == 'students' && $this->session->userdata('role') == ROLE_STUDENT)
		{
			continue;
		}
		if ($item_name == 'marketing' && $this->session->userdata('role') == ROLE_STUDENT)
		{
			continue;
		}
		if ($item_name == 'admin' && !$this->session->userdata('init'))
		{
			continue;
		}
		$additional_classes = '';

		$requires = $item['requires'];
		if ($requires && !$$requires)
		{
			continue;
/*			
			$additional_classes .= ' inactive ';
			$nav .= '<li class="'.$additional_classes.'"><span title="'.$item['inactive_title'].'">'.$item['label'].'</span></li>';
*/
		}
		else
		{
			if ($active == $item_name)
			{
				$additional_classes .= ' active ';
				$active_nav_item = $item['label'];
			}

			$nav .= '<li class="'.$additional_classes.'" title="'.$item['title'].'">'.anchor($item['path'], $item['label']).'</li>';		
		}
	}
?>

<nav id="account-nav" class="nav-bars">
	<div id="mobile-account-nav-cont">
		<span id="mobile-active-account-item-cont">
			<span id="active-mobile-item" data-dropdown="#dropdown-account-nav">
				<?= $active_nav_item ?> <span id="mobile-account-arrow"></span>
			</span>
			<div id="dropdown-account-nav" class="dropdown dropdown-relative">
				<ul class="dropdown-menu">
					<?= $nav ?>
				</ul>
			</div>
		</span>

	</div>

	<ul id="reg-account-nav" class="nav-lists">
		<?= $nav; ?>
	</ul>
<!--
	<div class="nav-indicators" id="account-nav-indicator"></div>
-->
</nav>

<script>
$(function()
{
/*
	$('#account-nav ul li span[title]').qtip(
	{
		position: 
		{
			my: 'bottom center',
			at: 'top center'
		}
	});	
*/
})
</script>