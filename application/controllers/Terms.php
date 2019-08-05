<?php 

if(!defined('BASEPATH')) exit('No direct access allowed');
 
/**
* Terms
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Terms extends CI_Controller
{

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $data["terms"]        = $this->lang->line('main_termstext');
        
        $data["main_content"] = 'terms_view';
        $this->load->view('includes/template.php', $data);
    }

}