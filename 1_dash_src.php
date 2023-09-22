<?php

session_start();

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}


require "../connect.php";

// PRERECORDED MESSAGE CHECK      vvvv

$dummy = 0; 

$prerec_off_query = "SELECT count(uniqueid) AS prerec_off FROM usernames WHERE prerecorded IS NULL AND uniqueid > ?";
$prerec_on_query = "SELECT count(uniqueid) AS prerec_on FROM usernames WHERE prerecorded IS NOT NULL AND uniqueid > ?";

$sql = $prerec_off_query;
    $stmt = mysqli_stmt_init($dbc);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $dummy);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $prerec_off_dash = $row["prerec_off"];
            }
        } else {
            echo 'Something went wrong';
        }
    } 

$sql = $prerec_on_query;
    $stmt = mysqli_stmt_init($dbc);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $dummy);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $prerec_on_dash = $row["prerec_on"];
            }
            
        } else {
            echo 'Something went wrong';
        }
    }

$total = $prerec_off_dash + $prerec_on_dash;
$mean = round($prerec_on_dash / $total, 2)*100;

//     NEW USERS PER DAY       vvvv

// $days_query = "SELECT DATE(datecreated) AS days, COUNT(uniqueid) AS num_users FROM usernames WHERE uniqueid > ? GROUP BY days";

$days_query = <<<EOD

SELECT dates.date, IFNULL(COUNT(usernames.uniqueid), 0) AS num_users
FROM dates
LEFT JOIN usernames ON dates.date = DATE(usernames.datecreated) AND usernames.uniqueid > ?
WHERE dates.date <= CURDATE()
GROUP BY dates.date;

EOD;

$days_since_launch = array();
$new_users_per_day_count = array();

$sql = $days_query;
$stmt = mysqli_stmt_init($dbc);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL statement failed";
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $dummy);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $days_since_launch[] = $row["date"];
            $new_users_per_day_count[] = $row["num_users"];
        }
    } else {
        echo 'Something went wrong';
    }
}

// echo json_encode($days1);
// echo json_encode($counts1);

// TOTAL CALLS
$prerec_off_query = "SELECT count(callid) AS total_calls FROM callrequests WHERE callid > ?";


$sql = $prerec_off_query;
    $stmt = mysqli_stmt_init($dbc);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $dummy);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $total_calls = $row["total_calls"];
            }
        } else {
            echo 'Something went wrong';
        }
    } 
// echo $total_calls;

// CALLS PER DAY       vvvvv

// $calls_per_day_query = "SELECT DATE(date) AS days, COUNT(callid) AS total_calls1 FROM callrequests WHERE callid > ? GROUP BY days";

$calls_per_day_query = <<<EOD
    SELECT dates.date as date, IFNULL(COUNT(callrequests.callid), 0) AS total_calls1 
    FROM dates
    LEFT JOIN callrequests ON dates.date = DATE(callrequests.date)
    WHERE dates.date <= CURDATE()
    GROUP BY date;
EOD;

$days = array();
$counts = array();

$sql = $calls_per_day_query;
$stmt = mysqli_stmt_init($dbc);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL statement failed";
    exit();
} else {
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $days[] = $row["date"];
            $counts[] = $row["total_calls1"];
            // echo "Date: " . $row["date"] . ", Count: " . $row["total_calls1"] . "<br>";
        }
    } else {
        echo 'Something went wrong';
    }
}

// echo json_encode($days);
// echo json_encode($counts);

// USERS BY REGRISTRATION METHOD      vvvvv

    $methods_query = "SELECT method AS methods, count(uniqueid) as count_method FROM usernames WHERE uniqueid > ? GROUP BY methods";

    $methods = array();
    $counts2 = array();
    
    $sql = $methods_query;
    $stmt = mysqli_stmt_init($dbc);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $dummy);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $methods[] = $row["methods"];
                $counts2[] = $row["count_method"];
            }
        } else {
            echo 'Something went wrong';
        }
    }

// echo "<br><br>"."Users by method of registration:<br>";

// for ($x = 0; $x < count($methods); $x++) {
//     echo "<br>".$methods[$x]." * * * ";
//     echo " ".$counts2[$x];
// }

?>