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

                $data["card_two_data"]      =   countTable($this->config->config["tables"]["projects"], "WHERE `Status` = 0 AND AssignedTo1 = " . $this->session->userdata("logged_in")["ID"] . " OR AssignedTo2 = " . $this->session->userdata("logged_in")["ID"] . " OR AssignedTo3 = " . $this->session->userdata("logged_in")["ID"] . " OR AssignedTo4 = " . $this->session->userdata("logged_in")["ID"] . " OR AssignedTo5 = " . $this->session->userdata("logged_in")["ID"]);
                $data["card_two_label"]     =   "Your active Projects";

                $data["task"]               =   $this->Dashboard_model->get_tasks();
                break;
            case 2:
                $data["card_one_data"]      =   countTable($this->config->config["tables"]["employees"]);
                $data["card_one_label"]     =   "Employees";

                $data["card_two_data"]      =   countTable($this->config->config["tables"]["projects"], "WHERE `Status` = 0");
                $data["card_two_label"]     =   "Active Projects";
                break;
        }

        $var1                               =   $this->Dashboard_model->get_tasks($this->session->userdata("logged_in")["ID"]);

        $tasks_return_months                =   array();

        $tasks_dates = array();

        foreach($var1 as $row) {
            if(isset($row["CompletedOn"]) && $row["CompletedOn"] != null) {
                $tasks_dates[]    =   $row["CompletedOn"];
            } elseif(isset($row["CompleteDate"]) && $row["CompleteDate"] != null) {
                $tasks_dates[]    =   $row["CompleteDate"];
            }
        }
        
        for($i = 0; $i < sizeof($tasks_dates); $i++) {
            for($increment_month = 0; $increment_month < 12; $increment_month++) {
                $php_date = getdate(strtotime($tasks_dates[$i]));
                if(intval(date("m", strtotime($tasks_dates[$i]))) == $increment_month)
                    @$tasks_return_months[$increment_month] = $tasks_return_months[$increment_month]+1;
            }
        }

        $months = json_decode('{"january":0,"february":0,"march":0,"april":0,"may":0,"june":0,"july":0,"august":0,"september":0,"october":0,"november":0,"december":0}', true);

        $tasks_months = $months;

        for($i = 1; $i <= 12; $i++) {
            if(isset($tasks_return_months[$i]))
                $tasks_months[get_month_name($i-1)] = $tasks_return_months[$i];
        }

        $tasks_js =   "[";
        foreach($tasks_months as $key => $value) {

            $tasks_js .= "'" . $value . "',";
        }
        $tasks_js = rtrim( $tasks_js, "," );
        $tasks_js .= "]";

        //die(print_r($tasks_return_months));
        $data["tasks_completed_monthly"]      = $tasks_js;

        $data["gradients"]                  =   array(
            "linear-gradient(45deg,#ffa836,#ffcf00 100%)",
            "linear-gradient(45deg,#3690ff,#00d2ff 100%)",
            "linear-gradient(45deg,#de36ff,#9600ff 100%)",
            "linear-gradient(45deg,#36ff38,#0bd71c 100%)"
        );
        $data["project"]                    =   $this->Dashboard_model->get_projects();

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

    public function add_user() {
        if($this->session->userdata("logged_in")["Type"] !== 2) {

            $data["department"]                 = $this->Dashboard_model->get_departments();
            $data["main_content"]               = 'dashboard/add_account';
            $this->load->view('includes/template.php', $data);
        }
    }

    public function add_user_2() {
        if($this->session->userdata("logged_in")["Type"] !== 2) {
            if(
                $this->input->post("firstname")     !== null  &&  post_parameter_set($this->input->post("firstname")) &&
                $this->input->post("lastname")      !== null  &&  post_parameter_set($this->input->post("lastname"))  &&
                $this->input->post("department")    !== null  &&  post_parameter_set($this->input->post("department"))&&
                $this->input->post("position")      !== null  &&  post_parameter_set($this->input->post("position"))  &&
                $this->input->post("gender")        !== null  &&  post_parameter_set($this->input->post("gender"))    &&
                $this->input->post("phone")         !== null  &&  post_parameter_set($this->input->post("phone"))     &&
                $this->input->post("address")       !== null  &&  post_parameter_set($this->input->post("address"))   &&
                $this->input->post("birthdate")     !== null  &&  post_parameter_set($this->input->post("birthdate")) &&
                $this->input->post("hiredsince")    !== null  &&  post_parameter_set($this->input->post("hiredsince")) &&
                $this->input->post("email")         !== null  &&  post_parameter_set($this->input->post('email'))     && 
                $this->input->post("type")          !== null  &&  post_parameter_set($this->input->post('type'))     
            ) {

                $this->load->model('User_model');
                $this->load->model('HR_model');

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
                $email          =   $this->input->post('email');
                $type           =   (int)$this->input->post('type');

                if(!exists($department, $this->config->config['tables']['departments'], "ID")) flash_redirect("error", "Invalid department.", base_url("hr/employees")); // Check if the submitted department exists
                if(!in_array($gender, array("Male", "Female"))) flash_redirect("error", "Invalid gender.", base_url("hr/employees")); // Check if the submitted gender is valid (personally I recognize only men and women)
                if(!in_array($type, array("0", "1"))) flash_redirect("error", "Invalid type.", base_url("hr/employees")); 

                if(strlen($email)) {
                    if(!exists($email, $this->config->config['tables']['accounts'], "EMail")) {

                        $remoteIp = $this->input->ip_address();

                        $salt               = bin2hex(openssl_random_pseudo_bytes(10));
                        $password           = bin2hex(openssl_random_pseudo_bytes(6));
                        $encoded_password   = hash("sha256",  "mOGhbVyt!4JkL" . implode($salt, str_split($password, floor(strlen($password)/2))) . $salt);
                        $generated_hash     = bin2hex(openssl_random_pseudo_bytes(10) . md5($encoded_password . $salt));

                        $post_data          = array(
                            "EMail"         => $email,
                            "Password"      => $encoded_password,
                            "Salt"          => $salt,
                            "IP"            => $this->input->ip_address(),
                            "Type"          => $type
                        );

                        $reg_data           = array(
                            "EMail"         => $email,
                            "HashGenerated" => $generated_hash,
                            "Timestamp"     => time(),
                            "IP"            => $this->input->ip_address()
                        );

                        if($this->User_model->insert_user($post_data, $reg_data)) {
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

                                $this->load->library('email');

                                $config['protocol']     = 'smtp';
                                $config['smtp_host']    = 'ssl://smtp.gmail.com';
                                $config['smtp_port']    = '465';
                                $config['smtp_timeout'] = '7';
                                $config['smtp_user']    = '';
                                $config['smtp_pass']    = '';
                                $config['charset']      = 'utf-8';
                                $config['newline']      = "\r\n";
                                $config['mailtype']     = 'html';
                                $config['validation']   = TRUE;  

                                $this->email->initialize($config);

                                $this->email->from('mailer@LeMonkey.com', 'LeMonkey');
                                $this->email->to($email);

                                $this->email->subject('\xF0\x9F\x94\xA5 You have been added to LeMonkey');

                                $message_to_show = "You have been added to LeMonkey. Your credentials are the following:<br><b>EMail:</b> $email<br><b>Password:</b> $password";

                                $this->email->send();
                                $flash_to_show = ($this->session->userdata('language') == "english") ? "An email containing further information has been sent to that email address. Thanks! \xF0\x9F\x98\x81" : "Un mail care conține informațiile necesare inregistrării a fost trimis către adresa indicată. Mulțumim! \xF0\x9F\x98\x81";

                                flash_redirect("success", "Employee added successfully.", base_url("dashboard"));
                            } else flash_redirect("error", "Something wrong happened.", base_url("hr/employees"));
                        } 
                    }
                } else flash_redirect('error', 'Something went wrong.', base_url("dashboard"));
            } else flash_redirect('error', 'Something went wrong.', base_url("dashboard"));
        }
    }

    public function settings() {

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
    
    public function register_push() {
        $user_id    =   $this->input->post('user_id');
        return $this->Dashboard_model->push_usersignal($user_id, $this->session->userdata("logged_in")["ID"]) ? true : false;
    }
}
?>