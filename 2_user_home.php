<?php
// LINK TO APEXCHARTS DOCUMENTATION vvvv
// https://apexcharts.com/docs/series/

session_start();

// ACCESS TABLE CONTENT VARIABLE FROM 4_USER_DATABASE !!!!!!
$tableContent = $_SESSION['tableContent'];

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}

require "../connect.php";
require "4_user_database.php";

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

    <!-- DATA TABLES REFERENCE: -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.0/css/buttons.dataTables.min.css">

    <!-- ADD JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ADD DATA TABLES JAVASCRIPT -->
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.0/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.0/js/buttons.html5.min.js"></script>
        
    <style>

        h1 {
            text-align: center;
            font-family: Arial Black;
            /* color: #57a1f5;  */
        }
        h3 {
            text-align: center;
            font-family: Arial Black;
        }
        a {
            font-family: Futura;
        }
        p {
            text-align: center;
            font-family: Futura;
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
            font-family: Arial;
            font-weight: light;
        }
        .rounded-box {
            background-color: white; /* Set the background color for the box */
            border: 5px solid lightblue; /* Add a thick solid border with the same color as the fill */
            border-radius: 10px; /* Add rounded corners */
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
    <div class="container-fluid">

        <!-- ROW 1:  -->

        <br>
        <div class="rounded-box">

                <br>
                
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <h3>user report</h3>
                        <p>enter a username:</p>
                        
                        <!-- SENDS USER TO TABLE DISPLAY PAGE, USING POST FUNCTION -->
                        
                        <form method="post" action="3_user_summary.php">
                            <div class="input-group my-3">
                                <span class="input-group-text" id="basic-addon1">@</span>
                                <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                        
                        <div id="resultDiv"></div>
                    </div>
                </div>
                <br><br>
        </div>

        <div class="rounded-box">
            <div class="row">
                <!-- <div class="col-md-2"></div> -->

                <div class="col-md-12">

                    <br>

                    <h3>user database</h3>
                    <p>browse and filter users:</p>

                    <br>

                    <?php echo $tableContent; ?>                      

                </div>
            </div>

            <br><br>
        </div>
         
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- CREATE TABLE !!!! -->
    <script>
	new DataTable('#example', {
		dom: 'Bfrtip',
		buttons: ['copy', 'csv', 'excel', 'pdf', 'print'],
	});

    // $(document).ready(function() {
    //     $('#example').DataTable({
    //         dom: 'Bfrtip',
    //         buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    //     });
    // });
	</script>

</body>
</html>