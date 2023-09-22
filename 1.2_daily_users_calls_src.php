<?php

// DAILY USERS/CALLS SRC

// Used as a reference for the text banners on the 0_graphs page, showing number of new users and calls on the 
// Callbird platform, on the current day. 

session_start();

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}

require "../connect.php";

// MASTER VARIABLES
// Store the number of new users on the current day 

$users_today = -1;
$calls_today = -1; 

$dummy = 1;

function queryToDailyValue($query, $column_name, $decoy, $dbc1) {
    $stmt = mysqli_stmt_init($dbc1);
    if (!mysqli_stmt_prepare($stmt, $query)) {
        echo "SQL statement failed";
        exit();
    } else {
        mysqli_stmt_bind_param($stmt, "s", $decoy);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result); // Fetch the result row
            return $row[$column_name]; // Access the count using the correct alias
        } else {
            return -1; 
        }
    }
}

//                                              NEW USERS TODAY QUERY
$new_users_today_query = "
SELECT count(callid) as daily_users FROM callrequests 
WHERE date(date) = CURDATE() AND 1 = ?
";

$users_today = queryToDailyValue($new_users_today_query, "daily_users", 1, $dbc);

// echo "New Users Today: " . $users_today . "<br>";

//                                              CALLS TODAY QUERY
$calls_today_query = "
SELECT count(callid) as daily_calls from callrequests
WHERE date(date) = CURDATE() AND 1 = ?
";

$calls_today = queryToDailyValue($calls_today_query, "daily_calls", 1, $dbc);

// echo "Calls Today: " . $calls_today . "<br>";
?>