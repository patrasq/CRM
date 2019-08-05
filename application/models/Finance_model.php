<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Finance
* 
* 
* @package    LeMonkey
* @subpackage Model
*/

class Finance_model extends CI_model {

    function __construct()
    {
        parent::__construct();
    }

    /* public function get_profit()
    *
    * Get profit for a business depending on profit type
    *
    * @param string $type Type of profit to fetch
    * @return array JSON 
    */
    public function get_profit($type) {
        switch($type) { // never too fast
            case 'month':  // in case of Month profit
                $this->db->select("JSON")
                    ->where('BusinessID', get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]))
                    ->limit(1);

                $q = $this->db->get($this->config->config['tables']['income_monthly']);
                if($q->num_rows()) {
                    foreach ($q->result() as $row) $data[] = $row;
                    return $data;
                }
            break;
                
            case 'product': 
                $this->db->select("JSON")
                    ->where('BusinessID', get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]))
                    ->limit(1);

                $q = $this->db->get($this->config->config['tables']['income_product']);
                if($q->num_rows()) {
                    foreach ($q->result() as $row) $data[] = $row;
                    return $data;
                }
            break;
                
            case 'employee': 
                $this->db->select("JSON")
                    ->where('BusinessID', get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]))
                    ->limit(1);

                $q = $this->db->get($this->config->config['tables']['income_employee']);
                if($q->num_rows()) {
                    foreach ($q->result() as $row) $data[] = $row;
                    return $data;
                }
            break;
        }
    }

    public function retrieve_expenses($fetch_data) {
        $this->db->cache_on();
        
        $this->db->select($fetch_data)
            ->where('BusinessID', get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]));

        $q = $this->db->get($this->config->config['tables']['expenses']);
        if($q->num_rows()) {
            foreach ($q->result() as $row) $data[] = $row;
            return $data;
        } else return FALSE;
        
        $this->db->cache_off();
    }
    
    public function add_expenses($post_data, $p2 = "") {
        $this->db->where('BusinessID', get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]));
        $this->db->update($this->config->config['tables']['expenses'], $post_data);

        return TRUE;
    }
}