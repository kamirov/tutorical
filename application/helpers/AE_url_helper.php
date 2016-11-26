<?  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('this_url_with_query'))
{
	function this_url_with_query($query = array()) 
	{
		$ci =& get_instance();
//		$url_with_query = $ci->uri->uri_string($segments);
	
		if ($ci->input->get())
		{
			parse_str($_SERVER['QUERY_STRING'], $existing_query);
			$result_query = $query + $existing_query;
			$url_with_query = $ci->uri->uri_string().'?'.http_build_query($result_query);
		}
		elseif ($query)
		{
			$url_with_query = $ci->uri->uri_string().'?'.http_build_query($query);
		}
		else
		{
			$url_with_query = $ci->uri->uri_string();			
		}

		return $url_with_query;
	}
}


if (!function_exists('ae_urlencode'))
{
	// URL encodes a string. Handles servers that don't allow slashes in URLs
	function ae_urlencode($str)
	{
		$str = urlencode($str);
		$str = str_replace('%2F', '[bs]', $str);

		return $str;
	}
}


if (!function_exists('ae_urldecode'))
{
	// URL decodes a string. Handles servers that don't allow slashes in URLs
	function ae_urldecode($str)
	{
		// We need to look for the URL entities. Decoding HTML chars doesn't work...for some reason
		$str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
		$str = str_replace('[bs]', '/', $str);
		$str = urldecode($str);

		return $str;
	}
}