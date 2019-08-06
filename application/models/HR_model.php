<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* HR
* 
* 
* @package    LeMonkey
* @subpackage Model
*/

class HR_model extends CI_model {

    function __construct()
    {
        parent::__construct();
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

    /* public function get_employees($business_id)
    *
    * Get departments for a specific business
    *
    * @param string $fetch_data Data to retrieve
    * @return array Fetched data 
    */
    function get_employees($fetch_data) {
        $this->db->cache_on();

        $this->db->select($fetch_data);
        $this->db->from($this->config->config['tables']['employees']);

        $q = $this->db->get();
        if($q->num_rows()) {
            foreach ($q->result() as $row) $data[] = $row;
            return $data;
        } else return FALSE;

    }

    function get_employee_info($id, $info = "ID") {
        $this->db->cache_on();
        $query = $this->db->query("SELECT " . $info . " FROM " . $this->config->config['tables']['employees'] . " WHERE `ID` = ? LIMIT 1", array($id));

        if($query->num_rows()) return $query->result()[0]->$info;
        else return 0;
    }

    function get_employee_bank_info($id, $info = "ID") {
        $this->db->cache_on();
        $query = $this->db->query("SELECT " . $info . " FROM " . $this->config->config['tables']['bank_employees'] . " WHERE `ID` = ? LIMIT 1", array($id));

        if($query->num_rows()) return $query->result()[0]->$info;
        else return 0;
    }

    function insert_employee($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['employees'], $data);
        $last_id = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));

        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['bank_employees'], array("EmployeeID" => $last_id));
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_employee @ " . getdate("d-m-Y H:i:s"));

        return TRUE;
    }

    public function update_department($data, $dep_id) {
        $this->db->where('ID', $dep_id);
        $this->db->update($this->config->config['tables']['departments'], $data);
        return TRUE;
    }

    public function insert_department($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['departments'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");
        return TRUE;
    }

    function modify($option, $new_value, $user_id) {
        switch($option) {
            case "bankholder":
                $modifier = "Holder";
                break;

            case "banknumber":
                $modifier = "Number";
                break;

            case "bankname":
                $modifier = "Name";
                break;

            case "bankbin":
                $modifier = "BIN";
                break;
        }

        if($this->db->query("UPDATE `" . $this->config->config['tables']['bank_employees'] . "` SET $modifier = ? WHERE `EmployeeID` = ? LIMIT 1", array($new_value, $user_id))) return TRUE;
    }


}