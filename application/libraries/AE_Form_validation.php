<?

class AE_Form_validation extends CI_Form_validation 
{
    public function __construct()
    {
        parent::__construct();
        $this->_error_prefix    = '<div class="error-messages">';
        $this->_error_suffix    = '</div>';
    }

    /**
     * Error Array
     *
     * Returns the error messages as an array
     *
     * @return  array
     */
    function error_array()
    {
        if (count($this->_error_array) === 0)
        {
                return FALSE;
        }
        else
            return $this->_error_array;
    }

    function make_error($error_text)
    {
        return $this->_error_prefix . $error_text . $this->_error_suffix;
    }

    function invalid_response($data = null)
    {
        return $this->response(STATUS_VALIDATION_ERROR, $data);
    }

    function response($status = STATUS_OK, $data = null)
    {
        // These are common, so just override them if appropriate status; this also takes case of the general database/unknown errors
        $response = array(
            'errors' => null,
            'status' => $status,
            'success' => FALSE,
            'data' => $data
        );

        if ($status == STATUS_VALIDATION_ERROR)
        {
            $response['errors'] = $this->error_array();

            // Check for custom errors
            if (isset($data, $data['errors']))
            {
                if ($response['errors'])
                {
                    $response['errors'] = array_merge($data['errors'], $response['errors']);
                }
                else
                {
                    $response['errors'] = $data['errors'];
                }
            }
        }  
        elseif ($status == STATUS_OK || $status == STATUS_NOTHING_HAPPENED)    // OK or NOTHING_HAPPENED
        {
            $response['success'] = TRUE;
        }

        return $response;
    }


    function is_money($input, $params) 
    {           
        @list($thousand, $decimal, $message) = explode(',', $params);
        $thousand = (empty($thousand) || $thousand === 'COMMA') ? ',' : '.';
        $decimal = (empty($decimal) || $decimal === 'DOT') ? '.' : ',';
        $message = (empty($message)) ? "Sorry, %s doesn't have a correct currency format (e.g. 1.00, $5, 17)" : $message;

        $regExp = "/^\s*[$]?\s*((\d+)|(\d{1,3}(\{thousand}\d{3})+)|(\d{1,3}(\{thousand}\d{3})(\{decimal}\d{3})+))(\{decimal}\d{2})?\s*$/";
        $regExp = str_replace("{thousand}", $thousand, $regExp);
        $regExp = str_replace("{decimal}", $decimal, $regExp);

        $ok = preg_match($regExp, $input);
        if(!$ok) {
            $CI =& get_instance();
            $CI->form_validation->set_message('is_money', $message);
            return FALSE;
        }
        return TRUE;
    }

    function lt($input, $max)
    {
        if ($input >= $max)
        {
            $this->set_message('lt','%s must be less than '.$max);
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function lte($input, $max)
    {
        if ($input > $max)
        {
            $this->set_message('lte','%s must be less than or equal to '.$max);
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function gt($input, $max)
    {
        if ($input <= $max)
        {
            $this->set_message('gt','%s must be greater than than '.$max);
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }


    function gte($input, $param)
    {
        $param = preg_split('/,/', $param);

        $max = $param[0];

        if (isset($param[1]))
            $compared_name = $param[1];
        else
            $compared_name = null;

        if ($input < $max)
        {
            if ($compared_name)
                $this->set_message('gte','%s can\'t be less than '.$compared_name);
            else
                $this->set_message('gte','%s can\'t be less than '.$max);
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function isnt($str,$forbidden)
    {
        $this->set_message('isnt', "%s can't be '$forbidden'");
        return $str !== $forbidden;
    }

    function not_spam($post)
    {
        if (empty($post['as_e']) && empty($post['as_h']) && $post['as_f'] == ANTI_SPAM_FILLED_TEXT)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    function valid_url($str)
    {
        /*
            Regex explained:
                - 1
                    - ^([a-z\d](-*[a-z\d])*)            Subdomain, allows for dashes, a-z and #s
                    - (\.([a-z\d](-*[a-z\d])*))*$       Domain and extension chars, dashes, a-z, and #s
                - 2
                    - ^.{1,253}$                        Less than 254 chars
                -3
                    - ^[^\.]{1,63}(\.[^\.]{1,63})+$     <64 chars followed by (. followed by <64 chars)+, 
                                                        Change last + to * to allow for no-extension domains (e.g .localhost)
        */

        $str = convert_accented_characters($str);

        $valid_domain = preg_match("/^([\w%](-*[\w%])*)(\.([\w%](-*['\w%])*))*$/i", $str) //valid chars check
            && preg_match("/^.{1,253}$/", $str) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})+$/", $str);

        $valid_url = filter_var($str, FILTER_VALIDATE_URL);

/*
        var_dump(preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $str),
                 preg_match("/^.{1,253}$/", $str),
                 preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})+$/", $str));
*/

        if ($valid_domain || $valid_url)
        {
            return TRUE;
        }
        else
        {
            $this->set_message('valid_url',"%s isn't a valid web address (e.g. myexample.com)");

            // Add protocol, then check if valid domain. This is for multi-subdomain entries (e.g. www.example.com vs example.com)
            $url = 'http://'.$str;
            $url_components = parse_url($url);
            $host = $url_components['host'];
            $ext = pathinfo($host, PATHINFO_EXTENSION);

            if (!$ext)
            {
                return FALSE;
            }

            if (filter_var($url, FILTER_VALIDATE_URL))
            {
                return TRUE;            
            }

            return FALSE;
        }
    }
}

?>