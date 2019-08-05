<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Main
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Main extends CI_Controller {
    
    public function __construct() 
    {       
        parent:: __construct();
    }
    
    public function index()
    {
        ($this->session->userdata('logged_in') !== null) ? redirect(base_url("dashboard")) : redirect(base_url("login"));
        
    }
}
