<?php 

if(!defined('BASEPATH')) exit('No direct access allowed');
 
/**
* Insightss
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Insights extends CI_Controller
{

    public function __construct() {
        parent::__construct();
    }

    function index() {
        $data["main_content"] = 'dashboard/insights';
        $this->load->view('includes/template.php', $data);
    }

}