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

// METHODS

function boolConvert($val) {
    if ($val === null || $val === 0) {
        return "NO";
    } else {
        return "YES";
    }
}

function onboardConvert($val) {
    if ($val === null || $val != 5) {
        return "NO";
    } else {
        return "YES";
    }
}

// MASTER VARIABLES

$user_onboarded = "";
$user_prerec = "";
$user_phone = "";
$user_calendar = "";
$user_custom_hours = "";


// USER FIRST/LAST/LOCATION QUERY

$first_last_location_query = "

    SELECT onboarding, prerecorded, phone, meeting as calendar, 
    CASE 
        WHEN 'true' IN (hours_monday, hours_tuesday, hours_wednesday, hours_thursday, hours_friday, hours_saturday, hours_sunday) THEN 'YES'
        ELSE 'NO'
    END AS customhrs
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
        $row = mysqli_fetch_assoc($result);
    
        $user_onboarded = onboardConvert($row["onboarding"]);
        $user_prerec = boolConvert($row["prerecorded"]);
        $user_phone = boolConvert($row["phone"]);
        $user_calendar = boolConvert($row["calendar"]);
        $user_custom_hours = $row["customhrs"];
    } 
    
    else {
        echo 'Something went wrong';
    }

    // echo "user_onboarded: " . $user_onboarded . "<br>";
    // echo "user_prerec: " . $user_prerec . "<br>";
    // echo "user_phone: " . $user_phone . "<br>";
    // echo "user_calendar: " . $user_calendar . "<br>";
    // echo "user_custom_hours: " . $user_custom_hours . "<br>";
}

?>