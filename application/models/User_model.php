<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* User
* 
* 
* @package    LeMonkey
* @subpackage Model
*/

class User_model extends CI_model {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('string');
    }

    public function insert_user($data, $reg_data)
    {   
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['accounts'], $data);
        $lid = $this->db->insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_user @ " . getdate("d-m-Y H:i:s"));

        $reg_data["UserID"] = $lid;

        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['registrations'], $reg_data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_user @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }

    public function create_finance_employee($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['income_employee'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for create_finance_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }

    public function create_finance_monthly($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['income_monthly'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for create_finance_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }

    public function create_finance_product($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['income_product'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for create_finance_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }
    
    public function create_agenda($data) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['agenda'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for create_finance_employee @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }

    public function insert_user_oauth($source, $uid, $email, $name, $ip)
    {   
        $this->db->query("INSERT INTO `" . $this->config->config['tables']['accounts'] . "` (`oauth_provider`, `oauth_uid`, `EMail`, `IP`, `Password`, `Salt`) VALUES (?, ?, ?, ?, ?, ?)", array(
            $source,
            $uid,
            $email,
            $ip,
            "NULL",
            "NULL"
        ));

        $user_id = $this->db->insert_id();

        $this->db->query("INSERT INTO `" . $this->config->config['tables']['accounts_detailed'] . "` (`UserID`, `FirstName`, `LastName`) VALUES (?, ?, ?)", array(
            $user_id,
            $name,
            $name
        ));

        return true;
    }

    public function return_user($email, $password)
    {   
        $query = $this->db->query
            (
            "SELECT ID, EMail, IP, Type FROM `" . $this->config->config['tables']['accounts'] . "` WHERE `EMail` = ? AND `Password` = ? LIMIT 1", 
            array($email,  hash("sha256", "mOGhbVyt!4JkL" . implode(get_info("Salt", "accounts", "EMail", $email), str_split($password, floor(strlen($password)/2))) . get_info("Salt", "accounts", "EMail", $email)))
        );

        if($query->num_rows()) return $query->result()[0];
        else return array();
    }

    public function insert_login($userID, $IP) {
        $this->db->trans_start();
        $this->db->insert($this->config->config['tables']['accounts'], $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) die("should be error management :)");//offline_log("Failed transaction for insert_login @ " . getdate("d-m-Y H:i:s"));
        return TRUE;
    }

    public function confirm_register($given_id) {
        $this->db->query("UPDATE `" . $this->config->config['tables']['registrations'] . "` SET `Confirmed` = 1 WHERE `ID` = ? LIMIT 1", array($given_id));
    }

    public function update_password($password, $salt) {
        $this->db->query("UPDATE `" . $this->config->config['tables']['accounts'] . "` SET `Password` = ? AND `Salt` = ? WHERE `ID` = ? LIMIT 1", array($password, $salt, $this->session->userdata("logged_in")["ID"]));
    }

    public function update_row($id, $post_data) {
        $this->db->where('ID', $id);
        $this->db->update($this->config->config['tables']['accounts'], $post_data);

    }

}