<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Finance
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Finance extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Finance_model'); 
        $this->load->helper(array('url'));

        if($this->session->userdata('logged_in') == null) {
            redirect(base_url("login"));
        }

        if (!is_cache_valid(md5('finance'), 60)){
            $this->db->cache_delete('finance');
        }
    }

    public function index()
    {
        $data['months']             =   "'January','February','March','April','May','June','July','August','September','October','November','December'";
        $data['month_profit']       =   $this->Finance_model->get_profit('month')[0]->JSON;
        $data['product_profit']     =   $this->Finance_model->get_profit('product')[0]->JSON;
        $data['employee_profit']    =   $this->Finance_model->get_profit('employee')[0]->JSON;
        $data['expenses']           =   $this->Finance_model->retrieve_expenses("JSON")[0]->JSON;
        
        if($data['month_profit']   != '{"january":0,"february":0,"march":0,"april":0,"may":0,"june":0,"july":0,"august":0,"september":0,"october":0,"november":0,"december":0}') {
            /* 
            * 
            * MONTH PROFIT 
            * decode json and format the text in order to be used in finance doughnut
            *
            */
            $month_decode       = json_decode($data['month_profit']);

            /* >       LABELS */
            $month_profit_labels  =   "";

            foreach ($month_decode as $key => $value) {
                $month_profit_labels .= "'" . $key . "',";
            }
            $data['month_profit_labels']  = rtrim($month_profit_labels, ',');


            /* >       DATA */
            $month_profit_data        =   "";

            foreach($month_decode as $key => $value) {
                $month_profit_data .= $value . ",";
            }
            $data['month_profit_data']    = rtrim($month_profit_data, ',');
            /******************/
        } else $data['month_profit'] = 0;

        if($data['product_profit'] != '{"product": {"name": "null","income": "0"}}') {
            /* 
            * 
            * PRODUCT PROFIT 
            * decode json and format the text in order to be used in finance doughnut
            *
            */
            $product_decode       = json_decode($data['product_profit']);

            /* >       LABELS */
            $product_profit_labels  =   "";

            foreach($product_decode as $row) {
                $product_profit_labels .= "'" . $row->name . "',";
            }
            $data['product_profit_labels']  = rtrim($product_profit_labels, ',');

            /* >       DATA */
            $product_profit_data    =   "";

            foreach($product_decode as $row) {
                $product_profit_data .= $row->income . ",";
            }
            $data['product_profit_data']      = rtrim($product_profit_data, ',');
            /******************/
        } else $data['product_profit'] = 0;

        if($data['employee_profit'] != '{"employee": {"id": "null","name": "null","profit": "null"}}') {
            /* 
            * 
            * EMPLOYEE PROFIT 
            * decode json and format the text in order to be used in finance doughnut
            *
            */
            $employee_decode        = json_decode($data['employee_profit']);

            /* >       LABELS */
            $employee_profit_labels =   "";

            foreach($employee_decode as $row) {

                $employee_profit_labels .= "'" . $row->employee->name . "',";
            }
            $data['employee_profit_labels']  = rtrim($employee_profit_labels, ',');

            /* >       DATA */
            $employee_profit_data   =   "";

            foreach($employee_decode as $row) {
                $employee_profit_data .= $row->employee->profit . ",";
            }
            $data['employee_profit_data']      = rtrim($employee_profit_data, ',');
            /******************/
        } else $data['employee_profit'] = 0;
        
        if($data['expenses'] != ' {"items":[{}]} ') {
            /* 
            * 
            * EXPENSES
            * decode json and format the text in order to be used in finance doughnut
            *
            */
            $expenses_decode        = json_decode($data['expenses']);

            
            /* >       LABELS */
            $expenses_labels =   "";
            
            for($i = 0; $i < sizeof($expenses_decode->items); $i++) {
                $expenses_labels .= "'" . $expenses_decode->items[$i]->reason . "',";
            }
            
            $data['expenses_labels']  = rtrim($expenses_labels, ',');

            /* >       DATA */
            $expenses_data   =   "";

            for($i = 0; $i < sizeof($expenses_decode->items); $i++) {
                $expenses_data .= $expenses_decode->items[$i]->expense . ",";
            }
            $data['expenses_data']      = rtrim($expenses_data, ',');
            /******************/
        } else $data['employee_profit'] = 0;

        $data["main_content"] = 'dashboard/finance/main_view';
        $this->load->view('includes/template.php', $data);
    }
    
    public function add_expenses() {
        $json       =   $this->input->post("json");
        $verify     =   '{"invalid":[' . $json . ']}';
        
        $ob = json_decode($verify);
        
        if($ob === null) {
            $this->output->set_header('HTTP/1.0 403 Forbidden');
            die("Something went wrong.");
        }
        
        $this->db->cache_delete('finance', 'index');
        if($finance = $this->Finance_model->retrieve_expenses(array("JSON"))) {
            $post_data = '{"items":['.$json.']}';
        } else {
            $this->output->set_header('HTTP/1.0 403 Forbidden');
            die("Something went wrongeess.");
        }
        
        $update_data       =   array("JSON"     =>  $post_data);
        
        if($this->Finance_model->add_expenses($update_data)) {
            $this->db->cache_delete('finance', 'index');
            $this->db->cache_delete('finance', 'add_expenses');
            die(json_encode(array(
                'csrfHash' => $this->security->get_csrf_hash()
            )));
        } else {
            $this->output->set_header('HTTP/1.0 403 Forbidden');
            die("Something went wrong.");
        }
    }

    public function accounting() {
        
        $temp_expense               =   ($this->Finance_model->retrieve_expenses("JSON")[0]->JSON);

        
        $data['expenses_court']     =   "";
        if($temp_expense != '{"items":[{}]}') {
            $i                          =   0;
            foreach(json_decode($temp_expense)->items as $row) {
                $i++;
                $data['expenses_court'] .=  "<tr data-specificid='".$i."'><td>".number_format($row->expense)."</td><td>".$row->reason."</td><td><span class='icon is-small is-left' onclick='remove_expense(".$i.")' style='margin-left: 15px;font-size: 15px;background: #d5d5d5;padding: 15px;border-radius: 100%;color: #747474;'><i class='fas fa-trash'></i></span></td></tr>";
            }

            $data['expenses_court']      =   htmlentities($data['expenses_court']);
        } 
        
      
        $data["main_content"] = 'dashboard/finance/accounting_view';
        $this->load->view('includes/template.php', $data);
    }

    public function _remap($method,$args)
    {
        (method_exists($this, $method)) ? ($this->$method($args)) : ($this->index($method,$args));
    }

}
?>