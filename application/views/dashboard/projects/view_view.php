<?php $this->load->view('dashboard/menu'); ?>
<div class="column" style="width:98%">
    <br>
    <h3 class="title bigname"><?= html_purify($project_name); ?></h3>
    <br>
    <div class="columns">
        <div class="column is-10">

            <nav class="tabs is-boxed is-fullwidth is-large" data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                <div class="container">
                    <ul>
                        <li class="tab is-active" onclick="openTab(event,'general')"><a>General</a></li>
                        <li class="tab" onclick="openTab(event,'milestones')"><a>Milestones</a></li>
                        <li class="tab" onclick="openTab(event,'issues')"><a>Issues</a></li>
                    </ul>
                </div>
            </nav>


            <div class="container section is-10" style="margin-top: -25px;box-shadow:0px 0px 10px rgba(1, 1, 1, 0.18);width:100%"  data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                <div id="general" class="content-tab" >
                    <div class="columns">
                        <div class="column">
                            <h4 class="subtitle">Project delivery</h4>
                            <progress class="progress is-medium <?= $progress_color; ?>" value="60" data-text="60" max="100">60%</progress>
                            <br>
                        </div>
                        <div class="column">
                            <h4 class="subtitle">Milestones (<?= $completed_milestones; ?>/<?= $max_milestones; ?>)</h4>
                            <progress class="progress is-medium <?= $progress_color; ?>" value="<?= $completed_milestones; ?>" max="<?= $max_milestones; ?>"><?= $completed_milestones; ?>/<?= $max_milestones; ?></progress>
                            <br>
                        </div>
                        <div class="column">
                            <h4 class="subtitle">Issues (<?= $completed_issues; ?>/<?= $max_issues; ?>)</h4>
                            <progress class="progress is-medium <?= $progress_color; ?>" value="<?= $completed_issues; ?>" max="<?= $max_issues; ?>"></progress>
                            <br>
                        </div>
                    </div>

                    <h4 class="subtitle">Time management</h4>
                    <label class="label" style="display:inline-block;">Deadline</label> &nbsp; <abbr title="<?= $project_deadline; ?>"><?= get_time_difference($project_deadline); ?></abbr>
                    <br>
                    <label class="label" style="display:inline-block;">Time left</label> &nbsp; <abbr title="<?= $project_deadline; ?>"><?= get_time_difference($project_deadline); ?></abbr>
                    <hr>

                    <h4 class="subtitle">Team</h4>
                    <?php if($team_member){$incrementor = 1; foreach($team_member as $row) { ?>
                    <figure class="image is-128x128">
                        <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png">
                    </figure>
                    <br>
                    <span><a href='<?= get_profile($row["AssignedTo".$incrementor]); ?>'><?= get_cached_info("FirstName", $this->config->config['tables']['employees'], "ID", $row['AssignedTo' . $incrementor]) . " " . get_cached_info("LastName", $this->config->config['tables']['employees'], "ID", $row['AssignedTo' . $incrementor]); ?></a></span>
                    <?php $incrementor++; }}else echo "No team member."; ?>
                    <hr>

                    <h4 class="subtitle">Time management</h4>
                    <label class="label" style="display:inline-block;">Milestones per day</label> &nbsp; <abbr title="<?= $project_deadline; ?>"><?= get_time_difference($project_deadline); ?></abbr>
                    <br>
                    <label class="label" style="display:inline-block;">Milestones per week</label> &nbsp; <abbr title="<?= $project_deadline; ?>"><?= get_time_difference($project_deadline); ?></abbr>
                    <hr>
                </div>
                <div id="milestones" class="content-tab" style="display:none">
                    <div class="card modern-shadow aos-init aos-animate" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart">
                        <div class="card-content">
                            <div class="content has-text-centered">
                                <?php 
                                foreach($milestone as $row): ?>
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content">
                                            <div class="columns">
                                                <div class="column">
                                                    <span><?= html_purify($row["Name"]); ?></span>
                                                </div>
                                                <div class="column">
                                                    created <?= get_time_difference($row["CreateDate"]); ?>
                                                </div>
                                                <div class="column">
                                                    <?php
                                                    if($row["CompletedBy"] == null) {
                                                        echo ($row["AssignedTo"]) ? "assigned to <a href='".get_profile($row["AssignedTo"])."'>" . get_cached_info("FirstName", $this->config->config['tables']['employees'], "ID", $row['AssignedTo']) . " " . get_cached_info("LastName", $this->config->config['tables']['employees'], "ID", $row['AssignedTo']) . "</a>" : "no one assigned"; }
                                                    else echo "completed by<br> <a href='".get_profile($row["CompletedBy"])."'>" . get_cached_info("FirstName", $this->config->config['tables']['employees'], "ID", $row['CompletedBy']) . " " . get_cached_info("LastName", $this->config->config['tables']['employees'], "ID", $row['CompletedBy']) . "</a>";
                                                    ?>
                                                </div>
                                                <div class="column">
                                                    <span style="text-align:right;display:block">
                                                        <?php 
                                                        if($this->session->userdata("logged_in")["Type"] == 1) {
                                                            if($row["CompletedBy"] == null) {
                                                                if($row["AssignedTo"] == null) {
                                                        ?>
                                                        <a href="<?= base_url("projects/assign_milestone/" . $row["ID"]); ?>">
                                                            assign milestone
                                                        </a>
                                                        <?php 
                                                                } elseif($row["AssignedTo"] == $this->session->userdata("logged_in")["ID"]) { ?>
                                                        <a href="<?= base_url("projects/assign_milestone/" . $row["ID"]); ?>">
                                                            mark as complete
                                                        </a>
                                                        <?php } else echo get_project_status($row["CompletedBy"]); } else echo get_project_status($row["CompletedBy"]);
                                                        } else { ?>
                                                        <?= get_project_status($row["CompletedBy"]); ?>
                                                        <?php } ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php if($this->session->userdata("logged_in")["Type"] == 2) {  ?>
                    <br>
                    <a href="#" class="button custom-button-normal open-modal" data-modal-id="#addmilestone">Add milestone</a>
                    <?php } ?>
                </div>

                <div id="issues" class="content-tab" style="display:none">
                    <div class="card modern-shadow aos-init aos-animate" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart">
                        <div class="card-content">
                            <div class="content has-text-centered">
                                <?php 
                                if($issue) { foreach($issue as $row): ?>
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content">
                                            <div class="columns">
                                                <div class="column">
                                                    <span class="tag <?= get_issue_color($row["Type"]); ?>"><?= $row["Type"]; ?></span>
                                                    <span><?= html_purify($row["Name"]); ?></span>
                                                </div>
                                                <div class="column">
                                                    created <?= get_time_difference($row["CreatedOn"]); ?>
                                                </div>
                                                <div class="column">
                                                    <?php
                                            if($row["CompletedBy"] == null) {
                                                echo ($row["AssignedTo"]) ? "assigned to <a href='".get_profile($row["AssignedTo"])."'>" . get_cached_info("FirstName", $this->config->config['tables']['employees'], "ID", $row['AssignedTo']) . " " . get_cached_info("LastName", $this->config->config['tables']['employees'], "ID", $row['AssignedTo']) . "</a>" : "no one assigned"; }
                                            else echo "completed by<br> <a href='".get_profile($row["CompletedBy"])."'>" . get_cached_info("FirstName", $this->config->config['tables']['employees'], "ID", $row['CompletedBy']) . " " . get_cached_info("LastName", $this->config->config['tables']['employees'], "ID", $row['CompletedBy']) . "</a>";
                                                    ?>
                                                </div>
                                                <div class="column">
                                                    <span style="text-align:right;display:block">
                                                        <?php 
                                            if($this->session->userdata("logged_in")["Type"] == 1) {
                                                if($row["CompletedBy"] == null) {
                                                    if($row["AssignedTo"] == null) {
                                                        ?>
                                                        <a href="<?= base_url("projects/assign_issue/" . $row["ID"]); ?>">
                                                            assign issue
                                                        </a>
                                                        <?php 
                                                    } elseif($row["AssignedTo"] == $this->session->userdata("logged_in")["ID"]) { ?>
                                                        <a href="<?= base_url("projects/assign_issue/" . $row["ID"]); ?>">
                                                            mark as complete
                                                        </a>
                                                        <?php } else echo get_project_status($row["CompletedBy"]); } else echo get_project_status($row["CompletedBy"]);
                                            } else { ?>
                                                        <?= get_project_status($row["CompletedBy"]); ?>
                                                        <?php } ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                                <?php endforeach; } else echo "<span>No issues yet.</span>";?>
                            </div>
                        </div>
                    </div>
                    <br>
                    <a href="#" class="button custom-button-normal open-modal" data-modal-id="#addissue">Add issue</a>
                </div>
            </div>
        </div>
        <?php $this->load->view('dashboard/footer'); ?>

        <?php if($this->session->userdata("logged_in")["Type"] == 2) {  ?>

        <div class="modal" id="addmilestone">
            <div class="modal-background close-modal" data-modal-id="#addmilestone"></div>
            <div class="modal-content">
                <div class="card">
                    <div class="card-content">
                        <div class="content">
                            <div class="columns">
                                <div class="column is-8">
                                    <?php echo form_open("projects/add_milestone/" . $project_id); ?>
                                    <div class="field">
                                        <div class="control has-icons-left">
                                            <label for="name">Name</label>
                                            <input class="input is-rounded" name="name" placeholder="Milestone name" type="text" autocomplete="off" required>
                                        </div>
                                    </div>                                 

                                    <button type="submit" class="custom-button-normal">add milestone</button>
                                    <?php echo form_close(); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="modal-close close-modal is-large" data-modal-id="#addmilestone" aria-label="close"></button>
        </div>

        <?php }  ?>

        <div class="modal" id="addissue">
            <div class="modal-background close-modal" data-modal-id="#addissue"></div>
            <div class="modal-content">
                <div class="card">
                    <div class="card-content">
                        <div class="content">
                            <div class="columns">
                                <div class="column is-8">
                                    <?php echo form_open("projects/add_issue/" . $project_id); ?>
                                    <div class="field">
                                        <div class="control has-icons-left">
                                            <label class="label" for="name">Name</label>
                                            <input class="input is-rounded" name="name" placeholder="Milestone name" type="text" autocomplete="off" required>
                                        </div>
                                    </div>    

                                    <div class="field">
                                        <div class="control has-icons-left">
                                            <label class="label" for="name">Type</label>
                                            <div class="select is-rounded">
                                                <select name="type" required>
                                                    <option value="bug">Bug</option>
                                                    <option value="documentation">Documentation</option>
                                                    <option value="duplicate">Duplicate</option>
                                                    <option value="enhancement">Enhancement</option>
                                                    <option value="wontfix">Wontfix</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    

                                    <button type="submit" class="custom-button-normal">add issue</button>
                                    <?php echo form_close(); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="modal-close close-modal is-large" data-modal-id="#addissue" aria-label="close"></button>
        </div>

        <script>
            $('#departments').on('change', function() {
                //this.value
                $("#positionfield").css("display", "block");

                $.ajax({
                    url: "<?php echo base_url("hr/get_positions/"); ?>" + this.value, 
                    success:function(data) {
                        $("#positionselect").html(data);
                    }
                });
            });
        </script>

        <style>
            .bigname {
                text-transform: uppercase;
                background: linear-gradient(to right,#0094ff 0,#00edfe 100%);
                background-clip: border-box;
                background-clip: border-box;
                background-clip: text;
                text-fill-color: transparent;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                font-size: 40px;

            }
        </style>

