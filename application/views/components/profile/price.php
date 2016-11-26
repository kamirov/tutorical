<nav id="account-nav" class="nav-bars">
	<ul>
		<?
			$items = array(
				'dashboard' => array(
					'path' => 'account',
					'label' => 'Dashboard',
					'needs_profile' => FALSE
				),
				'profile' => array(
					'path' => 'account/profile',
					'label' => 'Profile',
					'needs_profile' => TRUE
				),
				'students' => array(
					'path' => 'account/students',
					'label' => 'Students',
					'needs_profile' => TRUE
				),
				'edit' => array(
					'path' => 'account/edit',
					'label' => 'Edit',
					'needs_profile' => FALSE
				),
				'settings' => array(
					'path' => 'account/settings',
					'label' => 'Settings',
					'needs_profile' => FALSE
				)
			);

			$nav = '';
			foreach ($items as $item_name => $item)
			{
				if ($item['needs_profile'] && !$profile_made)
				{
					continue;
				}

				$additional_classes = '';
				if ($active == $item_name)
				{
					$additional_classes .= ' active ';
				}

				$nav .= '<li class="'.$additional_classes.'">'.anchor($item['path'], $item['label']).'</li>';

			}
		
			echo $nav;
		?>

	</ul>
	<div class="nav-indicators" id="account-nav-indicator"></div>
</nav>

