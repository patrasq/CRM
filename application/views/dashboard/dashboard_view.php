<?php $this->load->view('dashboard/menu'); ?>

<script src="<?= base_url("assets/js/chart.min.js"); ?>"></script>
<link rel="stylesheet" href="<?= base_url("assets/css/chart.min.css"); ?>" />

<div class="column">
    <div class="columns">
        <div class="column">
            <div class="column">
                <div class="card modern-shadow" style="background:linear-gradient(45deg,#3690ff,#00d2ff 100%)" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <div class="card-content">
                        <div class="content">
                            <h1 class="title"><?= $card_one_data; ?></h1>
                            <h4 class="subtitle"><?= $card_one_label; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="column">
                <div class="card modern-shadow" style="background: linear-gradient(45deg,#de36ff,#9600ff 100%);" data-aos="fade-up" data-aos-delay="250" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <div class="card-content">
                        <div class="content">
                            <h1 class="title"><?= $card_two_data; ?></h1>
                            <h4 class="subtitle"><?= $card_two_label; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="column">
                <div class="card modern-shadow" style="background:linear-gradient(45deg,#36ff38,#0bd71c 100%)" data-aos="fade-up" data-aos-delay="350" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <div class="card-content">
                        <div class="content">
                            <h1 class="title"><?= $card_one_data; ?></h1>
                            <h4 class="subtitle"><?= $card_one_label; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="column">
                <div class="card modern-shadow" style="background:linear-gradient(45deg,#ffa836,#ffcf00 100%)" data-aos="fade-up" data-aos-delay="450" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                    <div class="card-content">
                        <div class="content">
                            <h1 class="title"><?= $card_one_data; ?></h1>
                            <h4 class="subtitle"><?= $card_one_label; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="columns" id="latestnews">
        <br>
        <div class="column">
            <h3 class="subtitle">Efficiency graph</h3>
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <canvas id="efficiency_graph" width="100" height="100"></canvas>
            </div>
            <br>
            <div class="card modern-shadow aos-init aos-animate" style="background:linear-gradient(45deg,#3690ff,#00d2ff 100%);margin-left: 12px;margin-right: 11px;" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart">
                <div class="card-content">
                    <div class="content has-text-centered">
                        <h4 class="subtitle">Your projects</h4>


                        <div class="box dashboardtablebox">

                            <?php foreach($project as $row) { ?>
                            <article class="media" style="color:#fff!important">
                                <div class="media-content">
                                    <div class="content">
                                        <div class="columns">
                                            <div class="column">
                                                <span><?= $row["Name"]; ?></span>
                                            </div>
                                            <div class="column is-half">
                                                <span>
                                                    <?= mb_substr($row["Description"], 0, 150) . "..."; ?>
                                                </span>
                                            </div>
                                            <div class="column is-centered has-text-centered">
                                                <span><a class="button is-primary is-rounded" href="<?= base_url("projects/view/" . $row["ID"]); ?>">Go</a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>

                            <?php } ?>
                        </div>

                    </div>
                </div>
            </div>
            <?php if($this->session->userdata("logged_in")["Type"] == 1) { ?>
            <br>
            <div class="card modern-shadow aos-init aos-animate" style="background:linear-gradient(45deg,#3690ff,#00d2ff 100%);margin-left: 12px;margin-right: 11px;" data-aos="fade-up" data-aos-delay="100" data-aos-offset="200" data-aos-easing="ease-out-quart">
                <div class="card-content">
                    <div class="content has-text-centered">
                        <h4 class="subtitle">Your tasks</h4>


                        <div class="box dashboardtablebox">
                            <div class="columns is-multiline">
                                <?php foreach($task as $row) {  ?>
                                <div class="column is-one-third">
                                    <div class="card modern-shadow" style="background:<?= $gradients[array_rand($gradients)]; ?>" data-aos="fade-up" data-aos-delay="450" data-aos-offset="200" data-aos-easing="ease-out-quart" class="aos-init aos-animate">
                                        <div class="card-content">
                                            <div class="content">
                                                <h5 class="subtitle"><?= $row["Name"]; ?></h5>
                                                <?php if($row["Type"] != null) echo '<span style="border-color: #fff;color: #fff;" class="button is-small is-outlined '.get_issue_color($row["Type"]) . '">' .$row["Type"] . '</span>'; else echo "MILESTONE"; ?>
                                                <br><br>
                                                <a class="button is-outlined is-light" href="<?= base_url("projects/view/".$row["ProjectID"]); ?>">See project</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php $this->load->view('dashboard/footer'); ?>

<style>

    .card {
        background: #FE5E84;
        border-radius: 10px;
        color: #fff !important;
        font-family: 'Yantramanav';
        font-weight: 300;    
    }
    .card h1 {
        font-size: 60px;
        color: #fff;
        font-family: 'Yantramanav';
        font-weight: 300; 
    }
    .card h4 {
        text-transform: uppercase;
        color: #fff;
        font-family: 'Yantramanav';
        font-weight: 300; 
    }
    .content table td, .content table th {
        border-bottom:none;
    }
    #latestnews a {
        color: #fff;
        font-weight: bold;
    }
    table tr{
        background: linear-gradient(45deg,#00caff ,#3690ff 100%);
        margin-bottom: 10px !important;
        border-radius: 10px !important;
    }
    table {
        border-spacing: 0 15px !important;
        border-collapse: initial;
    }
    h5 {
        text-shadow: 0px 0px 3px #000;
        color: #fff !important;
    }
</style>
<script>
    var efficiency_graph  = document.getElementById('efficiency_graph').getContext('2d');

    $(".chart-container").css("width", "100%").css("height", "50vh");

    var instagram_chart = new Chart(efficiency_graph, {
        type: 'line',
        data: {
            labels: ['January','February','March','April','May','June','July','August','September','October','November','December'],
            datasets: [{
                label: 'Tasks completed',
                data: <?= $tasks_completed_monthly; ?>,
                backgroundColor: [
                'rgba(0, 140, 255, 0.26)'
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
            },
            legend: {
                display: false
            },
        }
    });
</script>