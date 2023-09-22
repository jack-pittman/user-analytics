<?php


// MAKE SURE SESSION IS ACTIVE
session_start();

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}

// GET USERNAME (STORED AS SESSION VARIABLE)
if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
    //echo $username; 
}

require "../connect.php";
// require "../4_user_database.php";

// MASTER VARIABLES

$user_first_name = "";
$user_last_name = "";

$user_location = "";

// $username = 'jack...'
$total_user_calls = -1; 
$daily_user_calls = -1;
$weekly_user_calls = -1; 

$days = array();
$num_calls = array();

// USER FIRST/LAST/LOCATION QUERY

$first_last_location_query = "
    SELECT firstname, lastname, location
    FROM usernames
    WHERE username = ?
";

$sql = $first_last_location_query; // Add missing semicolon here
$stmt = mysqli_stmt_init($dbc);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL statement failed";
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $user_first_name = $row["firstname"];
            $user_last_name = $row["lastname"];
            $user_location = $row["location"];
        }
    } else {
        echo 'Something went wrong';
    }

    // echo $user_first_name;
    // echo $user_last_name;
    // echo $user_location;
}



// TOTAL CALLS QUERY
$total_calls_query = "SELECT username, count(callid) as num_calls " .
                    "FROM usernames " .
                    "INNER JOIN callrequests ON linkeduser = uniqueid " .
                    "GROUP BY username " .
                    "HAVING username = ?";

$sql = $total_calls_query;
    $stmt = mysqli_stmt_init($dbc);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $total_user_calls = $row["num_calls"];
            }
        } else {
            $total_user_calls = 0; 
            // echo 'Something went wrong';
        }
    }

// DAILY CALLS QUERY
$daily_calls_query = "

    SELECT username, DATE(date) AS call_date, COUNT(callid) AS num_calls
    FROM usernames
    INNER JOIN callrequests ON linkeduser = uniqueid
    WHERE username = ? AND DATE(date) = CURDATE()
    GROUP BY username, DATE(date)

";

$sql = $daily_calls_query;
    $stmt = mysqli_stmt_init($dbc);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        echo "SQL statement failed";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            $daily_user_calls = $row["num_calls"];
        } 
        else {
            $daily_user_calls = 0;
            // echo 'Something went wrong';
        }
    }

// WEEKLY CALLS QUERY
$weekly_calls_query = "
    SELECT username, DATE(date) AS call_date, COUNT(callid) AS num_calls
    FROM usernames
    INNER JOIN callrequests ON linkeduser = uniqueid
    WHERE username = ? AND DATE(date) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY username
";

$sql = $weekly_calls_query;
$stmt = mysqli_stmt_init($dbc);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL statement failed";
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        // Fetch the data using mysqli_fetch_assoc or similar functions
        $row = mysqli_fetch_assoc($result);
        $weekly_user_calls = $row["num_calls"];
    } else {
        $weekly_user_calls = 0;
        // echo 'No data found';
    }
}

// echo $weekly_user_calls;



// CALLS PER DAY QUERY

$calls_per_day_query = "

    SELECT d.date AS calldate, IFNULL(callcount, 0) AS callcount
    FROM dates d
    LEFT JOIN (
        SELECT DATE(callrequests.date) AS tempdate, IFNULL(COUNT(callrequests.callid), 0) AS callcount
        FROM callrequests
        INNER JOIN usernames ON linkeduser = uniqueid
        WHERE username = ?
        GROUP BY DATE(tempdate)
    ) AS subquery ON d.date = subquery.tempdate
    WHERE d.date <= CURDATE()

";

$days = array();
$num_calls = array();

$sql = $calls_per_day_query;
$stmt = mysqli_stmt_init($dbc);
if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "SQL statement failed";
    exit();
} else {
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $days[] = $row["calldate"];
            $num_calls[] = $row["callcount"];

        }
    } else {
        echo 'Something went wrong';
    }

    // echo json_encode($days);
    // echo json_encode($num_calls);
}

?>