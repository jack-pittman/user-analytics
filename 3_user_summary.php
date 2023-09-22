<?php
session_start();

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}

// GET USERNAME FROM FORM 

if (isset($_POST["username"])) {
    $username = $_POST["username"];

    // STORE USERNAME AS SESSION VARIABLE
    $_SESSION["username"] = $username;
}

// IMPORT STATEMENTS
require "../connect.php";
require "3.1_user_calls.php";
require "3.2_user_checklist.php";
require "3.3_call_types.php";


// BUTTON COLOR FUNCTION

function buttonColor($user_state) {
    if ($user_state === "YES") {
        return '<a class="btn btn-success" href="2_user_home.php" role="button">YES</a>';
    }
    if ($user_state === "NO") {
        return '<a class="btn btn-danger" href="2_user_home.php" role="button">NO</a>';
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analytics Dashboard</title>

    <!-- BOOTSTRAP IMPORT/REFERENCE -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    
    <!-- STYLE SHEET -->
    <style>
        h1 {
            text-align: center;
            font-family: Arial Black;
            /* color: #57a1f5;  */
        }
        /* NAME AND LOCATION LABELS */
        h2 {
            text-align: center;
            font-family: futura;
            color: white;
            font-size: 20px; /* Adjust the font size as per your requirement */
        }

        /* CHECKLIST LABELS */
        h3 {
            text-align: left;
            font-family: Verdana;
            color: gray; 
        }

        /* BUTTON LABELS */
        a {
            font-family: futura;
            text-align: right;
            /* font-weight: bold; */
        }

        /* HEADLINE LABELS */
        h4 {
            text-align: right;
            font-family: futura;
            /* font-weight: bold; */
            color: gray; 
        }
        
        /* GRAPH TITLES */
        h5 {
            text-align: right;
            font-family: courier;
            font-weight: bold;
            color: gray; 
        }
        hr {
            height: 5px; /* Set the thickness of the <hr> element */
            background-color: Black; 
        }
        .text-banner {
            margin-top: 20px;
            background-color: #f8f9fa;
            color: #ef604c;
            padding: 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-family: Futura;
            font-weight: light;
        }
        .rounded-box {
            background-color: white; /* Set the background color for the box */
            border: 5px solid lightblue; /* Add a thick solid border with the same color as the fill */
            border-radius: 10px; /* Add rounded corners */
            padding: 5px; /* Add some padding for spacing */
            margin-bottom: 20px; /* Add margin at the bottom for separation */
        }
        .rounded-box-gray {
            background-color: white; /* Set the background color for the box */
            border: 5px solid gray; /* Add a thick solid border with the same color as the fill */
            border-radius: 10px; /* Add rounded corners */
            padding: 5px; /* Add some padding for spacing */
            margin-bottom: 20px; /* Add margin at the bottom for separation */
        }
        .rounded-box-blue {
            background-color: lightblue; /* Set the background color for the box */
            border: 5px solid lightblue; /* Add a thick solid border with the same color as the fill */
            border-radius: 10px; /* Add rounded corners */
            padding: 5px; /* Add some padding for spacing */
            margin-bottom: 20px; /* Add margin at the bottom for separation */
        }
        .rounded-box-white {
            background-color: white; /* Set the background color for the box */
            border: 5px solid white; /* Add a thick solid border with the same color as the fill */
            border-radius: 10px; /* Add rounded corners */
            padding: 5px; /* Add some padding for spacing */
            margin-bottom: 20px; /* Add margin at the bottom for separation */
        }
        .btn-success {
            background-color: #84cc95 !important; /* green */
            border: 5px white; 
        }
        .btn-warning {
            background-color: #fec009 !important; /* yellow */
            border: 5px white; 
            color: white;
        }
        .btn-danger {
            background-color: #ffabab !important; /* red */
            border: 5px white; 
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <br>

        <!-- OUTER BOX -->
        <div class="rounded-box">
            <br>
            <!-- HEADLINE ROW -->
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
                        <h1>
                            <?php echo $username?>        
                            <?php //echo $test_print?>       
                        </h1>


                    <div class="rounded-box-blue">
                        
                        <h2>
                            <?php echo $user_first_name?>
                            <?php echo $user_last_name?>
                            <?php echo " • "?>
                            <?php echo $user_location?>
                        </h2>
                    </div>

                    <div class="row">
                        <!-- <br> -->
                        <!-- HEADLINE STATS:  -->
                        <div class="col-md-4">
                        <!-- <br> -->
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>total calls:</h4> 
                                </div>
                                <div class="col-md-2">
                                    <!-- ACCESS TOTAL_CALLS VARIABLE !! -->
                                    <a class="btn btn-primary" href="2_user_home.php" role="button"><?php echo $total_user_calls; ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <!-- <br> -->
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>calls today:</h4> 
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-secondary" href="2_user_home.php" role="button"><?php echo $daily_user_calls; ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <!-- <br> -->
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>calls this week:</h4> 
                                </div>
                                <div class="col-md-2">
                                    <a class="btn btn-warning" href="2_user_home.php" role="button"><?php echo $weekly_user_calls; ?></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <br> -->

            
                </div>
            </div>

            <!-- GRAPHS ROW -->
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-5">
                
                <br>

                    <br>
                    <div class="rounded-box-gray">
                        <br>

                        <div id="chart1" style="width:100%"></div>

                    </div>

                    <h5> call activity (all-time) </h5>
                </div>
                <div class="col-md-5">
                    <br><br>
                    <!-- <h1>This is also a graph.</h1> -->

                    <div class="rounded-box-white">
                        <br>

                        <div id="chart2" style="width:100%"></div>
                    </div>
                    <h5> call breakdown </h5>
                </div>
                
            </div>

        <!-- ONBOARDING CHECKLIST  -->

         <!-- [ ]  Add checklist items (onboarding, etc.) to summary page
            [x]  onboarded?
            [x]  prerec?
            [x]  phone?
            [x]  calndr?
            [x]  customhrs? -->

            <div class="row">

            <!-- ONBOARDED?  -->

                <div class="col-md-2"></div>
                <div class="col-md-8">
                <br>
                    <div class="text-banner">
                        <div class="row">
                            <!-- <div class="col-md-1"></div> -->
                            <div class="col-md-10">
                                <h3>onboarded?</h3> 
                            </div>
                            <div class="col-md-2">
                                <!-- <a class="btn btn-success" href="2_user_home.php" role="button"><?php echo $user_onboarded; ?></a> -->
                                <?php echo buttonColor($user_onboarded); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

            <!-- PREREC?  -->
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="text-banner">
                        <div class="row">
                            <!-- <div class="col-md-1"></div> -->
                            <div class="col-md-10">
                                <h3>callbird wave?</h3> 
                            </div>
                            <div class="col-md-2">
                                <!-- <a class="btn btn-success" href="2_user_home.php" role="button"><?php echo $user_prerec; ?></a> -->
                                <?php echo buttonColor($user_prerec); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PHONE?  -->

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="text-banner">
                        <div class="row">
                            <!-- <div class="col-md-1"></div> -->
                            <div class="col-md-10">
                                <h3>phone?</h3> 
                            </div>
                            <div class="col-md-2">
                                <!-- <a class="btn btn-success" href="2_user_home.php" role="button"><?php echo $user_phone; ?></a> -->
                                <?php echo buttonColor($user_phone); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CALENDAR?  -->

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="text-banner">
                        <div class="row">
                            <!-- <div class="col-md-1"></div> -->
                            <div class="col-md-10">
                                <h3>calendar?</h3> 
                            </div>
                            <div class="col-md-2">
                                <!-- <a class="btn btn-success" href="2_user_home.php" role="button"><?php echo $user_calendar; ?></a> -->
                                <?php echo buttonColor($user_calendar); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="text-banner">
                        <div class="row">
                            <!-- <div class="col-md-1"></div> -->
                            <div class="col-md-10">
                                <h3>custom hours?</h3> 
                            </div>
                            <div class="col-md-2">
                                <!-- <a class="btn btn-success" href="2_user_home.php" role="button"><?php echo $user_custom_hours; ?></a> -->
                                <?php echo buttonColor($user_custom_hours); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a class="btn btn-secondary" href="2_user_home.php" role="button">Back</a>
        </div>
    </div>

<!-- APEXCHARTS DOCUMENTATION -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- APEXCHARTS FACTORY  -->
    <script>
    // USER CALLS/DAY CHART
    var options1 = {
        chart: {
            type: 'line'
        },
        series: [{
            name: 'calls',
            data: <?php echo json_encode($num_calls); ?> 
        }],
        xaxis: {
            categories: <?php echo json_encode($days); ?>, 
            labels: {
                show: false // Hide the x-axis labels
            },
            axisTicks: {
                show: false // Hide the x-axis tick lines
            }
        },
        colors:['#add8e6']
    };

    var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
    chart1.render();

    // USER CALL TYPE BREAKDOWN
    var options2 = {
        series: [
            <?php echo json_encode($missed_calls); ?>, 
            <?php echo json_encode($ghost_calls); ?>, 
            <?php echo json_encode($successful_calls); ?>, 
            <?php echo json_encode($short_calls); ?>, 
            <?php echo json_encode($total_user_calls - $not_other); ?>
        ], // Example values that add up to 100
        chart: {
            width: 455,
            type: 'pie',
        },
        labels: ['missed', 'ghosted', 'success', 'short', 'other'],
        colors: ['#ffc008', '#add8e6', '#83cc95', '#d3d3d3','#808080'], // Light orange, Light gray/blue, Green, dark gray
        legend: {
            position: 'bottom'
        },
        responsive: [{
            breakpoint: 480,

            options: {

                chart: {
                    width: 200
                },

                legend: {
                    position: 'bottom',
                }
            }
        }]
    };

    var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
    chart2.render();
    </script>
</body>
</html>