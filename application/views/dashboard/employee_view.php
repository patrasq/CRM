<?php $this->load->view('dashboard/menu'); ?>

<script src="<?= base_url("assets/js/chart.min.js"); ?>"></script>
<link rel="stylesheet" href="<?= base_url("assets/css/chart.min.css"); ?>" />

<div class="column">
    <br>
    <h1 class="title" id="bigname"><?php echo $employee_name; ?></h1>
    <h2 class="subtitle" data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate"><?php echo $employee_position; ?> @ <?php echo $employee_department; ?></h2>

    <a href="<?= base_url("hr/delete_employee/" . $employee_id); ?>" class="button custom-button-normal" data-aos="fade-left" data-aos-delay="1000" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate"><?php echo $text_delete_employee; ?></a>

    <br><br>  

    <nav class="tabs is-boxed is-fullwidth is-large" style="width: 88%;margin-left: 0px;" data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
        <div class="container">
            <ul>
                <li class="tab is-active" onclick="openTab(event,'general')"><a>General</a></li>
                <li class="tab" onclick="openTab(event,'activity')"><a>Activity</a></li>
                <li class="tab" onclick="openTab(event,'documents')"><a>Documents</a></li>
            </ul>
        </div>
    </nav>


    <div class="container section" style="margin-top: -24px;box-shadow: 0px 0px 10px rgba(1, 1, 1, 0.18);width: 88%;margin-left: 0px;"  data-aos="fade-left" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
        <div id="general" class="content-tab" >

            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td><?php echo $employee_name; ?></td>
                    </tr>
                    <tr>
                        <th>Gender</th>
                        <td><?php echo $employee_gender; ?></td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td><?php echo $employee_phone; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $employee_address; ?></td>
                    </tr>
                    <tr>
                        <th>Birth date</th>
                        <td><?php echo date("d M Y", $employee_birth); ?></td>
                    </tr>
                    <tr>
                        <th>Hired since</th>
                        <td><?php echo date("d M Y", $employee_hiredsince); ?></td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div id="activity" class="content-tab" style="display:none">
            <div class="columns">
                <div class="column">
                    <h3 class="subtitle" style="display:inline;">Tasks completed (<?= $completed_tasks; ?>/<?= $max_tasks; ?>)</h3> &nbsp; <span class="downloadpdf" style="display:inline-block;font-size:20px;color:red" data-download="tasks_graph"><i class="far fa-file-pdf"></i></span> &nbsp; <span class="printgraph" style="display:inline-block;font-size:20px;color:#47a5fb" data-download="tasks_graph"><i class="fas fa-print"></i></span>
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <canvas id="tasks_graph" width="100" height="100"></canvas>
                    </div>
                    <br>
                    <h3 class="subtitle" style="display:inline;">Tasks completed monthly</h3> 
                    &nbsp; 
                    <span class="downloadpdf" style="display:inline-block;font-size:20px;color:red" data-download="tasks_graph_monthly">
                        <i class="far fa-file-pdf"></i>
                    </span> &nbsp; 
                    <span class="printgraph" style="display:inline-block;font-size:20px;color:#47a5fb" data-download="tasks_graph_monthly">
                        <i class="fas fa-print"></i>
                    </span>
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <canvas id="tasks_graph_monthly" width="100" height="100"></canvas>
                    </div>
                </div>
                <div class="column">
                    <h3 class="subtitle" style="display:inline;">Issues completed (<?= $completed_issues; ?>/<?= $max_issues; ?>)</h3> &nbsp; <span class="downloadpdf" style="display:inline-block;font-size:20px;color:red" data-download="issues_graph"><i class="far fa-file-pdf"></i></span> &nbsp; <span class="printgraph" style="display:inline-block;font-size:20px;color:#47a5fb" data-download="issues_graph"><i class="fas fa-print"></i></span>
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <canvas id="issues_graph" width="100" height="100"></canvas>
                    </div>
                    <br>
                    <h3 class="subtitle" style="display:inline;">Issues completed monthly</h3> &nbsp; <span class="downloadpdf" style="display:inline-block;font-size:20px;color:red" data-download="issues_graph_monthly"><i class="far fa-file-pdf"></i></span> &nbsp; <span class="printgraph" style="display:inline-block;font-size:20px;color:#47a5fb" data-download="issues_graph_monthly"><i class="fas fa-print"></i></span>
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <canvas id="issues_graph_monthly" width="100" height="100"></canvas>
                    </div>
                </div>
                <div class="column" >
                    <h3 class="subtitle" style="display:inline;">Milestones completed (<?= $completed_milestones; ?>/<?= $max_milestones; ?>)</h3> &nbsp; <span class="downloadpdf" style="display:inline-block;font-size:20px;color:red" data-download="milestones_graph"><i class="far fa-file-pdf"></i></span> &nbsp; <span class="printgraph" style="display:inline-block;font-size:20px;color:#47a5fb" data-download="milestones_graph"><i class="fas fa-print"></i></span>
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <canvas id="milestones_graph" width="100" height="100"></canvas>
                    </div>
                    <br>
                    <h3 class="subtitle" style="display:inline;">Milestones completed monthly</h3> &nbsp; <span class="downloadpdf" style="display:inline-block;font-size:20px;color:red" data-download="milestones_graph_monthly"><i class="far fa-file-pdf"></i></span> &nbsp; <span class="printgraph" style="display:inline-block;font-size:20px;color:#47a5fb" data-download="milestones_graph_monthly"><i class="fas fa-print"></i></span>
                    <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                        <canvas id="milestones_graph_monthly" width="100" height="100"></canvas>
                    </div>
                </div>

            </div>

            <h3 class="subtitle">Tasks completed (<?= $completed_tasks; ?>/<?= $max_tasks; ?>)</h3>
            <progress class="progress is-medium is-danger" value="<?= $completed_tasks; ?>" max="<?= $max_tasks; ?>">60%</progress>
            <hr>
            <h3 class="subtitle">Issues completed (<?= $completed_issues; ?>/<?= $max_issues; ?>)</h3>
            <progress class="progress is-medium is-danger" value="<?= $completed_tasks; ?>" max="<?= $max_tasks; ?>">60%</progress>
            <hr>
            <h3 class="subtitle">Milestones completed (<?= $completed_milestones; ?>/<?= $max_milestones; ?>)</h3>
            <progress class="progress is-medium is-danger" value="<?= $completed_milestones; ?>" max="<?= $max_milestones; ?>">60%</progress>
        </div>
        <div id="documents" class="content-tab" style="display:none">
            <table class="table is-striped is-narrow is-hoverable is-fullwidth">
                <tbody>
                    <tr>
                        <th>Export excel data (.xsls)</th>
                        <td><a href="<?= base_url("hr/download_excel/" . $employee_id); ?>">Download</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <br> <br> 

</div>
<?php $this->load->view('dashboard/footer'); ?>

<div class="modal" id="unmodal">
    <div class="modal-background close-modal" data-modal-id="#unmodal"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-8">
                            <div class="field">
                                <div class="control has-icons-right">
                                    <label for="department">Department name</label>
                                    <input class="input is-rounded" name="department" placeholder="Department name" value="<?php echo set_value('email'); ?>" type="email" autocomplete="off">
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#unmodal" aria-label="close"></button>
</div>

<div class="modal" id="adddepartment">
    <div class="modal-background close-modal" data-modal-id="#adddepartment"></div>
    <div class="modal-content">
        <div class="card">
            <div class="card-content">
                <div class="content">
                    <div class="columns">
                        <div class="column is-8">
                            <div class="field">
                                <div class="control has-icons-right">
                                    <label for="department">Department name</label>
                                    <input class="input is-rounded" name="department" placeholder="Department name" value="<?php echo set_value('email'); ?>" type="email" autocomplete="off">
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button class="modal-close close-modal is-large" data-modal-id="#adddepartment" aria-label="close"></button>
</div>

<script src="<?= base_url("assets/js/jspdf.min.js"); ?>"></script>

<style>
    #bigname {
        text-transform: uppercase;
        background: linear-gradient(to right,#0094ff 0,#00edfe 100%);
        background-clip: border-box;
        background-clip: border-box;
        background-clip: text;
        text-fill-color: transparent;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 70px;

    }
    .tabs li.is-active a {
        background: linear-gradient(45deg,#3690ff,#00d2ff 100%);
        color: #fff;
        text-transform: uppercase;
        font-family: 'Montserrat';
        margin-bottom: -20px;
        transition: .2s all;
    }
    .table th {
        text-transform: none;
        background: linear-gradient(to right,#0094ff 0,#00edfe 100%);
        text-fill-color: transparent;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-size: 16px;
    }
</style>

<script>
    $(".notset_set").on('click', function() {
        $(this).parent().find('.editrow').css("display", "flex");
        $(this).css("display", "none");
    });

    $(".notset_revert").on('click', function(){
        $(this).parent().parent().parent().find('.editrow').css("display", "none");
        $(this).parent().parent().parent().find('.notset_set').css("display", "block");
    });

    var tasks_graph                 = document.getElementById('tasks_graph').getContext('2d');
    var issues_graph                = document.getElementById('issues_graph').getContext('2d');
    var milestones_graph            = document.getElementById('milestones_graph').getContext('2d');
    var tasks_monthly_graph         = document.getElementById('tasks_graph_monthly').getContext('2d');
    var issues_monthly_graph        = document.getElementById('issues_graph_monthly').getContext('2d');
    var milestones_monthly_graph    = document.getElementById('milestones_graph_monthly').getContext('2d');

    $(".chart-container").css("width", "100%").css("height", "50vh");

    var tasks_completed = new Chart(tasks_graph, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Total'],
            datasets: [{
                label: '#',
                data: ['<?= $completed_tasks; ?>', '<?= $max_tasks; ?>'],
                backgroundColor: [
                    'rgba(0, 140, 255, 0.26)',
                    'rgba(128, 34, 128, 0.26)'
                ],
                lineTension: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var tasks_completed_monthly = new Chart(tasks_monthly_graph, {
        type: 'bar',
        data: {
            labels: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            datasets: [{
                label: '#',
                data: <?= $tasks_completed_monthly ?>,
                backgroundColor: [
                'rgba(0, 140, 255, 0.26)',
                'rgba(128, 34, 128, 0.26)'
                ],
                lineTension: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var issues_completed_monthly = new Chart(issues_monthly_graph, {
        type: 'bar',
        data: {
            labels: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            datasets: [{
                label: '#',
                data: <?= $issues_completed_monthly ?>,
                backgroundColor: [
                'rgba(0, 140, 255, 0.26)',
                'rgba(128, 34, 128, 0.26)'
                ],
                lineTension: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var milestones_completed_monthly = new Chart(milestones_monthly_graph, {
        type: 'bar',
        data: {
            labels: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            datasets: [{
                label: '#',
                data: <?= $milestones_completed_monthly ?>,
                backgroundColor: [
                'rgba(0, 140, 255, 0.26)',
                'rgba(128, 34, 128, 0.26)'
                ],
                lineTension: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });


    var issues_completed = new Chart(issues_graph, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Total'],
            datasets: [{
                label: '#',
                data: ['<?= $completed_issues; ?>', '<?= $max_issues; ?>'],
                backgroundColor: [
                    'rgba(0, 140, 255, 0.26)',
                    'rgba(128, 34, 128, 0.26)'
                ],
                lineTension: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var milestones_completed = new Chart(milestones_graph, {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Total'],
            datasets: [{
                label: '#',
                data: ['<?= $completed_milestones; ?>', '<?= $max_milestones; ?>'],
                backgroundColor: [
                    'rgba(0, 140, 255, 0.26)',
                    'rgba(128, 34, 128, 0.26)'
                ],
                lineTension: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    $(".downloadpdf").on('click', function() {
        var canvas = document.getElementById($(this).data("download"));

        // only jpeg is supported by jsPDF
        var imgData = canvas.toDataURL("image/jpeg", 1.0);
        var pdf = new jsPDF();

        pdf.setTextColor(0,0,0);
        pdf.text('<?= $employee_name; ?> - '+$(this).data("download").replace("_", " ") +' as of <?= date("d-m-Y", time()); ?>',50,10);
        pdf.addImage(imgData, 'JPEG', 50, 20);
        pdf.save($(this).data("download") + "_<?= str_replace(" ", "_", $employee_name); ?>_<?= date("d-m-y", time()); ?>.pdf");
    });

    $(".printgraph").on('click', function() {
        var canvas = document.getElementById($(this).data("download")).toDataURL();
        var windowContent = '<!DOCTYPE html>';
        windowContent += '<html>'
        windowContent += '<head><title>Print canvas</title></head>';
        windowContent += '<body>'
        windowContent += '<h1><?= $employee_name; ?> - '+$(this).data("download").replace('_', ' ') +' as of <?= date("d-m-y", time()); ?></h1><br><img src="' + canvas + '">';
        windowContent += '</body>';
        windowContent += '</html>';
        var printWin = window.open('','','width=400,height=400');
        printWin.document.open();
        printWin.document.write(windowContent);
        printWin.document.close();
        printWin.focus();
        printWin.print();
        printWin.close();
    });
</script>