<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* HR
* 
* 
* @package    T4P
* @subpackage Controller
*/

class HR extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('HR_model'); 
        $this->load->helper(array('url'));

        if($this->session->userdata('logged_in') == null) {
            redirect(base_url("login"));
        }

        if (!is_cache_valid(md5('hr'), 60)){
            $this->db->cache_delete('hr');
        }
    }

    public function index()
    {
        redirect(base_url("dashboard"));
    }

    public function _remap($method,$args)
    {
        if (method_exists($this, $method))
        {
            $this->$method($args);  
        }
        else
        {
            $this->index($method,$args);
        }
    }

    public function departments() {
        $data['text_add_department']        = $this->lang->line('dashboard_add_department');

        $data['department']                 = $this->HR_model->get_departments(array(
            "ID",
            "Name",
            "Positions"
        ));

        
        $data["main_content"] = 'dashboard/departments_view';
        $this->load->view('includes/template.php', $data);
    }

    public function employees() {
        $data['text_add_employee']          = $this->lang->line('dashboard_add_employee');

        $data['employee']                   = $this->HR_model->get_employees(array("ID", "FirstName", "LastName", "Position", "Department"));
        $data['position']                   = $this->HR_model->get_departments(get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]));

        $data['department']                 = $this->HR_model->get_departments(array("ID", "Name"));

        $data["main_content"] = 'dashboard/employees_view';
        $this->load->view('includes/template.php', $data);
    }
    
    public function edit_department() {
        if(
            post_parameter_set($this->input->post("set_departmentname")) && 
            post_parameter_set($this->input->post("set_positions")) 
          ) {
            $dep_id         =       (int)$this->input->post("depid");
            $dep_name       =       html_purify($this->input->post("set_departmentname"));
            $positions      =       html_purify($this->input->post("set_positions"));
            
            $dep_posi       =       str_replace(",", "|", $positions);
                
            if(countTable($this->config->config['tables']['departments'], "WHERE `Name` = '" . $dep_name . "' AND `ID` <> ".$dep_id." AND `BusinessID` = " . get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]) . " LIMIT 1")) flash_redirect("error", "This department already exists.", base_url("hr/departments")); // Check if the submitted department exists
            
            if(!countTable($this->config->config['tables']['departments'], "WHERE `ID` = '" . $dep_id . "' AND `BusinessID` = " . get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]) . " LIMIT 1")) flash_redirect("error", "This ID isn't yours.", base_url("hr/departments")); // Check if the submitted ID is owners'

            $department_data = array(
                "Name"          => $dep_name,
                "Positions"     => $dep_posi
            );

            if($this->HR_model->update_department($department_data, $dep_id)) {
                $this->db->cache_delete('hr', 'departments');
                $this->db->cache_delete('hr', 'employees');
                flash_redirect("success", "Department updated successfully.", base_url("hr/departments")); 
            } else flash_redirect("error", "Something wrong happened.", base_url("hr/departments"));
        }
    }
    
    public function delete_employee() {
        $employee   =   (int)$this->uri->segment(3);
        if(countTable($this->config->config['tables']['employees'], "WHERE `ID` = " . $employee . " AND BusinessID = " . get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]))) {
            if($this->HR_model->delete_employee($employee)) {
                $this->db->cache_delete("hr", "employee");
                $this->db->cache_delete("hr", "employees");
                flash_redirect("success", "You\'ve successfully deleted your employee.", base_url("hr/employees"));
            }
        } else flash_redirect("error", "Invalid employee.", base_url("hr/employees"));
    }

    public function employee() {
        if(exists($this->uri->segment(3), $this->config->config['tables']['employees'], "ID")) {

            $data['text_delete_employee']       = "Delete employee";

            $data['text_tab1']                  = $this->lang->line('employee_tab1');
            $data['text_tab2']                  = $this->lang->line('employee_tab2');
            $data['text_tab3']                  = $this->lang->line('employee_tab3');


            /* GENERAL */
            $data['employee_id']                = $this->HR_model->get_employee_info($this->uri->segment(3), "ID");
            $data['employee_name']              = $this->HR_model->get_employee_info($this->uri->segment(3), "FirstName") . " " . $this->HR_model->get_employee_info($this->uri->segment(3), "LastName");
            $data['employee_gender']            = $this->HR_model->get_employee_info($this->uri->segment(3), "Gender");
            $data['employee_phone']             = $this->HR_model->get_employee_info($this->uri->segment(3), "Phone");
            $data['employee_address']           = $this->HR_model->get_employee_info($this->uri->segment(3), "Street");
            $data['employee_birth']             = $this->HR_model->get_employee_info($this->uri->segment(3), "Birth");
            $data['employee_hiredsince']        = $this->HR_model->get_employee_info($this->uri->segment(3), "HiredSince");
            $data['employee_position']          = $this->HR_model->get_employee_info($this->uri->segment(3), "Position");
            $data['employee_department']        = $this->HR_model->get_employee_info($this->uri->segment(3), "Department");
            /* END OF - GENERAL */



            /* FINANCE */
            $data['employee_bank_holder']       = $this->HR_model->get_employee_bank_info($this->uri->segment(3), "Holder") 
                ? 
            "<a href='#' class='notset_set'><abbr title='Edit BANK HOLDER'>".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "Holder")."</abbr></a>".form_open("hr/modify_bankholder/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='bankholder' value='".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "Holder")."'></div><div class='column'> <button type='submit' class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>"
                :   
            "<a href='#' class='notset_set'>".$this->lang->line('employee_notset')."</a>".form_open("hr/modify_bankholder/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='bankholder'></div><div class='column'> <button type='submit' class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>";

            $data['employee_bank_number']       = $this->HR_model->get_employee_bank_info($this->uri->segment(3), "Number") 
                ? 
            "<a href='#' class='notset_set'><abbr title='Edit BANK NUMBER'>".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "Number")."</abbr></a>".form_open("hr/modify_banknumber/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='banknumber' value='".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "Number")."'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>"
                :   
            "<a href='#' class='notset_set'>".$this->lang->line('employee_notset')."</a>".form_open("hr/modify_banknumber/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='banknumber'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>";

            $data['employee_bank_name']         = $this->HR_model->get_employee_bank_info($this->uri->segment(3), "BankName") 
                ? 
            "<a href='#' class='notset_set'><abbr title='Edit BANK NAME'>".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "BankName")."</abbr></a>".form_open("hr/modify_bankname/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='bankname' value='".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "BankName")."'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>"
                :   
            "<a href='#' class='notset_set'>".$this->lang->line('employee_notset')."</a>".form_open("hr/modify_bankname/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='bankname'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>";

            $data['employee_bank_bin']          = $this->HR_model->get_employee_bank_info($this->uri->segment(3), "BIN") 
                ? 
            "<a href='#' class='notset_set'><abbr title='Edit BANK BIN'>".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "BIN")."</abbr></a>".form_open("hr/modify_bankbin/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='bankbin' value='".$this->HR_model->get_employee_bank_info($this->uri->segment(3), "BIN")."'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>"
                :   
            "<a href='#' class='notset_set'>".$this->lang->line('employee_notset')."</a>".form_open("hr/modify_bankbin/" . $this->uri->segment(3))."<div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded' name='bankbin'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i>".form_close()."</a>";
            /* END OF - FINANCE */



            /* DOCUMENTS */
            $data['employee_documents_cv']      = $this->HR_model->get_employee_info($this->uri->segment(3), "CV") 
                ? 
                $this->HR_model->get_employee_info($this->uri->segment(3), "CV") //yes
                :   
            "<a href='#' class='notset_set'>".$this->lang->line('employee_notset')."</a><div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i></a>";

            $data['employee_documents_ct']      = $this->HR_model->get_employee_info($this->uri->segment(3), "Contract") 
                ? 
                $this->HR_model->get_employee_info($this->uri->segment(3), "Contract") //yes
                :   
            "<a href='#' class='notset_set'>".$this->lang->line('employee_notset')."</a><div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i></a>";

            $data['employee_documents_id']      = $this->HR_model->get_employee_info($this->uri->segment(3), "IDProof") 
                ? 
                $this->HR_model->get_employee_info($this->uri->segment(3), "IDProof") //yes
                :   
            "<a href='#' class='notset_set'>".$this->lang->line('employee_notset')."</a><div class='columns editrow' style='display:none'><div class='column'><input class='input is-rounded'></div><div class='column'> <button class='button is-outlined'><i class='fas fa-save'></i></button> <a class='button is-outlined notset_revert' ><i class='fas fa-window-close notset_revert'></i></a>";
            /* END OF - DOCUMENTS */


            $data["main_content"] = 'dashboard/employee_view';
            $this->load->view('includes/template.php', $data);

        } else {
            redirect(base_url(""));
        }
    }

    public function get_positions() {
        if(exists($this->uri->segment(3), $this->config->config['tables']['departments'], "ID")) {
            $uri            = $this->uri->segment(3);
            $concatenater   = "";
            $positions      = get_info("Positions", $this->config->config['tables']['departments'], "ID", $uri);
            $positions      = explode('|', $positions);

            foreach($positions as $row) {
                $concatenater .= "<option value='$row'>$row</option>";
            }

            die($concatenater);
        }
    }

    /*
    * public function add_employee()
    * 
    * Add an employee. 
    *
    * @return view
    */
    public function add_employee() {
        if(
            $this->input->post("firstname")     !== null  &&  post_parameter_set($this->input->post("firstname")) &&
            $this->input->post("lastname")      !== null  &&  post_parameter_set($this->input->post("lastname"))  &&
            $this->input->post("department")    !== null  &&  post_parameter_set($this->input->post("department"))&&
            $this->input->post("position")      !== null  &&  post_parameter_set($this->input->post("position"))  &&
            $this->input->post("gender")        !== null  &&  post_parameter_set($this->input->post("gender"))    &&
            $this->input->post("phone")         !== null  &&  post_parameter_set($this->input->post("phone"))     &&
            $this->input->post("address")       !== null  &&  post_parameter_set($this->input->post("address"))   &&
            $this->input->post("birthdate")     !== null  &&  post_parameter_set($this->input->post("birthdate")) &&
            $this->input->post("hiredsince")    !== null  &&  post_parameter_set($this->input->post("hiredsince"))
        ) {

            /*
            *
            * Purify submitted information, then check it's integrity
            *
            */
            $first_name     =   html_purify($this->input->post("firstname"));
            $last_name      =   html_purify($this->input->post("lastname"));
            $department     =   (int)$this->input->post("department"); 
            $position       =   html_purify($this->input->post("position")); 
            $gender         =   html_purify($this->input->post("gender"));
            $phone          =   html_purify($this->input->post("phone")); // o sa vad eu
            $address        =   html_purify($this->input->post("address")); // o sa vad eu
            $birth_date     =   strtotime($this->input->post("birthdate"));
            $hired_since    =   strtotime($this->input->post("hiredsince"));

            if(!exists($department, $this->config->config['tables']['departments'], "ID")) flash_redirect("error", "Invalid department.", base_url("hr/employees")); // Check if the submitted department exists
            if(strpos(get_info("Positions", $this->config->config['tables']['departments'], "ID", $department), $position) == false) flash_redirect("error", "Invalid position.", base_url("hr/employees")); // Check if the submitted position exists
            if(!in_array($gender, array("Male", "Female"))) flash_redirect("error", "Invalid gender.", base_url("hr/employees")); // Check if the submitted gender is valid (personally I recognize only men and women)

            $department = get_info("Name", $this->config->config['tables']['departments'], "ID", $department);

            $employee_data = array(
                "BusinessID"    => get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]),
                "FirstName"     => $first_name,
                "LastName"      => $last_name,
                "Department"    => $department,
                "Position"      => $position,
                "Gender"        => $gender,
                "Phone"         => $phone,
                "Street"        => $address,
                "Birth"         => $birth_date,
                "HiredSince"    => $hired_since
            );
            
            if($this->HR_model->insert_employee($employee_data)) {
                $this->db->cache_delete('hr', 'departments');
                $this->db->cache_delete('hr', 'employees');
                flash_redirect("success", "Employee added successfully.", base_url("hr/employees"));
            } else flash_redirect("error", "Something wrong happened.", base_url("hr/employees"));
        } else flash_redirect("error", "Something wrong happened.", base_url("hr/employees"));
    }
    
    /*
    * public function add_department()
    * 
    * Add a department. 
    *
    * @return view
    */
    public function add_department() {
        if(
            $this->input->post("departmentname")    !== null  &&  post_parameter_set($this->input->post("departmentname")) &&
            $this->input->post("description")       !== null  &&  post_parameter_set($this->input->post("description"))  &&
            $this->input->post("positions")         !== null  &&  post_parameter_set($this->input->post("positions"))
        ) {

            /*
            *
            * Purify submitted information, then check it's integrity
            *
            */
            $dep_name     =   html_purify($this->input->post("departmentname"));
            $dep_desc     =   html_purify($this->input->post("description"));
            $dep_posi     =   html_purify($this->input->post("positions"));
            
            $dep_posi     =   str_replace(",", "|", $dep_posi);
                
            if(countTable($this->config->config['tables']['departments'], "WHERE `Name` = '" . $dep_name . "' AND `BusinessID` = " . get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]) . " LIMIT 1")) flash_redirect("error", "This department already exists.", base_url("hr/departments")); // Check if the submitted department exists

            $department_data = array(
                "BusinessID"    => get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]),
                "Name"          => $dep_name,
                "Description"   => $dep_desc,
                "Positions"     => $dep_posi
            );

            if($this->HR_model->insert_department($department_data)) {
                $this->db->cache_delete('hr', 'departments');
                $this->db->cache_delete('hr', 'employees');
                flash_redirect("success", "Department added successfully.", base_url("hr/departments")); 
            } else flash_redirect("error", "Something wrong happened.", base_url("hr/departments"));
        } else flash_redirect("error", "Something wrong happened.", base_url("hr/departments"));
    }
    
    public function modify_bankholder() {
        if($this->input->post("bankholder") !== null && exists($this->uri->segment(3), $this->config->config['tables']['employees'], "ID")) {
            $bank_holder = html_purify($this->input->post("bankholder"));

            if($bank_holder) {
                if($this->HR_model->modify('bankholder', $bank_holder, $this->uri->segment(3))) {
                    $this->db->cache_delete('hr', 'employee');
                    flash_redirect("success", "You've modified bank holder successfully", base_url("hr/employee/" . $this->uri->segment(3)));
                } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
            } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
        } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
    }
    

    public function modify_bankname() {
        if($this->input->post("bankname") !== null && exists($this->uri->segment(3), $this->config->config['tables']['employees'], "ID")) {
            $bank_holder = html_purify($this->input->post("bankname"));

            if($bank_holder) {
                $this->db->cache_delete('hr', 'employee');
                ($this->HR_model->modify('bankname', $bank_holder, $this->uri->segment(3))) ? flash_redirect("success", "You've modified bank name successfully", base_url()) : flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
            } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
        }
    }
    
    public function modify_banknumber() {
        if($this->input->post("banknumber") !== null && exists($this->uri->segment(3), $this->config->config['tables']['employees'], "ID")) {
            $bank_holder = html_purify($this->input->post("banknumber"));

            if($bank_holder) {
                $this->db->cache_delete('hr', 'employee');
                ($this->HR_model->modify('banknumber', $bank_holder, $this->uri->segment(3))) ? flash_redirect("success", "You've modified bank number successfully", base_url()) : flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
            } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
        } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
    }
    
    public function modify_bankbin() {
        if($this->input->post("bankbin") !== null && exists($this->uri->segment(3), $this->config->config['tables']['employees'], "ID")) {
            $bank_holder = (int)html_purify($this->input->post("bankbin"));

            if($bank_holder) {
                $this->db->cache_delete('hr', 'employee');
                ($this->HR_model->modify('bankbin', $bank_holder, $this->uri->segment(3))) ? flash_redirect("success", "You've modified bank BIN successfully", base_url()) : flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
            } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
        } else flash_redirect("error", "Something went wrong...", base_url("hr/employee/" . $this->uri->segment(3)));
    }
}
?>