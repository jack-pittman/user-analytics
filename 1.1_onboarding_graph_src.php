<?php

// ONBOARDING GRAPH SRC

// Used as a reference for the graph on the DASHBOARD page to give an overview of user onboarding and interaction metrics. 
// These include the total number of users who are ONBOARDED, have a CALLBIRD WAVE set up, have entered their PHONE, set up
// CALENDAR, or CUSTOM HOURS. 

session_start();

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}

require "../connect.php";

// MASTER VARIABLES
// Stores the number of users with each of the following checklist items complete

$onboarded = -1;
$callbird_wave = -1; 
$phone = -1; 
$calendar = -1; 
$custom_hours = -1; 

$dummy = 1;

// SQL RETRIEVAL FUNCTION

function queryToValue($query, $decoy, $column_name, $dbc1) {
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
            // 'Something went wrong';
        }
    }
}

//                                              ONBOARDING QUERY
$onboarded_query = "
SELECT count(username) as onbrd from usernames
where onboarding = ? + 4
";

$onboarded = queryToValue($onboarded_query, 1, "onbrd", $dbc);


//                                              PRERECORDED QUERY
$prerecorded_query = "
SELECT count(username) as prerec from usernames
where prerecorded IS NOT NULL and 1 = ?
";

$callbird_wave = queryToValue($prerecorded_query, 1, "prerec", $dbc);

//                                              PHONE QUERY
$phone_query = "
SELECT count(username) as phone from usernames
where phone IS NOT NULL and 1 = ?
";

$phone = queryToValue($phone_query, 1, "phone", $dbc);

//                                              CALENDAR QUERY
$calendar_query = "
SELECT count(username) as calendar from usernames
where meeting IS NOT NULL and 1 = ?
";

$calendar = queryToValue($calendar_query, 1, "calendar", $dbc);

//                                              CUSTOM HOURS QUERY
$custom_hours_query = "
SELECT 
    SUM(CASE 
        WHEN 'true' IN (hours_monday, hours_tuesday, hours_wednesday, hours_thursday, hours_friday, hours_saturday, hours_sunday) THEN 1
        ELSE 0
    END) AS customhrs
FROM usernames
WHERE 1 = ?
";

$custom_hours = queryToValue($custom_hours_query, 1, "customhrs", $dbc);

?>