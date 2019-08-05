<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Dashboard
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model'); 
        $this->load->driver('cache');
        $this->load->helper(array('url'));

        if($this->session->userdata('logged_in') == null) {
            redirect(base_url("login"));
        }
    }

    public function index()
    {
        switch($this->session->userdata("logged_in")["Type"]) {
            case 1:
                $data["card_one_data"]      =   "1";
                $data["card_one_label"]     =   "Label";
            break;
            case 2:
                $data["card_one_data"]      =   countTable($this->config->config["tables"]["employees"]);
                $data["card_one_label"]     =   "Employees";
                
                $data["card_two_data"]      =   countTable($this->config->config["tables"]["projects"], "WHERE `Status` = 0");
                $data["card_two_label"]     =   "Active Projects";
            break;
        }
        
        $data["main_content"]       = 'dashboard/dashboard_view';
        $this->load->view('includes/template.php', $data);
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

    public function recover() {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email',      'Email',    'trim|required|valid_email|xss_clean');

        $data["main_content"] = 'login/recover_view';
        $this->load->view('includes/template.php', $data);
    }

    public function projects() {
        // DATA
        $data["project"]                    = $this->Dashboard_model->get_projects($this->session->userdata('logged_in')["ID"]);

        $data["main_content"]   = 'dashboard/projects_view';
        $this->load->view('includes/template.php', $data);
    }

    public function addproject() {
        // TRANSLATE
        $data["main_content"]   = 'dashboard/add_project';
        $this->load->view('includes/template.php', $data);
    }

    public function logins() {

        $this->load->library("pagination");

        $params = array();
        $limit_per_page = 10;
        $start_index = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $total_records = $this->Dashboard_model->get_total_logins($this->session->userdata('logged_in')["ID"]);

        if ($total_records > 0) 
        {
            // get current page records
            $data["logins"]                 = $this->Dashboard_model->get_logins($this->session->userdata('logged_in')["ID"], $limit_per_page);

            $config['base_url']             = base_url() . 'dashboard/logins/';
            $config['total_rows']           = $total_records;
            $config['per_page']             = $limit_per_page;
            $config['uri_segment']          = 3;


            $config['full_tag_open']        = '<br><nav class="pagination is-centered" role="navigation" aria-label="pagination"><ul class="pagination-list">';
            $config['full_tag_close']       = '</ul></nav>';

            $config['first_link']           = 'First Page';
            $config['first_tag_open']       = '<li>';
            $config['first_tag_close']      = '</li>';

            $config['last_link']            = 'Last Page';
            $config['last_tag_open']        = '<li>';
            $config['last_tag_close']       = '</li>';

            $config['next_link']            = 'Next';
            $config['next_tag_open']        = '';
            $config['next_tag_close']       = '';

            $config['prev_link']            = 'Previous';
            $config['prev_tag_open']        = '';
            $config['prev_tag_close']       = '';

            $config['attributes'] = array('class' => 'pagination-link');

            $config['cur_tag_open'] = '<li><a class="pagination-link is-current" aria-current="page">';
            $config['cur_tag_close'] = '</a></li>';

            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);

            $data['links'] = $this->pagination->create_links();
        } else $data["logins"]  = false;

        $data["main_content"]   = 'dashboard/logins_view';
        $this->load->view('includes/template.php', $data);
    }


    public function settings() {

        $data['my_business_id']             = get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]);
        $data['my_business_name']           = get_cached_info("Name", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]);

        $data["main_content"]   = 'dashboard/settings_view';
        $this->load->view('includes/template.php', $data);
    }

    public function settings_account() {

        $data['first_name']                 = 'ANDREI';
        $data['last_name']                  = 'PATRASQ';
        $data['email']                      = get_cached_info("EMail", $this->config->config['tables']['accounts'], "ID", $this->session->userdata("logged_in")["ID"]);

        $data["main_content"]   = 'dashboard/settings/account_view';
        $this->load->view('includes/template.php', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        flash_redirect('success', 'You have signed out.', base_url());
    }
}
?>