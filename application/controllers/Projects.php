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
        $data["project_status"]              = $this->Projects_model->get_info("Status", $id, 1)[0]["Status"]; // selector, id, cache?

        $data["completed_milestones"]        = countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy > 0 AND ProjectID = '".$id."'");
        $data["max_milestones"]              = countTable($this->config->config["tables"]["milestones"], "WHERE ProjectID = '".$id."'");

        $data["completed_issues"]            = countTable($this->config->config["tables"]["issues"], "WHERE CompletedBy > 0 AND ProjectID = '".$id."'");
        $data["max_issues"]                  = countTable($this->config->config["tables"]["issues"], "WHERE ProjectID = '".$id."'");

        $data["completed_tasks"]            = countTable($this->config->config["tables"]["issues"], "WHERE CompletedBy > 0 AND ProjectID = '".$id."'") + countTable($this->config->config["tables"]["milestones"], "WHERE CompletedBy > 0 AND ProjectID = '".$id."'");
        $data["max_tasks"]                  = countTable($this->config->config["tables"]["issues"], "WHERE ProjectID = '".$id."'") + countTable($this->config->config["tables"]["milestones"], "WHERE ProjectID = '".$id."'");

        $data["milestone"]                   = $this->Projects_model->get_milestones($id);
        $data["issue"]                       = $this->Projects_model->get_issues($id);

        $data["my_task"]                     = $this->Projects_model->my_tasks($id);

        $data["gradients"]                  =   array(
            "linear-gradient(45deg,#ffa836,#ffcf00 100%)",
            "linear-gradient(45deg,#3690ff,#00d2ff 100%)",
            "linear-gradient(45deg,#de36ff,#9600ff 100%)",
            "linear-gradient(45deg,#36ff38,#0bd71c 100%)"
        );
        $data["team_member"]                 = $this->Projects_model->get_team(array("AssignedTo1", "AssignedTo2", "AssignedTo3", "AssignedTo4", "AssignedTo5"), $id);

        $data["progress_color"]              = "is-danger";

        $data["main_content"]                = 'dashboard/projects/view_view';
        $this->load->view('includes/template.php', $data);
    }

    public function discharge() {
        if($this->session->userdata("logged_in")["Type"]) {
            $id                                  = ($this->uri->segment(3)) ? (int)$this->uri->segment(3) : flash_redirect("error", "Invalid project.", base_url("projects"));
            if(!countTable($this->config->config["tables"]["projects"], "WHERE `ID` = " . $id)) flash_redirect("error", "Invalid project.", base_url("projects"));

            if(
                $this->session->userdata("logged_in")["ID"] == get_info("AssignedTo1", $this->config->config["tables"]["projects"], "ID", $id) || 
                $this->session->userdata("logged_in")["ID"] == get_info("AssignedTo2", $this->config->config["tables"]["projects"], "ID", $id) ||
                $this->session->userdata("logged_in")["ID"] == get_info("AssignedTo3", $this->config->config["tables"]["projects"], "ID", $id) ||
                $this->session->userdata("logged_in")["ID"] == get_info("AssignedTo4", $this->config->config["tables"]["projects"], "ID", $id) ||
                $this->session->userdata("logged_in")["ID"] == get_info("AssignedTo5", $this->config->config["tables"]["projects"], "ID", $id)
            ) {
                if($this->session->userdata("logged_in")["ID"] == get_info("AssignedTo1", $this->config->config["tables"]["projects"], "ID", $id)) {
                    $update_data    =   array(
                        "AssignedTo1"   =>  NULL
                    );
                }
                elseif($this->session->userdata("logged_in")["ID"] == get_info("AssignedTo2", $this->config->config["tables"]["projects"], "ID", $id)) {
                    $update_data    =   array(
                        "AssignedTo2"   =>  NULL
                    );
                }
                elseif($this->session->userdata("logged_in")["ID"] == get_info("AssignedTo3", $this->config->config["tables"]["projects"], "ID", $id)) {
                    $update_data    =   array(
                        "AssignedTo3"   =>  NULL
                    );
                }
                elseif($this->session->userdata("logged_in")["ID"] == get_info("AssignedTo4", $this->config->config["tables"]["projects"], "ID", $id)) {
                    $update_data    =   array(
                        "AssignedTo4"   =>  NULL
                    );
                }
                elseif($this->session->userdata("logged_in")["ID"] == get_info("AssignedTo5", $this->config->config["tables"]["projects"], "ID", $id)) {
                    $update_data    =   array(
                        "AssignedTo5"   =>  NULL
                    );
                }
                if($this->Projects_model->discharge($update_data, $id)) {
                    $data   =   array(
                        "Content"   => "<a href='".get_profile($this->session->userdata("logged_in")["ID"]) . "'>".get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]). " " . get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . "</a> discharged from " . get_info("Name", $this->config->config["tables"]["projects"], "ID", $id) . " project.",
                        "Reciever"  => get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", $id)
                    );
                    $this->Projects_model->insert_notification($data);

                    if(get_info("OneSignal", $this->config->config['tables']['accounts'], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", $id))) {
                        sendMessage(get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]). " " . get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . " discharghed himself from " . get_info("Name", $this->config->config["tables"]["projects"], "ID", $id) . " project", get_info("OneSignal", $this->config->config['tables']['accounts'], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", $id)));
                    }
                    flash_redirect("success", "Discharged successfully.", base_url("projects/")); 
                } else flash_redirect("error", "Something went wrong", base_url("projects/"));
            } else flash_redirect("error", "Something went wrong", base_url("projects/"));
        } else flash_redirect("error", "Something went wrong", base_url("projects/"));

        $data["main_content"]                = 'dashboard/projects/main_view';
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

    public function force_assign_project() {
        if($this->input->post('project') !== null && $this->uri->segment(3) !== null) {
            $id         =       (int)$this->uri->segment(3);
            $project    =       (int)$this->input->post('project');

            if(!countTable($this->config->config["tables"]["projects"], "WHERE `ID` = '".$project."'")) flash_redirect("error", "Something went wrong3...", base_url("projects/view/" . $id));

            if($this->session->userdata("logged_in")["Type"] == 2) {
                if(get_cached_info_null("AssignedTo1", $this->config->config["tables"]["projects"], "ID", $project) == null ||
                   get_cached_info_null("AssignedTo2", $this->config->config["tables"]["projects"], "ID", $project) == null ||
                   get_cached_info_null("AssignedTo3", $this->config->config["tables"]["projects"], "ID", $project) == null|| 
                   get_cached_info_null("AssignedTo4", $this->config->config["tables"]["projects"], "ID", $project) == null||
                   get_cached_info_null("AssignedTo5", $this->config->config["tables"]["projects"], "ID", $project) == null) {
                    if(null == get_info("AssignedTo1", $this->config->config["tables"]["projects"], "ID", $id)) {
                        $update_data    =   array(
                            "AssignedTo1"   =>  $id
                        );
                    }
                    elseif(null == get_info("AssignedTo2", $this->config->config["tables"]["projects"], "ID", $id)) {
                        $update_data    =   array(
                            "AssignedTo2"   =>  $id
                        );
                    }
                    elseif(null == get_info("AssignedTo3", $this->config->config["tables"]["projects"], "ID", $id)) {
                        $update_data    =   array(
                            "AssignedTo3"   =>  $id
                        );
                    }
                    elseif(null == get_info("AssignedTo4", $this->config->config["tables"]["projects"], "ID", $id)) {
                        $update_data    =   array(
                            "AssignedTo4"   =>  $id
                        );
                    }
                    elseif(null == get_info("AssignedTo5", $this->config->config["tables"]["projects"], "ID", $id)) {
                        $update_data    =   array(
                            "AssignedTo5"   =>  $id
                        );
                    }
                    $this->Projects_model->assign_project($id, $update_data) ? flash_redirect("success", "Project assigned successfully.", $_SERVER["HTTP_REFERER"]) : flash_redirect("error", "Something went wrong2...", $_SERVER["HTTP_REFERER"]);
                } else flash_redirect("success", "All slots occupied.", $_SERVER["HTTP_REFERER"]);
            }

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

                    $data2   =   array(
                        "Content"   => "<a href='".get_profile($this->session->userdata("logged_in")["ID"]) . "'>".get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]). " " . get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . "</a> completed a milestone on " . get_info("Name", $this->config->config["tables"]["projects"], "ID", $id) . " project.",
                        "Reciever"  => get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                                get_info("ProjectID", $this->config->config['tables']['milestones'], "ID", $id)
                                               )
                    );
                    $this->Projects_model->insert_notification($data2);

                    if(get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", get_info("ProjectID", $this->config->config['tables']['milestones'], "ID", $id)))) {

                        sendMessage(
                            get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . " " . 
                            get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . 
                            " assigned a milestone on project " . 
                            get_info("Name", $this->config->config["tables"]["projects"], "ID",  get_info("ProjectID", $this->config->config["tables"]["milestones"], "ID", $id)), 
                            get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", 
                                     get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                              get_info("ProjectID", $this->config->config['tables']['milestones'], "ID", $id)
                                             )
                                    )
                        );

                    }

                    $this->Projects_model->assign_milestone($data, $id) ? flash_redirect("success", "Milestone assigned successfully.", $_SERVER["HTTP_REFERER"]) : flash_redirect("error", "Something went wrong2...", $_SERVER["HTTP_REFERER"]);
                } elseif(get_cached_info_null("AssignedTo", $this->config->config["tables"]["milestones"], "ID", $id) == $this->session->userdata("logged_in")["ID"]) {

                    $data   =   array(
                        "CompletedBy"    =>  $this->session->userdata("logged_in")["ID"],
                        "CompleteDate"   =>  date("d-m-Y H:i:s", time()),
                        "AssignedTo"     =>  NULL
                    );

                    $data2   =   array(
                        "Content"   => "<a href='".get_profile($this->session->userdata("logged_in")["ID"]) . "'>".get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]). " " . get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . "</a> completed a milestone on " . get_info("Name", $this->config->config["tables"]["projects"], "ID", $id) . " project.",
                        "Reciever"  => get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                                get_info("ProjectID", $this->config->config['tables']['milestones'], "ID", $id)
                                               )
                    );
                    $this->Projects_model->insert_notification($data2);

                    if(get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", get_info("ProjectID", $this->config->config['tables']['milestones'], "ID", $id)))) {
                        if(get_info("OneSignal", $this->config->config['tables']['accounts'], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", get_info("ProjectID", $this->config->config["tables"]["milestones"], "ID", $id))))) {
                            sendMessage(
                                get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . " " . 
                                get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . 
                                " completed a milestone on project " . 
                                get_info("Name", $this->config->config["tables"]["projects"], "ID",  get_info("ProjectID", $this->config->config["tables"]["milestones"], "ID", $id)), 
                                get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", 
                                         get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                                  get_info("ProjectID", $this->config->config['tables']['milestones'], "ID", $id)
                                                 )
                                        )
                            );
                        }
                    }

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

                    $data2   =   array(
                        "Content"   => "<a href='".get_profile($this->session->userdata("logged_in")["ID"]) . "'>".get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]). " " . get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . "</a> assigned a milestone on " . get_info("Name", $this->config->config["tables"]["projects"], "ID", $id) . " project.",
                        "Reciever"  => get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                                get_info("ProjectID", $this->config->config['tables']['issues'], "ID", $id)
                                               )
                    );
                    $this->Projects_model->insert_notification($data2);

                    if(get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", get_info("ProjectID", $this->config->config['tables']['issues'], "ID", $id)))) {

                        sendMessage(
                            get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . " " . 
                            get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . 
                            " assigned an issue on project " . 
                            get_info("Name", $this->config->config["tables"]["projects"], "ID",  get_info("ProjectID", $this->config->config["tables"]["issues"], "ID", $id)), 
                            get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", 
                                     get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                              get_info("ProjectID", $this->config->config['tables']['issues'], "ID", $id)
                                             )
                                    )
                        );

                    }

                    $data   =   array(
                        "AssignedTo"    =>  $this->session->userdata("logged_in")["ID"]
                    );
                    $this->Projects_model->assign_issue($data, $id) ? flash_redirect("success", "Issue assigned successfully.", $_SERVER["HTTP_REFERER"]) : flash_redirect("error", "Something went wrong2...", $_SERVER["HTTP_REFERER"]);
                } elseif(get_cached_info_null("AssignedTo", $this->config->config["tables"]["issues"], "ID", $id) == $this->session->userdata("logged_in")["ID"]) {

                    $data2   =   array(
                        "Content"   => "<a href='".get_profile($this->session->userdata("logged_in")["ID"]) . "'>".get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]). " " . get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . "</a> completed an issue on " . get_info("Name", $this->config->config["tables"]["projects"], "ID", $id) . " project.",
                        "Reciever"  => get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                                get_info("ProjectID", $this->config->config['tables']['issues'], "ID", $id)
                                               )
                    );
                    $this->Projects_model->insert_notification($data2);

                    if(get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", get_info("ProjectID", $this->config->config['tables']['issues'], "ID", $id)))) {

                        sendMessage(
                            get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . " " . 
                            get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . 
                            " completed an issue on project " . 
                            get_info("Name", $this->config->config["tables"]["projects"], "ID",  get_info("ProjectID", $this->config->config["tables"]["issues"], "ID", $id)), 
                            get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", 
                                     get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", 
                                              get_info("ProjectID", $this->config->config['tables']['issues'], "ID", $id)
                                             )
                                    )
                        );

                    }

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

    public function deliver() {
        if($this->uri->segment(3) !== null && $this->session->userdata("logged_in")["Type"] == 2) {
            $id     =   (int)$this->uri->segment(3);

            if(get_info("ID", $this->config->config["tables"]["projects"], "ID", $id)) {
                if(get_info("Status", $this->config->config["tables"]["projects"], "ID", $id) == 0) {
                    $data2   =   array(
                        "Content"   => "<a href='".get_profile($this->session->userdata("logged_in")["ID"]) . "'>".get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]). " " . get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . "</a> delivered " . get_info("Name", $this->config->config["tables"]["projects"], "ID", $id) . " project.",
                        "Reciever"  => get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", $id)
                    );
                    $this->Projects_model->insert_notification($data2);

                    if(get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", get_info("ProjectID", $this->config->config['tables']['issues'], "ID", $id)))) {

                        sendMessage(
                            get_info("FirstName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . " " . 
                            get_info("LastName", $this->config->config["tables"]["employees"], "ID", $this->session->userdata("logged_in")["ID"]) . 
                            " delivered project " . 
                            get_info("Name", $this->config->config["tables"]["projects"], "ID",  $id), 
                            get_info("OneSignal", $this->config->config["tables"]["accounts"], "ID", get_info("Supervizor", $this->config->config["tables"]["projects"], "ID", $id))
                        );

                    }
                    
                    $this->Projects_model->deliver_project(array("Status"=>1),$id) ? flash_redirect("success", "Project delivered successfully.", base_url("projects")) : flash_redirect("error", "Something went wrong...", base_url("projects"));
                    
                } 
            }
        }
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
}
?>