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

}