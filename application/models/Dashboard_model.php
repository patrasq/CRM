<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Dashboard
* 
* 
* @package    LeMonkey
* @subpackage Model
*/

class Dashboard_model extends CI_model {

    function __construct()
    {
        parent::__construct();
    }

    function insert_login($userID, $IP) {
        $this->db->query("INSERT INTO `" . $this->config->config['tables']['logins'] . "` (`UserID`, `IP`, `Time`) VALUES (?, ?, ?)", array($userID, $IP, date("Y-m-d H:i:s")));
    }

    function get_logins($user_id, $limit) {
        $this->db->cache_on();
        $query = $this->db->query("SELECT `IP`, `Time` FROM " . $this->config->config['tables']['logins'] . " WHERE `UserID` = ? ORDER BY ID DESC LIMIT $limit", array($user_id));

        if($query->num_rows()) return $query->result_array();
        else return 0;
    }

    function get_total_logins($user_id) {
        $this->db->cache_on();
        $query = $this->db->query("SELECT COUNT(ID) AS TOTAL FROM " . $this->config->config['tables']['logins'] . " WHERE `UserID` = ?", array($user_id));

        if($query->num_rows()) return $query->result()[0]->TOTAL;
        else return 0;
    }

    function get_tasks($id) {

        $array = array();

        $query1 = $this->db->query("SELECT Name, ProjectID, Type, CompletedOn FROM ".$this->config->config['tables']['issues']." WHERE `AssignedTo` = '" . $id."'");
        if($query1->num_rows()) {
            foreach($query1->result_array() as $row) {
                $row["z"] = "issue";
                $array[] = $row;
            }
        }

        $query2 = $this->db->query("SELECT Name, ProjectID, CompleteDate FROM ".$this->config->config['tables']['milestones']." WHERE `AssignedTo` = '".$id."'");
        if($query2->num_rows()) {
            foreach($query2->result_array() as $row) {
                $row["Type"] = null;
                $row["z"] = "milestone";
                $array[] = $row;
            }
        }

        /*
        $this->db->
            select('a.Name as issues_name, a.ProjectID as issues_id, b.Name as milestones_name, b.ProjectID as milestones_id, a.Type as issues_type')
            ->from($this->config->config['tables']['issues'] . ' as a, '.$this->config->config['tables']['milestones'].' as b')
            ->where('(a.AssignedTo = ' . $this->session->userdata("logged_in")["ID"] . ' OR b.AssignedTo = ' . $this->session->userdata("logged_in")["ID"] . ')');
        $query = $this->db->get();
        */

        return $array;
    }

    /* public function get_departments($data)
    *
    * Get departments for a specific business
    *
    * @param string $fetch_data Data to retrieve
    * @return array Fetched data 
    */
    function get_departments() {
        $this->db->cache_on();

        $this->db->select("*");
        $this->db->from($this->config->config['tables']['departments']);

        $q = $this->db->get();
        if($q->num_rows()) {
            foreach ($q->result() as $row) $data[] = $row;
            return $data;
        } else return FALSE;
    }

    function get_issues_graph($id) {
        $query = $this->db->query("SELECT Name, ProjectID, Type, CompletedOn FROM ".$this->config->config['tables']['issues']." WHERE `CompletedBy` = '" . $id."'");
        if($query->num_rows()) {
            return $query->result_array();
        } else return null;
    }

    function get_milestones_graph($id) {
        $query = $this->db->query("SELECT Name, ProjectID, CompleteDate FROM ".$this->config->config['tables']['milestones']." WHERE `CompletedBy` = '".$id."'");
        if($query->num_rows()) {
            return $query->result_array();
        } else return null;
    }

    function get_projects() {
        $this->db->select(array("Name", "ID", "Description"));
        $this->db->where("AssignedTo1", $this->session->userdata("logged_in")["ID"]);
        $this->db->or_where("AssignedTo2", $this->session->userdata("logged_in")["ID"]);
        $this->db->or_where("AssignedTo3", $this->session->userdata("logged_in")["ID"]);
        $this->db->or_where("AssignedTo4", $this->session->userdata("logged_in")["ID"]);
        $this->db->or_where("AssignedTo5", $this->session->userdata("logged_in")["ID"]);
        $this->db->or_where("Supervizor", $this->session->userdata("logged_in")["ID"]);
        $this->db->where("Status", 0);
        $this->db->from($this->config->config['tables']['projects']);
        $query = $this->db->get();

        return ($query ? $query->result_array() : false);
    }

    public function push_usersignal($u_id, $id) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['accounts'], array("OneSignal"=>$u_id));
        return TRUE;
    }

}