<section id="account" class="cf containers pages">

	<h1 id="page-heading">Your Account</h1>

	<?= $account_nav ?>

	<div id="admin-content" class="account-subpage-conts cf">
		<div class="col-1 cols">
			<section id="user-statistics-cont" class="cf account-secs">
				<header>
					<h1>User Statistics (Init : Fin-N : Fin-Y)</h1>
				</header>
				<div id="user-statistics-body">
					<div class="col-1">
						<h3>Tutors</h3>
						<span class="user-statistics-info"><b><?= $admin['num_of_init_tutors'] ?> : <?= $admin['num_of_fin_not_made_tutors'] ?></b>: <b><?= $admin['num_of_fin_made_tutors'] ?></b></span>
					</div>
					<div class="col-2">
						<h3>Students</h3>
						<span class="user-statistics-info"><b><?= $admin['num_of_init_students'] ?> : <?= $admin['num_of_fin_students'] ?></b></span>
					</div>
				</div>
			</section>

			<section id="" class="account-secs">
				<header>
					<h1>UT URL Check</h1>
				</header>
				<form method="post" class="form-cont" id="ut-url-check-form">
					<div class="form-inputs block-inputs">
						<input style="width: 90%;" type="text" placeholder="Profile Url" name="import-url" data-autocomplete-source="universitytutor_urls" class="autocompleted"> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="url-check-loader">
						<div class="form-input-notes error-messages"></div>
					</div>
				</form>		
			</section>
		</div>

		<div class="col-2 cols">
			<section id="" class="account-secs">
				<header>
					<h1>New Admin</h1>
				</header>
				<form method="post" class="form-cont">
					<div class="form-elements">
						<input style="width: 100px;" type="text" placeholder="First Name" name="admin-first-name" autofocus="autofocus">
						<input style="width: 100px;" type="text" placeholder="Last Name" name="admin-last-name">
						<input type="submit" name="role" class="buttons" value="Tutor">
						<input type="submit" name="role" class="buttons" value="Student">
					</div>
				</form>
			</section>
			<section id="" class="account-secs">
				<header>
					<h1>New Imported Tutor</h1>
				</header>
				<form method="post" class="form-cont" id="new-imported-tutor-form">
					<div class="form-elements">
						<input style="width: 100px;" type="text" placeholder="Email" name="import-email">
						<input style="width: 100px;" type="text" placeholder="Profile Url" name="import-url">
						<input type="submit" name="import-tutor" class="buttons" value="Import"> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders" id="import-tutor-loader">
					</div>
				</form>
				<div class="boxes" id="imported-tutor-url-cont">
					<input type="text" placeholder="Resulting Profile URL" class="select-on-click-inputs" id="imported-tutor-url">
				</div>			
			</section>
		</div>
	
		<div class="contacts-table-conts sharables-conts" id="new-tutors-table-cont" style="<? if (empty($admin['new_tutors'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">New Tutors (<span class="counts" id="new-tutors-count"><?= count($admin['new_tutors']) ?></span>)</h2>
			<table class="contact-tables" id="new-tutors-table">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="small-name-cells">Name</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($admin['new_tutors'] as $tutor): 
					?>
					<tr class="contact-rows" data-type="tutor" data-username="<?= $tutor['username'] ?>" data-name="<?= $tutor['name'] ?>" data-city="<?= $tutor['city'] ?>" data-country="<?= $tutor['country'] ?>" data-id="<?= $tutor['id'] ?>" data-name="<?= $tutor['name'] ?>">
						<td class="status-cells">
							<span class="ajax-enabled">
								<span class="ignore-links status-change-links">Clear</span>
							</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
						</td>
						<td class="tutor-name-cells"><?= anchor('tutors/'.$tutor['username'], $tutor['name']) ?></td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="contacts-table-conts sharables-conts" id="new-requests-table-cont" style="<? if (empty($admin['new_requests'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">New Requests (<span class="counts" id="new-requests-count"><?= count($admin['new_requests']) ?></span>)</h2>
			<table class="contact-tables" id="new-requests-table">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="small-name-cells">Name</th>
						<th class="details-cells">Details</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($admin['new_requests'] as $request): 
					?>
					<tr class="contact-rows"  data-type="request" data-id="<?= $request['id'] ?>" data-subject="<?= $request['subject'] ?>" data-city="<?= $request['city'] ?>" data-country="<?= $request['country'] ?>">
						<td class="status-cells">
							<span class="ajax-enabled">
								<span class="ignore-links status-change-links">Clear</span>
							</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
						</td>
						<td class="small-name-cells"><?= anchor('requests/'.$request['id'], $request['name']) ?></td>
						<td class="details-cells"><?= $request['details'] ?></td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>


		<div class="contacts-table-conts" id="subject-table-cont" style="<? if (empty($admin['subjects'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">Subjects (<span class="counts" id="subject-count"><?= count($admin['subjects']) ?></span>)</h2>
			<table class="contact-tables" id="subject-table">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="subject-category-cells">Category</th>
						<th class="name-cells">Name</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($admin['subjects'] as $subject): 
					?>
					<tr class="contact-rows" data-id="<?= $subject['id'] ?>">
						<td class="status-cells">
							<span class="ajax-enabled">
								<a href="javascript:void(0);" class="ignore-links status-change-links">Deactivate</a>
							</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
						</td>
						<td class="subject-category-cells">
							<input type="hidden" name="category" class="subject-category-inputs" value=""/>
						</td>
						<td class="name-cells">
							<form>
								<input type="text" class="no-box-inputs" value="<?= $subject['name'] ?>">
							</form>
						</td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="contacts-table-conts" id="reports-table-cont" style="<? if (empty($admin['reports'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">Reports (<span class="counts" id="reports-count"><?= count($admin['reports']) ?></span>)</h2>
			<table class="contact-tables" id="reports-table">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="student-cells">Type</th>
						<th class="admin-message-cells">Report</th>
						<th class="tutor-cells">Content</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($admin['reports'] as $report): 
					?>
					<tr class="report-rows" data-id="<?= $report['id'] ?>">
						<td class="status-cells">
							<span class="ajax-enabled">
								<a href="javascript:void(0);" class="ignore-links status-change-links">Clear</a>
							</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
						</td>
						<td class="type-cells"><a target="_blank" href="<?= $report['link'] ?>"><?= $report['type'] ?></a></td>
						<td class="message-cells"><?= nl2br($report['message']) ?></td>
						<td class="content-cells"><?= nl2br($report['content']) ?></td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="contacts-table-conts" id="tutor-contacts-table-cont" style="<? if (empty($admin['tutor_contacts'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">Tutor Contacts (<span class="counts" id="tutor-contacts-count"><?= count($admin['tutor_contacts']) ?></span>)</h2>
			<table class="contact-tables" id="tutor-contacts-table">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="student-cells">Student</th>
						<th class="tutor-cells">Tutor</th>
						<th class="admin-message-cells">Message</th>
						<th class="contacted-cells">Contacted</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($admin['tutor_contacts'] as $contact): 
				        $contacted = new DateTime($contact['contacted']);
					?>
					<tr class="contact-rows" data-st-id="<?= $contact['st_id'] ?>">
						<td class="status-cells">
							<span class="ajax-enabled">
								<span class="ignore-links status-change-links">Clear</span>
							</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
						</td>
						
						<td class="student-cells"><a target="_blank" href="<?= base_url('students/'.$contact['student_username']) ?>"><?= $contact['student_name'] ?></a> (<?= $contact['student_email'] ?>)</td>
						<td class="tutor-cells"><a target="_blank" href="<?= base_url('tutors/'.$contact['tutor_username']) ?>"><?= $contact['tutor_name'] ?></a> (<?= $contact['tutor_email'] ?>)</td>
						<td class="admin-message-cells"><?= nl2br($contact['message']) ?></td>
						<td class="contacted-cells"><?= $contacted->format('M d, Y (H:i)') ?></td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="contacts-table-conts" id="contacts-table-cont" style="<? if (empty($admin['contacts'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">Contacts (<span class="counts" id="contacts-count"><?= count($admin['contacts']) ?></span>)</h2>
			<table class="contact-tables" id="contacts-table">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="email-cells">Email</th>
						<th class="site-message-cells">Message</th>
						<th class="contacted-cells">Contacted</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($admin['contacts'] as $contact): 
				        $contacted = new DateTime($contact['contacted']);
					?>
					<tr class="contact-rows" data-id="<?= $contact['id'] ?>">
						<td class="status-cells">
							<span class="ajax-enabled">
								<span class="ignore-links status-change-links">Clear</span>
							</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
						</td>
						
						<td class="email-cells"><?= mailto($contact['email']) ?></td>
						<td class="site-message-cells"><?= nl2br($contact['message']) ?></td>
						<td class="contacted-cells"><?= $contacted->format('M d, Y (H:i)') ?></td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>

		<div class="contacts-table-conts" id="data-table-cont" style="<? if (empty($admin['data'])) echo ' display: none; '; ?>">
			<h2 class="table-headings">Pending Data (<span class="counts" id="data-count"><?= count($admin['data']) ?></span>)</h2>
			<table class="contact-tables" id="data-table">
				<thead>
					<tr>
						<th class="status-cells"></th>
						<th class="name-cells">Name</th>
						<th class="group-cells">Group</th>
					</tr>
				</thead>
				<tbody>
					<? foreach ($admin['data'] as $data): 
					?>
					<tr class="contact-rows" data-id="<?= $data['id'] ?>" data-table="<?= $data['table'] ?>">
						<td class="status-cells">
							<span class="ajax-enabled">
								<a href="javascript:void(0);" class="ignore-links status-change-links">Delete</a>
								<? if ($data['table'] == 'locations'): ?>
								| <a href="javascript:void(0);" class="hide-item-links status-change-links">Hide</a>
								<? endif; ?>
							</span> <img src="<?= base_url('assets/images/'.LOADER_LIGHT) ?>" class="ajax-loaders">
						</td>
						<td class="name-cells">
							<form>
								<input data-autocomplete-source="<?= $data['table'] ?>" type="text" class="no-box-inputs autocompleted" value="<?= $data['name'] ?>">
								<? if ($data['table'] == 'locations'): ?>
									<a class="map-links" href="https://maps.google.com/?q=<?= $data['name'] ?>" target="_blank">Map</a>
								<? endif; ?>
							</form>
						</td>
						<td class="group-cells"><?= ucfirst($data['table']) ?></td>
					</tr>
					<? endforeach; ?>
				</tbody>
			</table>
		</div>

	</div>
<pre class="cf">
<? var_export($this->session->all_userdata()); ?>
</pre>
</section>

<script>

$(function()
{
	$('#ut-url-check-form').submit(function()
	{
		var $form = $(this),
			$loader = $('#url-check-loader').fadeIn();

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/url_check') ?>",
			data: $form.serialize(),
			dataType: 'json'
		}).done(function(response)
		{
			$form.validate(response.errors);

			if (response.success == true)
			{
				var notyOptions = {
					timeout: 1800,
					type: 'success',
					text: '<b>URL Saved!</b>'
				};
				noty(notyOptions);

				$form[0].reset();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			ajaxFailNoty();
		});

		return false;
	});

	$('#new-imported-tutor-form').submit(function()
	{
		var $form = $(this),
			$loader = $('#import-tutor-loader').fadeIn();

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/make_imported_tutor_account') ?>",
			data: $form.serialize(),
			dataType: 'json'
		}).done(function(response) 
		{
			console.log(response);

			if (response.success == true)
			{
				$loader.fadeIn();

				$.ajax(
				{
					type: "POST",
					url: "<?= base_url('import') ?>",
					dataType: 'json'
				}).done(function(response)
				{
					console.log(response);

					if (response.success == true)
					{
						var notyOptions = {
							timeout: 1800,
							type: 'success',
							text: '<b>Tutor Imported!</b>'
						};
						noty(notyOptions);

						$('#imported-tutor-url').val(baseUrl('tutors/'+response.data.username));
						$form[0].reset();
					}

					if (response.status == <?= STATUS_UNKNOWN_ERROR ?>)
					{
						ajaxFailNoty();
					}
				}).always(function() 
				{
					$loader.hide();
				}).fail(function() 
				{
					ajaxFailNoty();
				});
			}
			else
			{
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();

		}).fail(function()
		{
			ajaxFailNoty();
		});

		return false;
	});

	$('.subject-category-inputs').select2({
		tags: <?= json_encode($subject_categories) ?>,
		tokenSeparators: [","],
		openOnEnter: false,
		maximumSelectionSize: 1,
		formatSelectionTooBig: function (limit) { return "Sorry, only 1 category per subject"; }
	});

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

	$('.status-change-links', '.sharables-conts').click(function()
	{
		var $link = $(this),
			$row = $link.parents('tr'),
			$cont = $row.parents('.sharables-conts'),
			id = $row.attr('data-id'),
			type = $row.attr('data-type'),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')
							   .removeClass('ajax-enabled')
							   .addClass('ajax-disabled'),
			action,
			data = 
			{
				id: id,
				type: type,
				city: $row.attr('data-city'),
				country: $row.attr('data-country')
			};

		if ($link.text() == 'Share')
		{
			action = 1;
		}
		else
		{
			action = 0;
		}

		data['action'] = action;

		if (type == 'tutor')
		{
			data['username'] = $row.attr('data-username');
			data['name'] = $row.attr('data-name');
		}
		else
		{
			data['subject'] = $row.attr('data-subject');			
		}

		log(data);

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/share') ?>",
			data: data,
			dataType: 'json'
		}).done(function(response) 
		{
			// console.log(response);

			if (response.success == true)
			{
				var $count = $cont.find('.counts'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$cont.fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});
	});

	$('.ignore-links', '#reports-table-cont').click(function()
	{
		var $row = $(this).parents('tr'),
			rowId = $row.attr('data-id'),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')
							   .removeClass('ajax-enabled')
							   .addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/delete_report') ?>",
			data: 
			{
				'row-id' : rowId
			},
			dataType: 'json'
		}).done(function(response) 
		{
			console.log(response);

			if (response.success == true)
			{
				var $count = $('#reports-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#reports-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});
	});

	$('.ignore-links', '#contacts-table-cont').click(function()
	{
		var $row = $(this).parents('tr'),
			rowId = $row.attr('data-id'),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')
							   .removeClass('ajax-enabled')
							   .addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/clear_contact') ?>",
			data: 
			{
				'row-id' : rowId
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// console.log(response);

			if (response.success == true)
			{
				var $count = $('#contacts-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#contacts-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});
	});

	$('.ignore-links', '#tutor-contacts-table-cont').click(function()
	{
		var $row = $(this).parents('tr'),
			stId = $row.attr('data-st-id'),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')
							   .removeClass('ajax-enabled')
							   .addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/clear_tutor_contact') ?>",
			data: 
			{
				'st-id' : stId
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// console.log(response);

			if (response.success == true)
			{
				var $count = $('#tutor-contacts-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#tutor-contacts-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});
	});

	$('.accept-links', '#tutor-contacts-table-cont').click(function()
	{
		var $row = $(this).parents('tr'),
			stId = $row.attr('data-st-id'),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')
							   .removeClass('ajax-enabled')
							   .addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/approve_student') ?>",
			data: 
			{
				'st-id' : stId
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// console.log(response);

			if (response.success == true)
			{
				var $count = $('#tutor-contacts-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#tutor-contacts-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});
	});

	$('.map-links', '#data-table-cont').click(function()
	{
		var $this = $(this),
			$row = $this.parents('tr'),
			value = $row.find('.name-cells form input').val();

		if (!value)
			return false;

		$this.attr('href', "https://maps.google.com/?q=" + value);
	});

	$('form', '#data-table-cont').submit(function()
	{
		var $row = $(this).parents('tr'),
			itemId = $row.attr('data-id'),
			table = $row.attr('data-table'),
			value = $row.find('.name-cells form input').val(),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')

		if (!value)
			return false;

		$ajaxButtons.removeClass('ajax-enabled').addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/approve_data_item') ?>",
			data: 
			{
				'item-id' : itemId,
				'table' : table,
				'value' : value
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// console.log(response);

			if (response.success == true)
			{
				var $count = $('#data-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#data-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});

		$row.next('tr').find('.name-cells form input').focus();

		return false;
	});

	$('#data-table-cont .ignore-links, #data-table-cont .hide-item-links').click(function()
	{
		var $this = $(this),
			type = $this.hasClass('hide-item-links') ? 'hide' : 'delete',
			url = baseUrl('admin/'+type+'_data_item'),
			$row = $this.parents('tr'),
			itemId = $row.attr('data-id'),
			table = $row.attr('data-table'),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')
							   .removeClass('ajax-enabled')
							   .addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: url,
			data: 
			{
				'item-id' : itemId,
				'table' : table
			},
			dataType: 'json'
		}).done(function(response) 
		{
			// console.log(response);

			if (response.success == true)
			{
				var $count = $('#data-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#data-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});
	});

	$('form', '#subject-table-cont').submit(function()
	{
		var $row = $(this).parents('tr'),
			subjectId = $row.attr('data-id'),
			value = $row.find('.name-cells form input').val(),
			category = $row.find('.subject-category-cells [name=category]').val(),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')

		if (!value || !category)
			return false;

		$ajaxButtons.removeClass('ajax-enabled').addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/approve_subject') ?>",
			data: 
			{
				'subject-id' : subjectId,
				'value' : value,
				'category' : category
			},
			dataType: 'json'
		}).done(function(response) 
		{
//			console.log(response);

			if (response.success == true)
			{
				var $count = $('#subject-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#subject-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});

		$row.next('tr').find('.name-cells form input').focus();

		return false;
	});

	$('.ignore-links', '#subject-table-cont').click(function()
	{
		var $row = $(this).parents('tr'),
			subjectId = $row.attr('data-id'),
			$loader = $row.find('.status-cells .ajax-loaders'),
			$ajaxButtons = $row.find('.status-cells .ajax-enabled')
							   .removeClass('ajax-enabled')
							   .addClass('ajax-disabled');

		$loader.fadeIn(<?= FAST_FADE_SPEED ?>);

		$.ajax(
		{
			type: "POST",
			url: "<?= base_url('admin/reject_subject') ?>",
			data: 
			{
				'subject-id' : subjectId
			},
			dataType: 'json'
		}).done(function(response) 
		{
			 console.log(response);

			if (response.success == true)
			{
				var $count = $('#subject-count'),
					count = +$count.text() - 1;

				if (count == 0)
				{
					$('#subject-table-cont').fadeOut();
				}
				else
				{
					$count.text(count);
				}

				$row.fadeOut(<?= FAST_FADE_SPEED ?>);
			}
			else
			{
				$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
				ajaxFailNoty();
			}
		}).always(function()
		{
			$loader.hide();
		}).fail(function()
		{
			$ajaxButtons.removeClass('ajax-disabled').addClass('ajax-enabled');
			ajaxFailNoty();
		});
	});
});

</script>