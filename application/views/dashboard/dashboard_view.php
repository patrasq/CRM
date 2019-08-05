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
                            <article class="media">
                                <div class="media-content">
                                    <div class="content">
                                        <div class="columns">
                                            <div class="column is-one-quarter">
                                                <span>s</span>
                                            </div>
                                            <div class="column">
                                                <span><a href="s"></a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        
                    </div>
                </div>
            </div>
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
</style>
<script>
    var efficiency_graph  = document.getElementById('efficiency_graph').getContext('2d');

    $(".chart-container").css("width", "100%").css("height", "50vh");

    var instagram_chart = new Chart(efficiency_graph, {
        type: 'line',
        data: {
            labels: ['Latest post', 'Post #2', 'Post #3', 'Post #4', 'Post #5', 'Post #6', 'Post #7', 'Post #8', 'Post #9', 'Post #10'],
            datasets: [{
                label: 'Likes',
                data: ['23','23','223','5523','23','23','4323','23','23'],
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