<?php $this->load->view('dashboard/menu'); ?>
<div class="column" style="width:98%">
    <br>
    <?php if($this->session->userdata("logged_in")["Type"] == 2){ ?>
        <a href="#" class="button custom-button-normal open-modal" data-modal-id="#addproject">Add project</a>
    <?php } ?>
    <br><br><br><br>
    <h1 class="title bigname">Your projects</h1>
    <table class="table" style="width:98%">
        <thead>
            <tr>
                <th>
                    <span>Name</span>
                </th>
                <th>
                    <span>Deadline</span>
                </th>
                <th>
                    <span>Status</span>
                </th>
                <th>
                    <span>View</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($personal_project) { 
                foreach($personal_project as $row) {
                    echo 
                        "<tr>" , 
                    "<td>" ,
                    $row["Name"],
                    "</td>" ,
                    "<td>" ,
                    $row["Deadline"],
                    "</td>" ,
                    "<td>" ,
                    $row["Status"] ? "Completed" : "In progress",
                    "</td>" ,
                    "<td><a href='".base_url("projects/view/".$row["ID"])."'>View</a></td>" ,
                    "</tr>";
                }
            } else echo "No projects available.";
            ?>
        </tbody>
    </table>
    <br><hr><br>
    <h1 class="title bigname">All projects</h1>
    <table class="table" style="width:98%">
        <thead>
            <tr>
                <th>
                    <span>Name</span>
                </th>
                <th>
                    <span>Deadline</span>
                </th>
                <th>
                    <span>Supervisor</span>
                </th>
                <th>
                    <span>Status</span>
                </th>
                <th>
                    <span>View</span>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            if($all_projects) { 
                foreach($all_projects as $row) {
                    echo 
                        "<tr>" , 
                    "<td>" ,
                    $row["Name"],
                    "</td>" ,
                    "<td>" ,
                    $row["Deadline"],
                    "</td>" ,
                    "<td>" ,
                    get_cached_info("FirstName", $this->config->config['tables']['accounts_detailed'], "UserID", $row['Supervizor']) , " " , get_cached_info("LastName", $this->config->config['tables']['accounts_detailed'], "UserID", $row['Supervizor']),
                    "</td>" ,
                    "<td>" ,
                    $row["Status"] ? "Completed" : "In progress",
                    "</td>" ,
                    "<td><a href='".base_url("projects/view/".$row["ID"])."'>View</a></td>" ,
                    "</tr>";
                }
            } else echo "No projects available.";
            ?>
        </tbody>
    </table>
</div>
<?php $this->load->view('dashboard/footer'); ?>

<?php if($this->session->userdata("logged_in")["Type"] == 2){ ?>
<div class="modal" id="addproject">
    <div class="modal-background close-modal" data-modal-id="#addproject"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-8">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Project name</label>
                                    <input class="input is-rounded" id="projectname" placeholder="Project name" type="text" autocomplete="off" required>
                                </div>
                            </div>                                                                                                               

                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Description</label>
                                    <textarea class="input" id="projectdescription" rows="2"></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <div class="control has-icons-left">
                                    <label for="department">Deadline</label>
                                    <br>
                                    <input class="input is-rounded" id="projectdeadline" id="deadline" placeholder="05-08-2018" type="text" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="columns">
                                <div class="column">
                                    <div class="field">
                                        <label class="label">Milestone</label>
                                        <div class="control has-icons-left">
                                            <input name="expense_input" class="input is-static" type="text" placeholder="Milestone name" id="milestone_input">
                                            <span class="icon is-small is-left">
                                                <i class="far fa-check-circle"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-one-fifth">
                                    <span class="icon is-small is-left" style="margin-top:33px;margin-left: 15px;font-size: 15px;background: #d5d5d5;padding: 15px;border-radius: 100%;color: #747474;" id="add_milestone">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                </div>
                            </div>
                            <label class="label">Milestones</label>
                            <table class="table" style="width:90%;">
                                <thead>
                                    <th>#</th>
                                    <th>Milestone</th>
                                    <th>Option</th>
                                </thead>
                                <tbody id="milestone_court">
                                </tbody>
                            </table>

                            <button type="submit" id="addproject_button" class="custom-button-normal">add project</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#addproject" aria-label="close"></button>
</div>

<input id="csrfHash"      type="hidden" value="<?= $this->security->get_csrf_hash(); ?>">
<input id="csrfTokenName" type="hidden" value="<?= $this->security->get_csrf_token_name(); ?>">

<?php } ?>

<script src="<?= base_url("assets/js/jquery.mask.js"); ?>"></script>
<script>
    $('#projectdeadline').mask('00-00-0000', {reverse: true});
    var i = 0,
        csrfHash    = '<?= $this->security->get_csrf_hash(); ?>',
        csrfName    = $('#csrfTokenName').val();
    
    $("#add_milestone").on('click', function(){
        i++;
        $("#milestone_court").html(
            $("#milestone_court").html() + 
            "<tr data-specificid='"+i+"'> " + 
            "<td>" + 
            i  + 
            "</td>" +
            "<td>" + 
            $("#milestone_input").val()  + 
            "</td>" +
            "<td>" +
            "<span class='icon is-small is-left' onclick='remove_expense("+i+")' style='margin-left: 15px;font-size: 15px;background: #d5d5d5;padding: 15px;border-radius: 100%;color: #747474;'><i class='fas fa-trash'></i></span>" +
            "</td>" +
            "</tr>");
    });

    $("#addproject_button").on('click', function(){
        $("#add_milestone").css("display", "none");
        $("#milestone_input").attr("disabled", 1); 


        var milestone_encode = "";

        $('#milestone_court > tr').each(function(e) {
            milestone_encode += '{"milestone":"'+($(this).children('td').eq(1).html())+'"},';
        });

        milestone_encode  = milestone_encode.substring(0, milestone_encode.length - 1);

        $.ajax(
            {
                type: "POST",
                url: "<?= base_url("projects/add_project/"); ?>",
                data: { 
                    json:               milestone_encode,
                    projectname:        $("#projectname").val(),
                    projectdescription: $("#projectdescription").val(),
                    projectdeadline:    $("#projectdeadline").val(),
                    [csrfName]:         $('#csrfHash').val()
                },
                success:function(response)
                {
                    document.location = "<?= base_url("project/view/"); ?>"  + response.returnId;
                },
                error: function(response) 
                {
                    document.location = "<?= base_url("projects/"); ?>";
                }
            }
        ); 
    });

    function remove_expense(data_id) {
        $("tr[data-specificid='" + data_id +"']").remove();
    }
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

