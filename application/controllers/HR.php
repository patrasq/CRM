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
        $data['position']                   = $this->HR_model->get_departments();

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

            if(countTable($this->config->config['tables']['departments'], "WHERE `Name` = '" . $dep_name . "' AND `ID` <> ".$dep_id." LIMIT 1")) flash_redirect("error", "This department already exists.", base_url("hr/departments")); // Check if the submitted department exists

            if(!countTable($this->config->config['tables']['departments'], "WHERE `ID` = '" . $dep_id . "' LIMIT 1")) flash_redirect("error", "Does not exist.", base_url("hr/departments"));
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
        if(countTable($this->config->config['tables']['employees'], "WHERE `ID` = " . $employee)) {
            if($this->HR_model->delete_employee($employee)) {
                $this->db->cache_delete("hr", "employee");
                $this->db->cache_delete("hr", "employees");
                flash_redirect("success", "You\'ve successfully deleted your employee.", base_url("hr/employees"));
            }
        } else flash_redirect("error", "Invalid employee.", base_url("hr/employees"));
    }

    public function employee() {
        if(exists($this->uri->segment(3), $this->config->config['tables']['employees'], "ID")) {

            $this->load->model('Dashboard_model');

            $data['text_delete_employee']       = "Delete employee";

            $data['text_tab1']                  = $this->lang->line('employee_tab1');
            $data['text_tab2']                  = $this->lang->line('employee_tab2');
            $data['text_tab3']                  = $this->lang->line('employee_tab3');


            $data["completed_tasks"]            = countTable($this->config->config["tables"]["issues"], "WHERE CompletedBy = '".$this->uri->segment(3)."'") + countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy = '".$this->uri->segment(3)."'");
            $data["max_tasks"]                  = countTable($this->config->config["tables"]["issues"], "WHERE AssignedTo = '".$this->uri->segment(3)."'") + countTable($this->config->config["tables"]["milestones"], "WHERE AssignedTo = '".$this->uri->segment(3)."'");

            $data["completed_issues"]           = countTable($this->config->config["tables"]["issues"], "WHERE CompletedBy = '".$this->uri->segment(3)."'");
            $data["max_issues"]                 = countTable($this->config->config["tables"]["issues"], "WHERE AssignedTo = '".$this->uri->segment(3)."'") ;

            $data["completed_milestones"]       = countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy = '".$this->uri->segment(3)."'") + countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy = '".$this->uri->segment(3)."'");
            $data["max_milestones"]             = countTable($this->config->config["tables"]["milestones"], "WHERE AssignedTo = '".$this->uri->segment(3)."'") + countTable($this->config->config["tables"]["milestones"], "WHERE AssignedTo = '".$this->uri->segment(3)."'");

            $var1                               =   $this->Dashboard_model->get_tasks();
            $var2                               =   $this->Dashboard_model->get_issues_graph();
            $var3                               =   $this->Dashboard_model->get_milestones_graph();

            $tasks_return_months                =   array();
            $issues_return_months               =   array();
            $milestones_return_months           =   array();

            $tasks_dates = array();
            $issues_dates = array();
            $milestones_dates = array();

            foreach($var1 as $row) {
                if(isset($row["CompletedOn"]) && $row["CompletedOn"] != null) {
                    $tasks_dates[]    =   $row["CompletedOn"];
                } elseif(isset($row["CompleteDate"]) && $row["CompleteDate"] != null) {
                    $tasks_dates[]    =   $row["CompleteDate"];
                }
            }

            foreach($var2 as $row) {
                if(isset($row["CompletedOn"]) && $row["CompletedOn"] != null) {
                    $issues_dates[]    =   $row["CompletedOn"];
                } elseif(isset($row["CompleteDate"]) && $row["CompleteDate"] != null) {
                    $issues_dates[]    =   $row["CompleteDate"];
                }
            }

            foreach($var3 as $row) {
                if(isset($row["CompletedOn"]) && $row["CompletedOn"] != null) {
                    $milestones_dates[]    =   $row["CompletedOn"];
                } elseif(isset($row["CompleteDate"]) && $row["CompleteDate"] != null) {
                    $milestones_dates[]    =   $row["CompleteDate"];
                }
            }

            for($i = 0; $i < sizeof($tasks_dates); $i++) {
                for($increment_month = 0; $increment_month < 12; $increment_month++) {
                    $php_date = getdate(strtotime($tasks_dates[$i]));
                    if(intval(date("m", strtotime($tasks_dates[$i]))) == $increment_month)
                        @$tasks_return_months[$increment_month] = $tasks_return_months[$increment_month]+1;
                }
            }

            for($i = 0; $i < sizeof($issues_dates); $i++) {
                for($increment_month = 0; $increment_month < 12; $increment_month++) {
                    $php_date = getdate(strtotime($issues_dates[$i]));
                    if(intval(date("m", strtotime($issues_dates[$i]))) == $increment_month)
                        @$issues_return_months[$increment_month] = $issues_return_months[$increment_month]+1;
                }
            }

            for($i = 0; $i < sizeof($milestones_dates); $i++) {
                for($increment_month = 0; $increment_month < 12; $increment_month++) {
                    $php_date = getdate(strtotime($milestones_dates[$i]));
                    if(intval(date("m", strtotime($milestones_dates[$i]))) == $increment_month)
                        @$milestones_return_months[$increment_month] = $milestones_return_months[$increment_month]+1;
                }
            }

            $months = json_decode('{"january":0,"february":0,"march":0,"april":0,"may":0,"june":0,"july":0,"august":0,"september":0,"october":0,"november":0,"december":0}', true);

            $tasks_months = $months;
            $issues_months = $months;
            $milestones_months = $months;

            for($i = 1; $i <= 12; $i++) {
                if(isset($tasks_return_months[$i]))
                    $tasks_months[get_month_name($i-1)] = $tasks_return_months[$i];
            }

            for($i = 1; $i <= 12; $i++) {
                if(isset($issues_return_months[$i]))
                    $issues_months[get_month_name($i-1)] = $issues_return_months[$i];
            }

            for($i = 1; $i <= 12; $i++) {
                if(isset($milestones_return_months[$i]))
                    $milestones_months[get_month_name($i-1)] = $milestones_return_months[$i];
            }

            $tasks_js =   "[";
            foreach($tasks_months as $key => $value) {

                $tasks_js .= "'" . $value . "',";
            }
            $tasks_js = rtrim( $tasks_js, "," );
            $tasks_js .= "]";

            $issues_js =   "[";
            foreach($issues_months as $key => $value) {

                $issues_js .= "'" . $value . "',";
            }
            $issues_js = rtrim( $issues_js, "," );
            $issues_js .= "]";

            $milestones_js =   "[";
            foreach($milestones_months as $key => $value) {

                $milestones_js .= "'" . $value . "',";
            }
            $milestones_js = rtrim( $milestones_js, "," );
            $milestones_js .= "]";

            //die(print_r($tasks_return_months));
            $data["tasks_completed_monthly"]      = $tasks_js;
            $data["issues_completed_monthly"]     = $issues_js;
            $data["milestones_completed_monthly"] = $milestones_js;

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

            if(countTable($this->config->config['tables']['departments'], "WHERE `Name` = '" . $dep_name . "' LIMIT 1")) flash_redirect("error", "This department already exists.", base_url("hr/departments")); // Check if the submitted department exists

            $department_data = array(
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

    public function download_excel() {
        if($this->uri->segment(3) !== null) {
            $u_id   =   (int)$this->uri->segment(3);
            if(!countTable($this->config->config['tables']['accounts'], "WHERE ID = '".$u_id."'")) flash_redirect("error", "Something went wrong...", base_url());
            require(APPPATH . "libraries\PHPExcel.php");

            $excel = new PHPExcel();

            $excel->setActiveSheetIndex(0);

            $excel->getActiveSheet()->setTitle("General");

            $excel->getActiveSheet()->setCellValue("A1", "Name");
            $excel->getActiveSheet()->setCellValue("A2", "Gender");
            $excel->getActiveSheet()->setCellValue("A3", "Phone");
            $excel->getActiveSheet()->setCellValue("A4", "Address");
            $excel->getActiveSheet()->setCellValue("A5", "Birth date");
            $excel->getActiveSheet()->setCellValue("A6", "Hired since");

            $excel->getActiveSheet()->setCellValue("B1", get_cached_info('FirstName', $this->config->config['tables']['employees'], "ID", $u_id) . ' ' . get_cached_info('LastName', $this->config->config['tables']['employees'], "ID", $u_id));
            $excel->getActiveSheet()->setCellValue("B2", get_cached_info('Gender', $this->config->config['tables']['employees'], "ID", $u_id));
            $excel->getActiveSheet()->setCellValue("B3", get_cached_info('Phone', $this->config->config['tables']['employees'], "ID", $u_id));
            $excel->getActiveSheet()->setCellValue("B4", get_cached_info('Street', $this->config->config['tables']['employees'], "ID", $u_id));
            $excel->getActiveSheet()->setCellValue("B5", date("d-m-Y", get_cached_info('Birth', $this->config->config['tables']['employees'], "ID", $u_id)));
            $excel->getActiveSheet()->setCellValue("B6", date("d-m-Y", get_cached_info('HiredSince', $this->config->config['tables']['employees'], "ID", $u_id)));

            $excel->createSheet(1);    
            $excel->setActiveSheetIndex(1);
            $excel->getActiveSheet()->setTitle("Insights");

            /*
            =====================================
            ==========[TASKS COMPLETED]==========
            =====================================
            */
            $objWorksheet = $excel->getActiveSheet();
            $objWorksheet->fromArray(
                array(
                    array(
                        "Completed",
                        "Max",
                        "Completed",
                        "Max",
                        "Completed",
                        "Max"
                    ),
                    array(
                        countTable($this->config->config["tables"]["issues"], "WHERE CompletedBy = '".$this->uri->segment(3)."'") + countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy = '".$this->uri->segment(3)."'"),
                        countTable($this->config->config["tables"]["issues"], "WHERE AssignedTo = '".$this->uri->segment(3)."'") + countTable($this->config->config["tables"]["milestones"], "WHERE AssignedTo = '".$this->uri->segment(3)."'"),
                        
                        countTable($this->config->config["tables"]["issues"], "WHERE CompletedBy = '".$this->uri->segment(3)."'"),
                        countTable($this->config->config["tables"]["issues"], "WHERE AssignedTo = '".$this->uri->segment(3)."'"),
                        
                        countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy = '".$this->uri->segment(3)."'"),
                        countTable($this->config->config["tables"]["milestones"], "WHERE AssignedTo = '".$this->uri->segment(3)."'")
                    )
                )
            );

            $data_series_tasks_completed = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Insights!$A$1:$B$2', NULL, 2),	//	2011
            );

            $x_axis_tasks_completed = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Insights!$A$1:$B$2', NULL, 2),	//	Q1 to Q4
            );

            $data_series_tasks_completed = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Insights!$A$2:$B$2', NULL, 2),
            );
            //	Build the dataseries
            $series_tasks_completed = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
                NULL,			                                        // plotGrouping (Pie charts don't have any grouping)
                range(0, count($data_series_tasks_completed)-1),					// plotOrder
                $data_series_tasks_completed,										// plotLabel
                $x_axis_tasks_completed,										// plotCategory
                $data_series_tasks_completed										// plotValues
            );
            //	Set up a layout object for the Pie chart
            $layout_tasks_completed = new PHPExcel_Chart_Layout();
            $layout_tasks_completed->setShowVal(TRUE);
            $layout_tasks_completed->setShowPercent(TRUE);
            //	Set the series in the plot area
            $plot_tasks_completed = new PHPExcel_Chart_PlotArea($layout_tasks_completed, array($series_tasks_completed));
            //	Set the chart legend
            $legend_tasks_completed = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
            $title_tasks_completed = new PHPExcel_Chart_Title('Tasks completed');
            //	Create the chart
            $chart_tasks_completed = new PHPExcel_Chart(
                'chart1',		// name
                $title_tasks_completed,		// title
                $legend_tasks_completed,		// legend
                $plot_tasks_completed,		// plotArea
                true,			// plotVisibleOnly
                0,				// displayBlanksAs
                NULL,			// xAxisLabel
                NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
            );
            //	Set the position where the chart should appear in the worksheet
            $chart_tasks_completed->setTopLeftPosition('A7');
            $chart_tasks_completed->setBottomRightPosition('F20');
            //	Add the chart to the worksheet
            $objWorksheet->addChart($chart_tasks_completed);
            /*
            =====================================
            ========[end TASKS COMPLETED]========
            =====================================
            */

            /*
            =====================================
            ==========[ISSUES COMPLETED]==========
            =====================================
            */

            $data_series_issues_completed = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Insights!$C$1:$D$1', NULL, 2),	//	2011
            );

            $x_axis_issues_completed = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Insights!$C$1:$D$1', NULL, 2),	//	Q1 to Q4
            );

            $data_series_issues_completed = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Insights!$C$2:$D$2', NULL, 2),
            );
            //	Build the dataseries
            $series_issues_completed = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
                NULL,			                                        // plotGrouping (Pie charts don't have any grouping)
                range(0, count($data_series_issues_completed)-1),					// plotOrder
                $data_series_issues_completed,										// plotLabel
                $x_axis_issues_completed,										// plotCategory
                $data_series_issues_completed										// plotValues
            );
            //	Set up a layout object for the Pie chart
            $layout_issues_completed = new PHPExcel_Chart_Layout();
            $layout_issues_completed->setShowVal(TRUE);
            $layout_issues_completed->setShowPercent(TRUE);
            //	Set the series in the plot area
            $plot_issues_completed = new PHPExcel_Chart_PlotArea($layout_issues_completed, array($series_issues_completed));
            //	Set the chart legend
            $legend_issues_completed = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
            $title_issues_completed = new PHPExcel_Chart_Title('Issues completed');
            //	Create the chart
            $chart_issues_completed = new PHPExcel_Chart(
                'chart2',		// name
                $title_issues_completed,		// title
                $legend_issues_completed,		// legend
                $plot_issues_completed,		// plotArea
                true,			// plotVisibleOnly
                0,				// displayBlanksAs
                NULL,			// xAxisLabel
                NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
            );
            //	Set the position where the chart should appear in the worksheet
            $chart_issues_completed->setTopLeftPosition('G7');
            $chart_issues_completed->setBottomRightPosition('L20');
            //	Add the chart to the worksheet
            $objWorksheet->addChart($chart_issues_completed);
            /*
            =====================================
            ========[end TASKS COMPLETED]========
            =====================================
            */
            
            /*
            =====================================
            ==========[MILESTONES COMPLETED]==========
            =====================================
            */

            $data_series_milestones_completed = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Insights!$   E$1:$F$1', NULL, 2),	//	2011
            );

            $x_axis_milestones_completed = array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Insights!$E$1:$F$1', NULL, 2),	//	Q1 to Q4
            );

            $data_series_milestones_completed = array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Insights!$E$2:$F$2', NULL, 2),
            );
            //	Build the dataseries
            $series_milestones_completed = new PHPExcel_Chart_DataSeries(
                PHPExcel_Chart_DataSeries::TYPE_PIECHART,				// plotType
                NULL,			                                        // plotGrouping (Pie charts don't have any grouping)
                range(0, count($data_series_milestones_completed)-1),					// plotOrder
                $data_series_milestones_completed,										// plotLabel
                $x_axis_milestones_completed,										// plotCategory
                $data_series_milestones_completed										// plotValues
            );
            //	Set up a layout object for the Pie chart
            $layout_milestones_completed = new PHPExcel_Chart_Layout();
            $layout_milestones_completed->setShowVal(TRUE);
            $layout_milestones_completed->setShowPercent(TRUE);
            //	Set the series in the plot area
            $plot_milestones_completed = new PHPExcel_Chart_PlotArea($layout_milestones_completed, array($series_milestones_completed));
            //	Set the chart legend
            $legend_milestones_completed = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
            $title_milestones_completed = new PHPExcel_Chart_Title('Milestones completed');
            //	Create the chart
            $chart_milestones_completed = new PHPExcel_Chart(
                'chart2',		// name
                $title_milestones_completed,		// title
                $legend_milestones_completed,		// legend
                $plot_milestones_completed,		// plotArea
                true,			// plotVisibleOnly
                0,				// displayBlanksAs
                NULL,			// xAxisLabel
                NULL			// yAxisLabel		- Pie charts don't have a Y-Axis
            );
            //	Set the position where the chart should appear in the worksheet
            $chart_milestones_completed->setTopLeftPosition('M7');
            $chart_milestones_completed->setBottomRightPosition('R20');
            //	Add the chart to the worksheet
            $objWorksheet->addChart($chart_milestones_completed);
            /*
            =====================================
            ========[end MILESTONES COMPLETED]========
            =====================================
            */

            $objWriter = new PHPExcel_Writer_Excel2007($excel);
            $objWriter->setIncludeCharts(TRUE);
            $objWriter->save("ceva.xlsx");
            
                        // We'll be outputting an excel file
            header('Content-type: application/vnd.ms-excel');

            // It will be called file.xls
            header('Content-Disposition: attachment; filename="'.get_cached_info('FirstName', $this->config->config['tables']['employees'], "ID", $u_id) . '_' . get_cached_info('LastName', $this->config->config['tables']['employees'], "ID", $u_id).'_'.date("d-m-Y", time()).'.xls"');

            // Write file to the browser
            $objWriter->save('php://output');
            flash_redirect("success", "Excel downloaded successfully", base_url("hr/employee/".$u_id));

        }

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