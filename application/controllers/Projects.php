<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Projects
* 
* 
* @package    LeMonkey
* @subpackage Controller
*/

class Projects extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Projects_model'); 
        $this->load->helper(array('url'));
        $this->load->driver('cache');

        if($this->session->userdata('logged_in') == null) {
            redirect(base_url("login"));
        }

        if (!is_cache_valid(md5('projects'), 60*5)){
            $this->db->cache_delete('projects');
        }
    }

    public function view()
    {

        $id                                  = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : flash_redirect("error", "Invalid project.", base_url("projects"));
        if(!countTable($this->config->config["tables"]["projects"], "WHERE `ID` = " . $id)) flash_redirect("error", "Invalid project.", base_url("projects"));

        $data["project_id"]                  = $id; 
        $data["project_name"]                = $this->Projects_model->get_info("Name", $id, 1)[0]["Name"]; // selector, id, cache?
        $data["project_deadline"]            = $this->Projects_model->get_info("Deadline", $id, 1)[0]["Deadline"]; // selector, id, cache?
        $data["project_started"]             = $this->Projects_model->get_info("Started", $id, 1)[0]["Started"]; // selector, id, cache?

        $data["completed_milestones"]        = countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy > 0 AND ProjectID = '".$id."'");
        $data["max_milestones"]              = countTable($this->config->config["tables"]["milestones"], "WHERE ProjectID = '".$id."'");

        $data["completed_issues"]            = countTable($this->config->config["tables"]["issues"], "WHERE CompletedBy > 0 AND ProjectID = '".$id."'");
        $data["max_issues"]                  = countTable($this->config->config["tables"]["issues"], "WHERE ProjectID = '".$id."'");

        $data["milestone"]                   = $this->Projects_model->get_milestones($id);
        $data["issue"]                       = $this->Projects_model->get_issues($id);

        
        $data["team_member"]                 = $this->Projects_model->get_team(array("AssignedTo1", "AssignedTo2", "AssignedTo3", "AssignedTo4", "AssignedTo5"), $id);
        $data["progress_color"]              = "is-danger";

        $data["main_content"]                = 'dashboard/projects/view_view';
        $this->load->view('includes/template.php', $data);
    }

    public function index()
    {
        $data["personal_project"]            = $this->Projects_model->get_projects(array("ID", "Name", "Deadline", "Status"),               $this->session->userdata("logged_in")["ID"]);
        $data["all_projects"]                = $this->Projects_model->get_projects(array("ID", "Name", "Deadline", "Status", "Supervizor"), $this->session->userdata("logged_in")["ID"]);

        $data["main_content"]                = 'dashboard/projects/main_view';
        $this->load->view('includes/template.php', $data);
    }


    public function add_project(){
        if(
            $this->input->post("json")                  !== null &&
            $this->input->post("projectname")           !== null &&
            $this->input->post("projectdescription")    !== null &&
            $this->input->post("projectdeadline")       !== null 
        )
        {
            
            $name           =   html_purify($this->input->post("projectname"));
            $description    =   html_purify($this->input->post("projectdescription"));
            $deadline       =   html_purify($this->input->post("projectdeadline"));
            $json           =   $this->input->post("json");
            $verify         =   '{"invalid":[' . $json . ']}';

            if(!preg_match("/^([0-2][0-9]|(3)[0-1])(\-)(((0)[0-9])|((1)[0-2]))(\-)\d{4}$/", $deadline)) flash_redirect("error", "Something went wrong", base_url("projects/"));
            
            $ob = json_decode($verify);

            if($ob === null) {
                $this->output->set_header('HTTP/1.0 403 Forbidden');
                die("Something went wrong.");
            }
            
            $data       =   array(
                "Name"          =>  $name,
                "Description"   =>  $description,
                "Deadline"      =>  $deadline,
                "Started"       =>  date("d-m-Y", time()),
                "AssignedTo1"   =>  NULL,
                "AssignedTo2"   =>  NULL,
                "AssignedTo3"   =>  NULL,
                "AssignedTo4"   =>  NULL,
                "AssignedTo5"   =>  NULL,
                "Supervizor"    =>  $this->session->userdata("logged_in")["ID"]
            );

            $i = 0;
            foreach($ob->invalid as $row) {
                $data2[$i]      =   array(
                    "Name"          =>  $row->milestone,
                    "AssignedTo"    =>  NULL,
                    "CompletedBy"   =>  NULL,
                    "CompleteDate"  =>  NULL,
                    "CreateDate"    =>  date("d-m-Y", time())
                );
                
                $i++;
            }
            if($return_id = $this->Projects_model->add_project($data, $data2)) {
                $this->db->cache_delete('projects', 'view');
                $this->db->cache_delete('projects', 'index');
                die(json_encode(array(
                    'csrfHash' => $this->security->get_csrf_hash(),
                    'returnId' => $return_id
                )));
            }
        } else {
            $this->output->set_header('HTTP/1.0 403 Forbidden');
            die("Something went wrong.");
        }

    }

    /* MILESTONES */
    public function add_milestone() {
        if($this->input->post("name") !== null && $this->uri->segment(3) != null && $this->session->userdata("logged_in")["Type"] == 2) {
            $name       =       html_purify($this->input->post("name"));
            $id         =       (int)$this->uri->segment(3);

            if(!countTable($this->config->config["tables"]["milestones"], "WHERE `ID` = '".$id."'")) flash_redirect("error", "Something went wrong...", base_url("projects/view/" . $id));

            $data       =       array(
                "ProjectID"  =>      $id,
                "Name"       =>      $name,
                "CreateDate" =>      date("d-m-Y H:i:s", time()),
                "AssignedTo" =>      NULL,
                "CompletedBy"=>      NULL
            );

            ($this->Projects_model->add_milestone($data, $this->session->userdata("logged_in")["ID"])) ? flash_redirect("success", "Milestone added succesfully", base_url("projects/view/" . $id)) : flash_redirect("error", "Something went wrong...", base_url("projects/view/" . $id));
        } else flash_redirect("error", "Something went wrong...", base_url("projects/view/" . $id));
    }
    
    public function assign_milestone() {
        if($this->uri->segment(3) != null) {
            $id         =       (int)$this->uri->segment(3);

            if(!countTable($this->config->config["tables"]["milestones"], "WHERE `ID` = '".$id."'")) flash_redirect("error", "Something went wrong3...", base_url("projects/view/" . $id));

            if($this->session->userdata("logged_in")["Type"] == 1) {
                if(get_cached_info_null("AssignedTo", $this->config->config["tables"]["milestones"], "ID", $id) == null) {
                    $data   =   array(
                        "AssignedTo"    =>  $this->session->userdata("logged_in")["ID"]
                    );
                    $this->Projects_model->assign_milestone($data, $id) ? flash_redirect("success", "Milestone assigned successfully.", $_SERVER["HTTP_REFERER"]) : flash_redirect("error", "Something went wrong2...", $_SERVER["HTTP_REFERER"]);
                } elseif(get_cached_info_null("AssignedTo", $this->config->config["tables"]["milestones"], "ID", $id) == $this->session->userdata("logged_in")["ID"]) {
                    $data   =   array(
                        "CompletedBy"    =>  $this->session->userdata("logged_in")["ID"],
                        "CompleteDate"   =>  date("d-m-Y H:i:s", time()),
                        "AssignedTo"     =>  NULL
                    );
                    $this->Projects_model->complete_milestone($data, $id) ? flash_redirect("success", "Milestone completed successfully.", $_SERVER["HTTP_REFERER"]) : flash_redirect("error", "Something went wrong2...", $_SERVER["HTTP_REFERER"]);
                } else flash_redirect("error", "Something went wrong...", base_url("projects"));
            }
            /*elseif($this->session->userdata("logged_in")["Type"] == 2) {
                if(get_cached_info("AssignedTo", $this->config->config["tables"]["milestones"], "ID", $id) == null) {
                    $this->Projects_model->assign_milestone();
                } else if(get_cached_info("AssignedTo", $this->config->config["tables"]["milestones"], "ID", $id) == $this->session->userdata("logged_in")["ID"]) {
                    $this->Projects_model->complete_milestone();
                } else flash_redirect("error", "Something went wrong...", base_url("projects"));
            }*/

        }
    }
    /* /MILESTONES/ */

    /* ISSUES */
    public function add_issue() {
        if($this->input->post("name") !== null && $this->uri->segment(3) != null) {
            $name       =       html_purify($this->input->post("name"));
            $type       =       html_purify($this->input->post("type"));

            if(!in_array($type, array("bug", "documentation", "duplicate", "enhancement", "wontfix"))) flash_redirect("success", "Milestone added succesfully", base_url("projects/view/" . $id));

            $id         =       (int)$this->uri->segment(3);

            if(!countTable($this->config->config["tables"]["milestones"], "WHERE `ID` = '".$id."'")) flash_redirect("error", "Something went wrong...", base_url("projects/view/" . $id));

            $data       =       array(
                "ProjectID"  =>      $id,
                "Name"       =>      $name,
                "CreatedOn"  =>      date("d-m-Y H:i:s", time()),
                "AssignedTo" =>      NULL,
                "CompletedBy"=>      NULL,
                "CreatedBy"  =>      $this->session->userdata("logged_in")["ID"],
                "Type"       =>      $type
            );

            if($this->Projects_model->add_issue($data, $this->session->userdata("logged_in")["ID"])) {
                $this->db->cache_delete("projects", "view");
                $this->db->cache_delete("projects", "assign_issue");
                flash_redirect("success", "Issue added succesfully", base_url("projects/view/" . $id));
            }  else flash_redirect("error", "Something went wrong...", base_url("projects/view/" . $id));
        } else flash_redirect("error", "Something went wrong...", base_url("projects/view/" . $id));
    }
    
    public function assign_issue() {
        if($this->uri->segment(3) != null) {
            $id         =       (int)$this->uri->segment(3);

            if(!countTable($this->config->config["tables"]["issues"], "WHERE `ID` = '".$id."'")) flash_redirect("error", "Something went wrong3...", base_url("projects/view/" . $id));

            if($this->session->userdata("logged_in")["Type"] == 1) {
                if(get_cached_info_null("AssignedTo", $this->config->config["tables"]["issues"], "ID", $id) == null) {
                    $data   =   array(
                        "AssignedTo"    =>  $this->session->userdata("logged_in")["ID"]
                    );
                    $this->Projects_model->assign_issue($data, $id) ? flash_redirect("success", "Issue assigned successfully.", $_SERVER["HTTP_REFERER"]) : flash_redirect("error", "Something went wrong2...", $_SERVER["HTTP_REFERER"]);
                } elseif(get_cached_info_null("AssignedTo", $this->config->config["tables"]["issues"], "ID", $id) == $this->session->userdata("logged_in")["ID"]) {
                    $data   =   array(
                        "CompletedBy"    =>  $this->session->userdata("logged_in")["ID"],
                        "CompletedOn"   =>  date("d-m-Y H:i:s", time()),
                        "AssignedTo"     =>  NULL
                    );
                    if($this->Projects_model->complete_issue($data, $id)) {
                        $this->db->cache_delete("projects", "view");
                        $this->db->cache_delete("projects", "assign_issue");
                        flash_redirect("success", "Issue completed successfully.", $_SERVER["HTTP_REFERER"]);
                    } else flash_redirect("error", "Something went wrong2...", $_SERVER["HTTP_REFERER"]);
                } else flash_redirect("error", "Something went wrong...", base_url("projects"));
            }
            /*elseif($this->session->userdata("logged_in")["Type"] == 2) {
                if(get_cached_info("AssignedTo", $this->config->config["tables"]["milestones"], "ID", $id) == null) {
                    $this->Projects_model->assign_milestone();
                } else if(get_cached_info("AssignedTo", $this->config->config["tables"]["milestones"], "ID", $id) == $this->session->userdata("logged_in")["ID"]) {
                    $this->Projects_model->complete_milestone();
                } else flash_redirect("error", "Something went wrong...", base_url("projects"));
            }*/

        }
    }
    /* /ISSUES/ */


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
}
?>