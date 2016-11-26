<?
return;

	// This is for show_404()

	$ci =& get_instance();
    $data['meta'] = $ci->config->item('error-php');

	$ci->load->page('errors/php', $data);
?>