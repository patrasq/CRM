<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Globals {
    
    function __contstruct($config = array())
    {
        foreach($config as $key => $value)
        {
            $data[$key] = $value; 
        }
        $CI =& get_instance();
        $CI->load->vars($data);
    }
}