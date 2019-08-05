<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* CEO
* 
* 
* @package    LeMonkey
* @subpackage Model
*/

class CEO_Model extends CI_model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function retrieve_agenda($fetch_data) {
        $this->db->cache_on();
        
        $this->db->select($fetch_data)
            ->where('UserID', $this->session->userdata("logged_in")["ID"])
            ->limit(1);

        $q = $this->db->get($this->config->config['tables']['agenda']);
        if($q->num_rows()) {
            foreach ($q->result() as $row) $data[] = $row;
            return $data;
        } else return FALSE;
        
        $this->db->cache_off();
    }
    
    public function add_event($post_data, $p2 = "") {
        $this->db->where('UserID', $this->session->userdata("logged_in")["ID"]);
        $this->db->update($this->config->config['tables']['agenda'], $post_data);
        
        if($p2 != "") {
            $this->db->where('UserID', $this->session->userdata("logged_in")["ID"]);
            $this->db->update($this->config->config['tables']['agenda'], $p2);
        }
        return TRUE;
    }
    
}