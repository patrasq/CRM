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
        $this->db->from($this->config->config['tables']['projects'])->where("ID", $id)->limit(1);
        $query = $this->db->get();

        if($cache) $this->db->cache_off();

        return ($query ? $query->result_array() : false);

    }

    public function get_milestones($id) {
        $this->db->select();
        $this->db->where("ProjectID", $id);
        $this->db->from($this->config->config['tables']['milestones']);
        $query = $this->db->get();

        return ($query ? $query->result_array() : false);
    }

    public function get_issues($id) {
        $this->db->select();
        $this->db->where("ProjectID", $id);
        $this->db->from($this->config->config['tables']['issues']);
        $query = $this->db->get();

        return ($query ? $query->result_array() : false);
    }

    /* MILESTONE */
    public function add_milestone($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['milestones'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }
    public function assign_milestone($data, $id) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['milestones'], $data);
        return TRUE;
    }
    public function complete_milestone($data, $id) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['milestones'], $data);
        return TRUE;
    }
    /* /MILESTONE/ */

    public function add_issue($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['issues'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }
    public function assign_issue($data, $id) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['issues'], $data);
        return TRUE;
    }
    public function assign_project($id, $data) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['projects'], $data);
        return TRUE;
    }
    public function complete_issue($data, $id) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['issues'], $data);
        return TRUE;
    }

    public function get_team($data, $project_id) {
        $this->db->select($data);
        $this->db->where("ID", $project_id);
        $this->db->from($this->config->config['tables']['projects']);
        $this->db->limit(1);
        $query = $this->db->get();

        return ($query->num_rows() ? $query->result_array() : false);

    }

    public function add_project($data, $data2) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['projects'], $data);
        $lastId = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));

        for($i = 0; $i < sizeof($data2); $i++) {
            $data2[$i]["ProjectID"] = $lastId;  
        }

        $this->db->trans_start();
        $this->db->insert_batch($this->config->config['tables']['milestones'], $data2); //hehe love CI3
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        return $lastId;
    }

    public function my_tasks($id) {
        $array = array();

        $query1 = $this->db->query("SELECT Name, ProjectID, Type, CompletedOn FROM ".$this->config->config['tables']['issues']." WHERE `AssignedTo` = '" . $this->session->userdata("logged_in")["ID"]."' AND `ProjectID` = ?", array($id));
        if($query1->num_rows()) {
            foreach($query1->result_array() as $row) {
                $array[] = $row;
            }
        }

        $query2 = $this->db->query("SELECT Name, ProjectID, CompleteDate FROM ".$this->config->config['tables']['milestones']." WHERE `AssignedTo` = '".$this->session->userdata('logged_in')["ID"]."' AND `ProjectID` = ?", array($id));
        if($query2->num_rows()) {
            foreach($query2->result_array() as $row) {
                $row["Type"] = null;
                $array[] = $row;
            }
        }

        return $array;
    }

    public function discharge($data, $id) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['projects'], $data);
        return TRUE;
    }

    public function insert_notification($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['notifications'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }
    
    public function deliver_project($data,$id) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['projects'], $data);
        return TRUE;
    }
}