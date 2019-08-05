<?php $this->load->view('dashboard/menu'); ?>

<script src="<?= base_url("assets/js/chart.min.js"); ?>"></script>
<link rel="stylesheet" href="<?= base_url("assets/css/chart.min.css"); ?>" />
<link rel="stylesheet" href="<?= base_url("assets/css/font-awesome.min.css"); ?>" />

<div class="column" style="padding:30px;">
    <div class="columns">
        <div class="column">
            <!-- INCOME BY MONTH -->
            <h1 class="subtitle has-text-centered">
                Income by month
            </h1>
            <?php if($month_profit) { ?>
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <canvas id="income_month" width="100" height="100"></canvas>
            </div>
            <?php } else echo "<h2 class='subtitle has-text-centered'>No data yet.</h2>"; ?>
        </div>
        <div class="column">
            
            <!-- EXPENSE BY MONTH -->
            <h1 class="subtitle has-text-centered">
                Expenses
            </h1>
            <?php if($expenses) { ?>
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <canvas id="expenses" width="100" height="100"></canvas>
            </div>
            <?php } else echo "<h2 class='subtitle has-text-centered'>No data yet.</h2>"; ?>
            
            <h1 class="subtitle has-text-centered">
                Income by product
            </h1>
            <?php if($product_profit) { ?>
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <canvas id="income_product" width="100" height="100"></canvas>
            </div>
            <?php } else echo "<h2 class='subtitle has-text-centered'>No data yet.</h2>";?>
        </div>
        <div class="column">
            <h1 class="subtitle has-text-centered">
                Income by employee
            </h1>
            <?php if($employee_profit) { ?>
            <div class="chart-container" style="position: relative; height:40vh; width:80vw">
                <canvas id="income_employee" width="100" height="100"></canvas>
            </div>
            <?php } else echo "<h2 class='subtitle has-text-centered'>No data yet.</h2>";?>
        </div>
    </div>
</div>

<script>
    <?php if($month_profit) { ?>
    var month_chart     = document.getElementById('income_month').getContext('2d');
    <?php } ?>

    <?php if($product_profit) { ?>
    var product_chart   = document.getElementById('income_product').getContext('2d');
    <?php } ?>
    
    <?php if($employee_profit) { ?>
    var employee_chart  = document.getElementById('income_employee').getContext('2d');
    <?php } ?>
    
    <?php if($expenses) { ?>
    var expenses_chart  = document.getElementById('expenses').getContext('2d');
    <?php } ?>

    $(".chart-container").css("width", "90%").css("height", "90%");

    <?php if($month_profit) { ?>
    var income_month = new Chart(month_chart, {
        type: 'doughnut',
        data: {
            labels: [<?= $month_profit_labels; ?>],
            datasets: [{
                label: '# of Votes',
                data: [<?= $month_profit_data; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    <?php } ?>

    <?php if($product_profit) { ?>
    // IF PRODUCT PROFT
    var income_product = new Chart(product_chart, {
        type: 'doughnut',
        data: {
            labels: [<?= $product_profit_labels; ?>],
            datasets: [{
                label: '# of Votes',
                data: [<?= $product_profit_data; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)' // sa genereze automat
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    <?php } ?>


    <?php if($employee_profit) { ?>
    var income_employee = new Chart(employee_chart, {
        type: 'doughnut',
        data: {
            labels: [<?= $employee_profit_labels; ?>],
            datasets: [{
                label: '# of Votes',
                data: [<?= $employee_profit_data; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    <?php } ?>
    
    <?php if($expenses) { ?>
    var expenses = new Chart(expenses_chart, {
        type: 'doughnut',
        data: {
            labels: [<?= $expenses_labels; ?>],
            datasets: [{
                label: '# of Votes',
                data: [<?= $expenses_data; ?>],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    <?php } ?>
</script>

<?php $this->load->view('dashboard/footer'); ?>