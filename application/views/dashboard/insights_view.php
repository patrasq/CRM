<?php $this->load->view('dashboard/menu'); ?>

<script src="<?= base_url("assets/js/chart.min.js"); ?>"></script>
<link rel="stylesheet" href="<?= base_url("assets/css/chart.min.css"); ?>" />

<div class="column" style="padding:30px;">
    Best employee:
</div>

<?php $this->load->view('dashboard/footer'); ?>

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