<?php
// LINK TO APEXCHARTS DOCUMENTATION vvvv
// https://apexcharts.com/docs/series/

session_start();

// ACCESS ARRAYS FROM 0_dashboard        vvvvv

require_once('1_dash_src.php');
require_once('1.1_onboarding_graph_src.php');
require_once('1.2_daily_users_calls_src.php');

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}


require "../connect.php";

// CALLS PER DAY DATA:     vvvvv

// CONNECT ARRAYS to dash_src file: 
// $g_calls_per_day = $counts;
// $g_users_per_day = $counts1;

// $g_days = $days; 


?>

<!doctype html>
<html lang="en">



<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics Dashboard</title>

    <!-- // BOOTSTRAP IMPORT/REFERENCE -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
        <style>

        h1 {
            text-align: center;
            font-family: Arial Black;
            color: white; 
            font-size: 88px;
        }
        h2 {
            text-align: center;
            font-family: Futura;
            color: white; 
        }
        h3 {
            text-align: left;
            font-family: Futura;
            font-weight: Bold; 
        }
        a {
            font-family: Futura;
        }
        p {
            text-align: left;
            font-family: Futura;
            color: gray; 
        }
        hr {
            height: 5px; /* Set the thickness of the <hr> element */
            background-color: Black; 
        }
        .rounded-box-blue {
            background-color: lightblue; /* Set the background color for the box */
            border: 5px solid lightblue; /* Add a thick solid border with the same color as the fill */
            border-radius: 20px; /* Add rounded corners */
            padding: 5px; /* Add some padding for spacing */
            margin-bottom: 20px; /* Add margin at the bottom for separation */
        }
        </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">CallBird Data Services™</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="0_graphs.php">dashboard</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="#">sign-ups</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link" href="2_user_home.php">users</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container my-5">
        <!-- HEADLINE ROW:  -->
        <div class="row">
            <div class="rounded-box-blue">
                <br>
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-5">
                        <h2>
                            total users:
                        </h2>
                        <h1>
                            <?php echo $total; ?> 
                        </h1>
                    </div>
                    <div class="col-md-5">
                        <h2>
                            total calls:
                        </h2>
                        <h1>
                            <?php echo $total_calls; ?> 
                        </h1>
                    </div>
                </div>
                <br>

            </div>
        </div>

        <!-- ROW 1:  -->
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <br><br>
                <div class="row">
                    <h3>
                        new users/day
                        <!-- <a href="https://www.google.com" class="btn btn-primary">Primary</a> -->
                    </h3>
                    <p>
                        New Callbird™ users today: <span style="color: #57a1f5;"><?php echo $users_today; ?></span>
                    </p>

                    <div id="chart2" style="width:100%"></div>
                </div>
            </div>
            <div class="col-md-5">
                <br><br>
                <div class="row">
                    
                    <h3>
                        calls/day
                    </h3>
                    <p>
                    Calls made with Callbird™ today: <span style="color: #57a1f5;"><?php echo $calls_today; ?></span>
                    </p>
                    <div id="chart1" style="width:100%"></div>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-1"></div>
            <div class="col-md-5">
                <br><br>
                <div class="row">
                    <h3>
                        onboarding checklist
                    </h3>
                    <!-- <p>
                    Number of users with a CallBird Wave uploaded: <span style="color: #57a1f5;"><?php //echo $prerec_on_dash; ?></span>
                    </p> -->
                    <p>
                        How many users have: 
                    </p>
                    <div id="chart3" style="width:100%"></div>
                </div>
            </div>
            <div class="col-md-5">
                <br><br>
                <h3>
                    registration methods
                </h3>
                <p>
                    Number of CallBird users by method of registration:
                </p>
                <div id="chart4" style="width:100%"></div>
            </div>
        </div>

        <!-- ROW 2!! -->

        <div class="row">
            <!-- <div class="col-md-6">
                <h3>
                    registration methods
                </h3>
                <p>
                    Number of CallBird users by method of registration:
                </p>
                <div id="chart4" style="width:100%"></div>
            </div> -->
        </div>

        <div class="col-lg-6 px-0">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>

    //CALLS PER DAY
    var options1 = {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'calls',
            data: <?php echo json_encode($counts); ?>
        }],
        xaxis: {
            categories:  <?php echo json_encode($days); ?>,
            tickPlacement: 'on',
            tickAmount: 5
        },
        colors:['#add8e6']

    }
    var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
    chart1.render();

    //                                              USERS PER DAY
    var options2 = {
    chart: {
        type: 'line'
    },
    series: [{
        name: 'users',
        data: <?php echo json_encode($new_users_per_day_count); ?>
    }],
    xaxis: {
        categories: <?php echo json_encode($days_since_launch); ?>,
        tickPlacement: 'on',
        tickAmount: 5
    },
    colors:['#add8e6']

    };

    var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
    chart2.render();

    //                                              USER CHECKLIST
    var options3 = {
        chart: {
            type: 'bar'
        },
        plotOptions: {
          bar: {
            borderRadius: 4,
            horizontal: true,
          }
        },
        series: [{
            name: 'users',
            data: [<?php echo json_encode($onboarded); ?>, 
                <?php echo json_encode($custom_hours); ?>, 
                <?php echo json_encode($callbird_wave); ?>, 
                <?php echo json_encode($calendar); ?>, 
                <?php echo json_encode($phone); ?>, ]
        }],
        xaxis: {
            categories: ["onboarded", "custom hours", "callbird wave", "calendar", "phone"],
            labels: {
                style: {
                    colors: ['#808080', '#808080', '#808080', '#808080', '#808080'],
                    fontFamily: 'Futura', // Set the font to Futura
                    fontSize: '18px' // Set the font size to a larger value (e.g., 18px)
                }
            }
        },
        colors: ['#add8e6'],
        dataLabels: {
            style: {
                colors: ['#ffffff'], // Set the text color to white
                fontSize: '18px', // Set the font size to a larger value (e.g., 18px)
                // offsetY: -100
            }
        },
        states: {
            hover: {
                filter: {
                    type: 'none',
                }
            },
        }
    };

    var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
    chart3.render();

    //                                              REGISTRATION METHODS GRAPH

    var options4 = {
        chart: {
            //width: 380,
            type: 'pie',
        },
        series: <?php echo json_encode($counts2); ?>,
        labels: <?php echo json_encode($methods); ?>,
        colors: ['#add8e6', '#57a0f5', '#ffc008'], // Place the colors property inside the options4 object

        responsive: [{
            breakpoint: 480,
            options: {
                chart: {
                    width: 200
                },
                legend: {
                    position: 'bottom'
                }
            }
        }]
    };

    var chart4 = new ApexCharts(document.querySelector("#chart4"), options4);
    chart4.render();

    </script>
</body>
</html>