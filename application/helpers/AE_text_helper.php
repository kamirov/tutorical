<?  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('title_case'))
{
      function title_case ($title) {
            //remove HTML, storing it for later
            //       HTML elements to ignore    | tags  | entities
            $regx = '/<(code|var)[^>]*>.*?<\/\1>|<[^>]+>|&\S+;/';
            preg_match_all ($regx, $title, $html, PREG_OFFSET_CAPTURE);
            $title = preg_replace ($regx, '', $title);
            
            //find each word (including punctuation attached)
            preg_match_all ('/[\w\p{L}&`\'‘’"“\.@:\/\{\(\[<>_]+-? */u', $title, $m1, PREG_OFFSET_CAPTURE);
            foreach ($m1[0] as &$m2) {
                  //shorthand these- "match" and "index"
                  list ($m, $i) = $m2;
                  
                  //correct offsets for multi-byte characters (`PREG_OFFSET_CAPTURE` returns *byte*-offset)
                  //we fix this by recounting the text before the offset using multi-byte aware `strlen`
                  $i = mb_strlen (substr ($title, 0, $i), 'UTF-8');
                  
                  //find words that should always be lowercase…
                  //(never on the first word, and never if preceded by a colon)
                  $m = $i>0 && mb_substr ($title, max (0, $i-2), 1, 'UTF-8') !== ':' && 
                        !preg_match ('/[\x{2014}\x{2013}] ?/u', mb_substr ($title, max (0, $i-2), 2, 'UTF-8')) && 
                         preg_match ('/^(a(nd?|s|t)?|b(ut|y)|en|for|i[fn]|o[fnr]|t(he|o)|vs?\.?|via)[ \-]/i', $m)
                  ?     //…and convert them to lowercase
                        mb_strtolower ($m, 'UTF-8')
                        
                  //else:     brackets and other wrappers
                  : (   preg_match ('/[\'"_{(\[‘“]/u', mb_substr ($title, max (0, $i-1), 3, 'UTF-8'))
                  ?     //convert first letter within wrapper to uppercase
                        mb_substr ($m, 0, 1, 'UTF-8').
                        mb_strtoupper (mb_substr ($m, 1, 1, 'UTF-8'), 'UTF-8').
                        mb_substr ($m, 2, mb_strlen ($m, 'UTF-8')-2, 'UTF-8')
                        
                  //else:     do not uppercase these cases
                  : (   preg_match ('/[\])}]/', mb_substr ($title, max (0, $i-1), 3, 'UTF-8')) ||
                        preg_match ('/[A-Z]+|&|\w+[._]\w+/u', mb_substr ($m, 1, mb_strlen ($m, 'UTF-8')-1, 'UTF-8'))
                  ?     $m
                        //if all else fails, then no more fringe-cases; uppercase the word
                  :     mb_strtoupper (mb_substr ($m, 0, 1, 'UTF-8'), 'UTF-8').
                        mb_substr ($m, 1, mb_strlen ($m, 'UTF-8'), 'UTF-8')
                  ));
                  
                  //resplice the title with the change (`substr_replace` is not multi-byte aware)
                  $title = mb_substr ($title, 0, $i, 'UTF-8').$m.
                         mb_substr ($title, $i+mb_strlen ($m, 'UTF-8'), mb_strlen ($title, 'UTF-8'), 'UTF-8')
                  ;
            }
            
            //restore the HTML
            foreach ($html[0] as &$tag) $title = substr_replace ($title, $tag[0], $tag[1], 0);
            return $title;
      }
}

if(!function_exists('transliterate'))
{
	function transliterate($string)
	{
      //Cyrylic transliteration
      $cyrylic_from = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
      $cyrylic_to   = array('A', 'B', 'v', 'G', 'D', 'E', 'Yo', 'Zh', 'Z', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'x', 'C', 'Ch', 'Sh', 'Shh', '', 'Y', '', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'yo', 'zh', 'z', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'ch', 'c', 'ch', 'sh', 'shh', '', 'y', '', 'e', 'yu', 'ya'); 
 
      
      $from = array("Á", "À", "Â", "Ä", "Ă", "Ā", "Ã", "Å", "Ą", "Æ", "Ć", "Ċ", "Ĉ", "Č", "Ç", "Ď", "Đ", "Ð", "É", "È", "Ė", "Ê", "Ë", "Ě", "Ē", "Ę", "Ə", "Ġ", "Ĝ", "Ğ", "Ģ", "á", "à", "â", "ä", "ă", "ā", "ã", "å", "ą", "æ", "ć", "ċ", "ĉ", "č", "ç", "ď", "đ", "ð", "é", "è", "ė", "ê", "ë", "ě", "ē", "ę", "ə", "ġ", "ĝ", "ğ", "ģ", "Ĥ", "Ħ", "I", "Í", "Ì", "İ", "Î", "Ï", "Ī", "Į", "Ĳ", "Ĵ", "Ķ", "Ļ", "Ł", "Ń", "Ň", "Ñ", "Ņ", "Ó", "Ò", "Ô", "Ö", "Õ", "Ő", "Ø", "Ơ", "Œ", "ĥ", "ħ", "ı", "í", "ì", "i", "î", "ï", "ī", "į", "ĳ", "ĵ", "ķ", "ļ", "ł", "ń", "ň", "ñ", "ņ", "ó", "ò", "ô", "ö", "õ", "ő", "ø", "ơ", "œ", "Ŕ", "Ř", "Ś", "Ŝ", "Š", "Ş", "Ť", "Ţ", "Þ", "Ú", "Ù", "Û", "Ü", "Ŭ", "Ū", "Ů", "Ų", "Ű", "Ư", "Ŵ", "Ý", "Ŷ", "Ÿ", "Ź", "Ż", "Ž", "ŕ", "ř", "ś", "ŝ", "š", "ş", "ß", "ť", "ţ", "þ", "ú", "ù", "û", "ü", "ŭ", "ū", "ů", "ų", "ű", "ư", "ŵ", "ý", "ŷ", "ÿ", "ź", "ż", "ž");
      $to   = array("A", "A", "A", "A", "A", "A", "A", "A", "A", "AE", "C", "C", "C", "C", "C", "D", "D", "D", "E", "E", "E", "E", "E", "E", "E", "E", "G", "G", "G", "G", "G", "a", "a", "a", "a", "a", "a", "a", "a", "a", "ae", "c", "c", "c", "c", "c", "d", "d", "d", "e", "e", "e", "e", "e", "e", "e", "e", "g", "g", "g", "g", "g", "H", "H", "I", "I", "I", "I", "I", "I", "I", "I", "IJ", "J", "K", "L", "L", "N", "N", "N", "N", "O", "O", "O", "O", "O", "O", "O", "O", "CE", "h", "h", "i", "i", "i", "i", "i", "i", "i", "i", "ij", "j", "k", "l", "l", "n", "n", "n", "n", "o", "o", "o", "o", "o", "o", "o", "o", "o", "R", "R", "S", "S", "S", "S", "T", "T", "T", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "W", "Y", "Y", "Y", "Z", "Z", "Z", "r", "r", "s", "s", "s", "s", "B", "t", "t", "b", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "w", "y", "y", "y", "z", "z", "z");
      
      
      $from = array_merge($from, $cyrylic_from);
      $to   = array_merge($to, $cyrylic_to);
      
      $transliterated = str_replace($from, $to, $string);   
      return $transliterated;
	}
}
if (!function_exists('time_elapsed_string'))
{
      function time_elapsed_string($ptime, $after_text = '') {
          $etime = time() - $ptime;
          
          if ($etime < 1) {
            if ($after_text == ' ago')
              return 'just now';
            return '0 seconds'.$after_text;
          }
          
          $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                      30 * 24 * 60 * 60       =>  'month',
                      24 * 60 * 60            =>  'day',
                      60 * 60                 =>  'hour',
                      60                      =>  'minute',
                      1                       =>  'second'
                      );
          
          foreach ($a as $secs => $str) {
              $d = $etime / $secs;
              if ($d >= 1) {
                  $r = round($d);
                  return $r . ' ' . $str . ($r > 1 ? 's' : '').$after_text;
              }
          }
      }      
}

if (!function_exists('ellipsis_text'))
{
  function ellipsis_text($string, $length, $stopanywhere = FALSE) 
  {
    //truncates a string to a certain char length, stopping on a word if not specified otherwise.
    if (strlen($string) > $length) 
    {
        //limit hit!
        $string = substr($string,0,($length -3));
        if ($stopanywhere) 
        {
            //stop anywhere
            $string .= '...';
        } 
        else
        {
            //stop on a word.
            $string = substr($string,0,strrpos($string,' ')).'...';
        }
    }
    return $string;
  }
}

if (!function_exists('get_currency_sign'))
{
  function get_currency_sign($code)
  {
    $currency_and_codes = array(
      '$' => array('USD', 'CAD', 'AUD', 'MXN'),
      '&pound;' => 'GBP',
      '&euro;' => 'EUR',
      '&#8377;' => 'INR',
      '&yen;' => array('CNY', 'JPY')
    );

    foreach($currency_and_codes as $currency => $codes)
    {
      if ((is_array($codes) && in_array($code, $codes)) || $codes == $code)
        return $currency;
    }
    return '';

  }
}

if (!function_exists('remove_tabs_and_new_lines'))
{
  function remove_tabs_and_new_lines($text)
  {
//    return htmlspecialchars($text);
    return preg_replace('/\s+/', ' ', $text);
  }
}


/* End of file text_helper.php */
/* Location: ./system/helpers/text_helper.php */