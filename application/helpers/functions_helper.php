<?php

/**
* 
* Get time difference between a date and current date
*
* @param string $date Date to convert 
* @return string
*
*/
function get_time_difference($date) 
{
    if(empty($date)) {
        return "No date provided";
    }
    $periods         = array("seconds", "minutes", "hours", "days", "weeks", "months", "years", "decades");
    $lengths         = array("60","60","24","7","4.35","12","10");
    $now             = strtotime("-0 minutes");
    $unix_date       = strtotime($date);

    if(empty($unix_date)) {   
        return "Bad date";
    }

    if($now > $unix_date) {   
        $difference     = $now - $unix_date;
        $tense = "ago";

    } else {
        $difference     = $unix_date - $now;
        $tense = "in";
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    return (($now > $unix_date) ? ("$difference $periods[$j] {$tense}") : ("{$tense} $difference $periods[$j]"));
}

/**
* 
* Get time difference between a timestamp and current date
*
* @param integer $date Timestamp to difference 
* @return string
*
*/
function get_time_difference_timestamp($date) 
{
    if(empty($date)) {
        return "No date provided";
    }
    $periods         = array("seconds", "minutes", "hours", "days", "weeks", "months", "years", "decades");
    $lengths         = array("60","60","24","7","4.35","12","10");
    $now             = strtotime("-0 minutes");
    $unix_date       = ($date);

    if(empty($unix_date)) {   
        return "Bad date";
    }

    if($now > $unix_date) {   
        $difference     = $now - $unix_date;

    } else {
        $difference     = $unix_date - $now;
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    return "$difference $periods[$j]";
}

function get_info($selector, $table, $firstkey, $key) {
    $ci =& get_instance();
    $query = $ci->db->query("SELECT `".$selector."` FROM `".$table."` WHERE `".$firstkey."` = '".$key."' ");
    return (isset($query->result()[0]->$selector) ? $query->result()[0]->$selector: "unknown");
}

function get_cached_info($selector, $table, $firstkey, $key) {
    $ci =& get_instance();
    $ci->db->cache_on();
    $query = $ci->db->query("SELECT `".$selector."` FROM `".$table."` WHERE `".$firstkey."` = '".$key."' ");
    return ((isset($query->result()[0]->$selector)) ? $query->result()[0]->$selector : "unknown");
}

function get_cached_info_null($selector, $table, $firstkey, $key) {
    $ci =& get_instance();
    $ci->db->cache_on();
    $query = $ci->db->query("SELECT `".$selector."` FROM `".$table."` WHERE `".$firstkey."` = '".$key."' ");
    return $query->result()[0]->$selector;
}


function countTable($table, $extra = "") {
    $ci =& get_instance();
    $query = $ci->db->query("SELECT null FROM `" . $table . "` " . $extra);
    return $query->num_rows();
}

function base64Encoded($data)
{
    if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
        return TRUE;
    } else {
        return FALSE;
    }
};


function most_frequent_words($string, $stop_words = [], $limit = 5) { // stackoverflow
    $string = strtolower($string); // Make string lowercase

    $words = str_word_count($string, 1); // Returns an array containing all the words found inside the string
    $words = array_diff($words, $stop_words); // Remove black-list words from the array
    $words = array_count_values($words); // Count the number of occurrence

    arsort($words); // Sort based on count

    return array_slice($words, 0, $limit); // Limit the number of words and returns the word array
}

/* Sets a flash message then redirects */
function flash_redirect($type, $message, $where) {
    $CI =& get_instance();
    $CI->session->set_flashdata($type, $message);
    redirect($where);
}

function delete_all_between($beginning, $end, $string) {
    $beginningPos = strpos($string, $beginning);
    $endPos = strpos($string, $end);
    if (!$beginningPos || !$endPos) {
        return $string;
    }

    $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

    return str_replace($textToDelete, '', $string);
}

function get_string_between($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}


function isAbsolutePath($file)
{
    return strspn($file, '/\\', 0, 1)
        || (strlen($file) > 3 && ctype_alpha($file[0])
            && substr($file, 1, 1) === ':'
            && strspn($file, '/\\', 2, 1)
           )
        || null !== parse_url($file, PHP_URL_SCHEME)
        ;
}

function get_data($url) {
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

/**
* 
* Check if something exists
*
* @param integer  $refID                Reference ID in table
* @param string   $table                Provided table
* @param string   $identificatior       Identificator 
* @return boolean
*
*/
function exists($refID, $table, $identificator = "`ID`") {
    $ci =& get_instance();
    $query = $ci->db->query("SELECT $identificator FROM `".$table."` WHERE $identificator = '".$refID."' LIMIT 1");
    return ($query->num_rows() ? true : false);
}

/**
* 
* Get location of an IP by IPStack.com Free API
*
* @param string   $IP               IP to provide
* @param boolean  $useHead          Use headers?
* @return string
*
*/
function get_ip_location($IP) {
    $APIKEY     = "189dadc9f0e4de1b77feaf12215cf5e9";
    $info       = json_decode(get_data("http://api.ipstack.com/$IP?access_key=$APIKEY&format=1"));
    return $info->city . ", " . $info->region_name . ", " . $info->country_name;
}

/**
* 
* Format a size (number)
*
* @param integer  $number           Number to be formatted
* @return string
*
*/
function format_size($number) {
    switch ($number) {
        case $number < 1024:
            $size = $number .' B'; break;
        case $number < 1048576:
            $size = round($number / 1024, 2) .' KiB'; break;
        case $number < 1073741824:
            $size = round($number / 1048576, 2) . ' MiB'; break;
        case $number < 1099511627776:
            $size = round($number / 1073741824, 2) . ' GiB'; break;
        default:
            $size = $number;
    }
    return $size;
}

/**
* 
* Get size of a resource file
*
* @param string   $url              URL to check
* @param boolean  $formatSize       Should return the number of the formatted number?
* @param boolean  $useHead          Use headers?
* @return string
*
*/
function get_remote_file_size($url, $formatSize = true, $useHead = true)
{
    if (false !== $useHead) {
        stream_context_set_default(array('http' => array('method' => 'HEAD')));
    }
    $headers = @get_headers($url, 1);
    if($headers) {
        $head = array_change_key_case($headers);

        $clen = isset($head['content-length']) ? $head['content-length'] : 0;

        if (!$clen) {
            return 0;
        }

        if ($formatSize == false) {
            return $clen; 
        }

        $size = $clen;
        switch ($clen) {
            case $clen < 1024:
                $size = $clen .' B'; break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) .' KiB'; break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB'; break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB'; break;
        }

        return $size;
    } else return 0;
}

/**
* 
* Get domain root
*
* @param string  $url               URL to check
* @return string
*
*/
function get_domain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}
/**/


/**
* 
* Get HTTP Code of a website
*
* @param string  $url  URL to check
* @return string
*
*/
function get_http_code($url) 
{
    @$headers = get_headers($url);
    if($headers) return substr($headers[0], 9, 3);
    else return "error";
}

/**
* 
* Check if URL is alive
*
* @param string $url URL to check
* @return string
*
*/
function check_if_alive($url) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch , CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($ch);
    $headers = curl_getinfo($ch);
    curl_close($ch);

    if(in_array($headers['http_code'], array("200", "301", "302"))) return true;
    else return false;
}

/**
* 
* Transform an array into valid POST arguments 
*
* @param  array  $array
* @return string
*
*/
function transform_array_into_argv($array) {
    $argument_id    = 0;
    $final_string   = "";

    foreach($array as $argument) { 
        $key = array_keys($array);
        $val = array_values($array);
        $final_string = $key[$argument_id]."=".$val[$argument_id]."&";
        $argument_id++;
    }

    return rtrim($final_string, '&');
}

/**
* 
* Check if user currently uses a page to show in the dashboard's sidebar
*
* @param  array  $array
* @return string
*
*/
function is_menu_active($menu, $relative = "shouldbeerror") {
    $ci =& get_instance();
    if(strpos($ci->uri->segment(2), $menu) !== false) echo "class='modern-shadow is-active'";
    elseif(strpos($ci->uri->segment(2), $relative) !== false) echo "class='modern-shadow is-active'";
}

/**
*
* Return topic news about your business
*
* @param string $referer
* @return array
*/
function get_topic($referer = '') {
    (!strlen($referer)) ? $ci->config->item('site_name') : $referer;
}


/**
*
* Just a simple curl, dude
*
* @param string $url
* @param array $arguments
* @return array
*/
function curl($type, $url, $arguments = "") {
    if($type == "post") {
        if($arguments) {
            foreach($arguments as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            rtrim($fields_string, '&');
        }

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL, $url);
        if($arguments) {
            curl_setopt($ch,CURLOPT_POST, count($arguments));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        }
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;

    } elseif ($type == "get") {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: text/xml"
        ]);

        $data = curl_exec($ch);

        curl_close($ch);

        echo $data;
    }
}

/**
*
* Get final destination of redirects
*
* @param string $url
* @param array $arguments
* @return array
*/
function get_final_destination($url, $maxRequests = 10)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);

    //customize user agent if you desire...
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Link Checker)');

    while ($maxRequests--) {

        //fetch
        curl_setopt($ch, CURLOPT_URL, $url);
        $response = curl_exec($ch);

        //try to determine redirection url
        $location = '';
        if (in_array(curl_getinfo($ch, CURLINFO_HTTP_CODE), [301, 302, 303, 307, 308])) {
            if (preg_match('/Location:(.*)/i', $response, $match)) {
                $location = trim($match[1]);
            }
        }

        if (empty($location)) {
            //we've reached the end of the chain...
            return $url;
        }

        //build next url
        if ($location[0] == '/') {
            $u = parse_url($url);
            $url = $u['scheme'] . '://' . $u['host'];
            if (isset($u['port'])) {
                $url .= ':' . $u['port'];
            }
            $url .= $location;
        } else {
            $url = $location;
        }
    }

    return null;
}

function post_parameter_set($parameter) {
    if(strlen($parameter) > 0 && $parameter !== null) return 1;
}

function get_project_status($status) {
    switch($status) {
        case 0:
            return 'open &nbsp; <i class="fas fa-spinner"></i>';
            break;
        case 1:
            return 'closed &nbsp; <i class="far fa-check-circle"></i>';
            break;
    }
}

function get_profile($id) {
    return base_url("hr/employee/" . (int)$id);
}

function get_issue_color($type) {
    switch($type) {
        case "bug":
            return "is-danger";
            break;
        case "documentation":
            return "is-info";
            break;
        case "duplicate":
            return "is-light";
            break;
        case "enhancement":
            return "is-primary";
            break;
        case "wontfix":
            return "is-warning";
            break;
    }
}

function get_month_name($month) {
    $months = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
    return $months[$month];
}
