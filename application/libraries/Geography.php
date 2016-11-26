<? if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Geography
 *
 * Geocoding, for now
 *
 * @version		1.0
 */
class Geography
{

	function __construct()
	{
		$this->ci =& get_instance();
	}

	function geocode($readable_location)
	{
		$encoded_location = str_replace (" ", "+", urlencode($readable_location));
		$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$encoded_location."&sensor=false";
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $details_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, true); // some output will go to stderr / error_log
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

/*
//		Diagnostics:
		$response = curl_exec($ch);
		$errStr = curl_error($ch);
		$errNum = curl_errno($ch);
		$head = curl_getinfo($ch, CURLINFO_HEADER_OUT);
		$ci = curl_getinfo($ch);
		var_dump(array($response, $head, $errStr, $errNum, $ci));
*/

		$response = json_decode(curl_exec($ch), true);

		// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
		if ($response['status'] != 'OK') 
		{
 			$array = array(
			    'status' => $response['status']
			);
			return $array;
		}

		$geometry = $response['results'][0]['geometry'];

		$array = array(
			'lat' => $geometry['location']['lat'],
		    'lon' => $geometry['location']['lng'],
		    'readable' => $readable_location,
		    'status' => $response['status']
		);

		return $array;
	}
	
}

/* End of file Geography.php */
/* Location: ./application/libraries/Geography.php */