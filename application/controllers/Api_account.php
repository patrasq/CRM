<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Api_account
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Api_account extends CI_Controller {
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Dashboard_model'); 
        $this->load->helper(array('url'));
        $this->load->library(array('pagination'));
        
        if($this->session->userdata('logged_in') == null) {
            redirect(base_url("login"));
        }
        
        if (!is_cache_valid(md5('api_account'), 60)){
            $this->db->cache_delete('api_account');
        }
    }
 
    public function index()
    {
        
        $data['your_news']                  = get_geonews();
        $data['activity_news']              = get_activitynews(get_info("Activity", "businesses", "Owner", $this->session->userdata("logged_in")["ID"])); // WORLD NATION BUSINESS TECHNOLOGY ENTERTAINMENT SPORTS SCIENCE HEALTH
        $data['number_employees']           = countTable("hr_employees");
        
        
        $data["main_content"] = 'dashboard/dashboard_view';
        $this->load->view('includes/template.php', $data);
    }
    
    public function _remap($method,$args)
    {
        (method_exists($this, $method)) ? $this->$method($args) : $this->index($method,$args);
    }
 
    
    
    function logout()
    {
        $this->session->unset_userdata('logged_in');
        session_destroy();
        redirect('', 'refresh');
    }
    
    public function recover() {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('email',      'Email',    'trim|required|valid_email|xss_clean');
        
        $data["main_content"] = 'login/recover_view';
        $this->load->view('includes/template.php', $data);
    }
    
    public function change_password() {
        if(post_parameter_set($this->input->post('oldpass')) && post_parameter_set($this->input->post('newpass'))) {
            $salt               = bin2hex(openssl_random_pseudo_bytes(10));
            $encoded_password   = hash("sha256",  "mOGhbVyt!4JkL" . implode($salt, str_split($this->input->post('newpass'), floor(strlen($this->input->post('newpass'))/2))) . $salt);
            $generated_hash     = bin2hex(openssl_random_pseudo_bytes(10) . md5($encoded_password . $salt));
            
            $this->User_model->update_password($generated_hash, $salt);
        } else {
            flash_redirect('error', 'Old password or new password are not set.', $_SERVER['HTTP_REFERER']);
        }
    }
    
    public function logout() {
        $this->session->sess_destroy();
        flash_redirect('success', 'You have signed out.', base_url());
    }
}
?>