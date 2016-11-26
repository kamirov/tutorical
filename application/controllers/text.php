<? if (!defined('BASEPATH')) exit('No direct script access allowed');

class Text extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form'));
		$this->load->library(array('security', 'form_validation'));
		$this->load->model('text_model');
	}

	function index()
	{
		$segments = $this->uri->segment_array();
		$count = count($segments);
		$parent_id = 0;

		$breadcrumbs = '';

		for ($i = 1; $i < $count+1; $i++)
		{
			$page = $this->text_model->get_page(array('slug' => $segments[$i], 'parent_id' => $parent_id));

			// If $page is empty, then something in the URL is wrong

			if (empty($page))
			{
				show_404();
				return;
			}

			$parent_id = $page['id'];

			if ($i != $count)
				$breadcrumbs .= anchor('', $page['title']).' » ';
		}

		$data['breadcrumbs'] = substr($breadcrumbs, 0, -3);		// -3 gets rid of final raquo

        $data['meta'] = $this->config->item('text-page');
        $data['meta']['title'] = str_replace('{TITLE}', $page['title'], $data['meta']['title']);
        $data['meta']['description'] = $page['description'];

		$data['text_page'] = $page;
		$this->load->page('text/regular', $data);
	}

}

/* End of file text.php */
/* Location: ./application/controllers/text.php */