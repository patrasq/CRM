<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Projects
* 
* 
* @package    LeMonkey
* @subpackage Model
*/

class Projects_model extends CI_model {

    function __construct()
    {
        parent::__construct();
    }

    public function get_projects($data, $personal = 0) {

        $personal ? 
            $this->db->select($data)
            ->where('Supervizor', $personal)
            :
        $this->db->select($data);

        return ($this->db->get($this->config->config['tables']['projects'])->result_array()) ? $this->db->get($this->config->config['tables']['projects'])->result_array() : false;

    }

    public function get_info($data, $id, $cache = 0) {
        if($cache) $this->db->cache_on();
        
        $this->db->select($data);
        $this->db->from($this->config->config['tables']['projects']);
        $query = $this->db->get();
        
        if($cache) $this->db->cache_off();
        
        return ($query ? $query->result_array() : false);

    }
    
    public function get_milestones($id) {
        $this->db->select();
        $this->db->from($this->config->config['tables']['milestones']);
        $query = $this->db->get();
        
        return ($query ? $query->result_array() : false);
    }
    
    public function get_issues($id) {
        $this->db->select();
        $this->db->from($this->config->config['tables']['issues']);
        $query = $this->db->get();
        
        return ($query ? $query->result_array() : false);
    }

    public function add_milestone($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['milestones'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }
    
    public function add_issue($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['issues'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }
    
    public function add_project($data, $data2) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['projects'], $data);
        $lastId = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        
        for($i = 0; $i < sizeof($data2); $i++) {
            $data2[]["ProjectID"] = ;  
        }
        
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['milestones'], $data2);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }
}