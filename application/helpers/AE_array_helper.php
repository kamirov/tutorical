<?  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/* Case-insensitive array_unique */

if ( ! function_exists('array_iunique'))
{
	function array_iunique($array) {
	    return array_intersect_key($array,array_unique(
	                 array_map('strtolower',$array)));
	}
}

/* Case-insensitive in_array */

if ( ! function_exists('in_arrayi'))
{
	function in_arrayi($needle, $haystack) {
	    return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}

}

/* Case-insensitive array_search */

if ( ! function_exists('array_searchi'))
{
	function array_searchi($needle, $haystack) {
		return  array_search(strtolower($needle), array_map('strtolower', $haystack));
	}
}

/* Get all nth-position elements in all subarrays of a 2D array */

if ( ! function_exists('combine_subarrays')) 
{
	function combine_subarrays($arr, $n = 0) {
		$combined = array();

		foreach($arr as $sub) {
			array_push($combined, $sub[$n]);
		}

		return $combined;
	}
}

/* Convert 2D array of 0 => key => value, etc. to 1D assoc key => value array */

if ( ! function_exists('key_values_to_assoc')) 
{
	function key_values_to_assoc($arr) 
	{
		$assoc = array();

		foreach($arr as $key_value_arr) 
		{
			$assoc[$key_value_arr['key']] = $key_value_arr['value'];
		}

		return $assoc;
	}
}


/* Same as slice, but chops off slice from original array */

if ( ! function_exists('array_chop')) 
{
	function array_chop(&$arr, $num)
	{
	    $ret = array_slice($arr, 0, $num);
	    $arr = array_slice($arr, $num);
	    return $ret;
	}
}


/* Clean string and explode */

if ( ! function_exists('clean_string_and_explode')) 
{
	function clean_string_and_explode($str) {

		$arr = explode(',', $str);

		// Remove new lines and returns.
		$arr = preg_replace('/(\n|\r|\t)/', '', $arr);

		//Remove dangerous '\' char. This needs to be put as a specialized aspect in case '\' are actually needed
		$arr = str_replace('\\', '', $arr);

		// Trim each el
		$arr = array_map('trim', $arr);

		// Remove empty els
		$arr = array_filter($arr);

		// Remove duplicates
		$arr = array_iunique($arr);

		return $arr;
	}
}

/* End of file AE_array_helper.php */
/* Location: ./application/helpers/AE_array_helper.php */