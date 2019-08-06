<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Api_account
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Settings extends CI_Controller {

    /* MUST HAVE */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model'); 
        $this->load->helper(array('url'));

        if($this->session->userdata('logged_in') == null) {
            redirect(base_url("login"));
        }
    }
    public function _remap($method,$args)
    {
        if (method_exists($this, $method)) $this->$method($args); 
        else $this->index($method,$args);
    }
    /* END OF - MUST HAVE */

    public function index()
    {
        redirect(base_url("dashboard"));    
    }

    public function check_tw($val) {
        if($val) {
            $tw_regex = '/^(https?:\/\/)?(www\.)?twitter.com\/[a-zA-Z0-9(\.\?)?]/';

            if(preg_match($tw_regex, $val) == 0) {
                $this->form_validation->set_message('check_tw', 'Your Twitter link does not appear to be valid');
                return FALSE;
            } else return TRUE;
        }
    }

    public function check_ig($val) {
        if($val) {
            $ig_regex = '/^(https?:\/\/)?(www\.)?instagram.com\/[a-zA-Z0-9(\.\?)?]/';

            if(preg_match($ig_regex, $val) == 0) {
                $this->form_validation->set_message('check_ig', 'Your Instagram link does not appear to be valid');
                return FALSE;
            } else return TRUE;
        }
    }

    public function check_fb($val) {
        if($val) {
            $fb_regex = '/^(https?:\/\/)?(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/';

            if(preg_match($fb_regex, $val) == 0) {
                $this->form_validation->set_message('check_fb', 'Your Facebook link does not appear to be valid');
                return FALSE;
            } else return TRUE;
        }
    }

    public function check_website($val) {
        if($val) {
            if(!check_if_alive($val)) {
                $this->form_validation->set_message('check_website', 'Your website does not appear to be working');
                return FALSE;
            } else return TRUE;
        }
    }

    public function check_hashtag($val) {
        if($val) {
            preg_match_all("/(#\w+)/", $val, $matches);
            if(!$matches) {
                $this->form_validation->set_message('check_hashtag', 'Your hashtag does not appear to be really a hashtag...');
            } else return TRUE;
        }
    }

    public function account()
    {       
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name',                 'Company name',            'trim|required|xss_clean');
        $this->form_validation->set_rules('country',              'Register country',        'trim|required|xss_clean');
        $this->form_validation->set_rules('activity',             'Activity field',          'trim|required|xss_clean');
        $this->form_validation->set_rules('description',          'Description',             'trim|required|xss_clean|min_length[15]');
       // $this->form_validation->set_rules('website',              'Website',                 'callback_check_website');;

        //$this->form_validation->set_rules('facebook_hashtag',     'Facebook hashtag',        'callback_check_fbhashtag');

        $this->form_validation->set_error_delimiters('<div class="notification is-danger"><button class="delete"></button>', '</div>');

        if($this->form_validation->run() == FALSE)
        {
            $business                           = get_cached_info("ID", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata('logged_in')["ID"]);

            /* $business_* */
            $data['name']              = get_cached_info("Name",               $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]);
            $data['gender']           = get_cached_info("Country",            $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]);
            $data['business_activity']          = get_cached_info("Activity",           $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]);
            $data['business_description']       = get_cached_info("Description",        $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]);
            $data['business_website']           = get_cached_info("Website",            $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"]);

            $data['business_facebook']          = get_cached_info("Facebook",           $this->config->config['tables']['businesses_socialmedia'], "BusinessID", $this->session->userdata("logged_in")["ID"]);
            $data['business_instagram']         = get_cached_info("Instagram",          $this->config->config['tables']['businesses_socialmedia'], "BusinessID", $this->session->userdata("logged_in")["ID"]);
            $data['business_twitter']           = get_cached_info("Twitter",            $this->config->config['tables']['businesses_socialmedia'], "BusinessID", $this->session->userdata("logged_in")["ID"]); 
            $data['facebook_hashtag']           = get_cached_info("FacebookHashtag",    $this->config->config['tables']['businesses_socialmedia'], "BusinessID", $this->session->userdata("logged_in")["ID"]);
            $data['instagram_hashtag']          = get_cached_info("InstagramHashtag",   $this->config->config['tables']['businesses_socialmedia'], "BusinessID", $this->session->userdata("logged_in")["ID"]);
            $data['twitter_hashtag']            = get_cached_info("TwitterHashtag",     $this->config->config['tables']['businesses_socialmedia'], "BusinessID", $this->session->userdata("logged_in")["ID"]);

            $data["main_content"]               = 'dashboard/settings/business_view';
            $this->load->view('includes/template.php', $data);
        }
        else
        {
            $business_updates       = array();

            //SETUP BUSSINESS
            if($this->input->post("name") !== null && post_parameter_set($this->input->post("name"))) {
                $name        = html_purify($this->input->post("name")); // Sanitize this input
                if($name    != get_cached_info("Name", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"])) { // If is a new business name
                    $business_updates["Name"]  = $name; // Insert member to array("$to_update") in order to update the database
                }
            }
            if($this->input->post("country") !== null && post_parameter_set($this->input->post("country"))) {
                $country        = in_array($this->input->post("country"), $countries) ? html_purify($this->input->post("country")) : flash_redirect('error', 'Something went wrong8...', base_url("account/business")); 
                if($country    != get_cached_info("Country", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"])) { // If is a new country
                    $business_updates["Country"]  = $country; // Insert member to array("$to_update") in order to update the database
                }
            }
            if($this->input->post("activity") !== null && post_parameter_set($this->input->post("activity"))) {
                $activity        = in_array($this->input->post("activity"), $activities) ? html_purify($this->input->post("activity")) : flash_redirect('error', 'Something went wrong7...', base_url("account/business")); 
                if($activity    != get_cached_info("Activity", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"])) { // If is a new activity
                    $business_updates["Activity"]  = $activity; // Insert member to array("$to_update") in order to update the database
                }
            }
            if($this->input->post("description") !== null && post_parameter_set($this->input->post("description")))  {
                $description = html_purify($this->input->post("description"));
                if($description    != get_cached_info("Description", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"])) { // If is a new business name
                    $business_updates["Description"]  = $description; // Insert member to array("$to_update") in order to update the database
                }
            }
            if($this->input->post("website") !== null && post_parameter_set($this->input->post("website"))) {
                $website        = check_if_alive($this->input->post("website")) ? $website : "NULL";
                if($website    != get_cached_info("Website", $this->config->config['tables']['businesses'], "Owner", $this->session->userdata("logged_in")["ID"])) { // If is a new website
                    $socialmedia_updates["Website"]  = $this->input->post("website"); // Insert member to array("$to_update") in order to update the database
                }
            }

            $update_business        = $this->Business_model->update_business($business_updates, $this->session->userdata("logged_in")["ID"]);
            if($update_business) {
                $this->db->cache_delete("dashboard",    "settings");
                $this->db->cache_delete("settings",     "business");
                flash_redirect('success', 'You have successfully updated your business\' information!', base_url("dashboard/settings/business"));
            } else flash_redirect('error', 'Something went wrong6...', base_url("account/settings/business"));
        }

    }

}
?>