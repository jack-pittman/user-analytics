<?php

// CALL TYPES 

// Used as a reference for the graph on the user_summary page to give a summary of successful, missed, short, and ghost calls by each user. 

session_start();

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}

// GET USERNAME (STORED AS SESSION VARIABLE)

if (isset($_SESSION["username"])) {
    $username = $_SESSION["username"];
}

require "../connect.php";

// MASTER VARIABLES
// $short_call_time = 10; // amount of time that constitutes a short call, in seconds

$successful_calls = -1; 
$short_calls = -1; 
$ghost_calls = -1;
$missed_calls = -1; 

$not_other = -1; 
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
            return 0; 
            // 'Something went wrong';
        }
    }
}

//                                      SUCCESSFUL CALLS QUERY
$success_calls_query = "

SELECT username, count(callid) as num_calls
FROM usernames
INNER JOIN callrequests ON linkeduser = uniqueid
where activecall_visitor - ready > 10 AND activecall_user - ready > 10 
GROUP BY username
HAVING username = ?

";

$successful_calls = queryToValue($success_calls_query, $username, "num_calls", $dbc);

// echo "Number of Successful Calls: " . $successful_calls; 

//                                      SHORT CALLS QUERY
$short_calls_query = "

SELECT username, count(callid) as num_calls
FROM usernames
INNER JOIN callrequests ON linkeduser = uniqueid
where ready IS NOT NULL and activecall_visitor - ready < 10
GROUP BY username
HAVING username = ?

";

$short_calls = queryToValue($short_calls_query, $username, "num_calls", $dbc);

// echo "<br>Number of Short Calls (Butt-Dial): " . $short_calls; 



//                                      GHOST CALLS QUERY
$ghost_calls_query = "

SELECT count(username) as num_calls
FROM usernames u
INNER JOIN callrequests cr ON cr.linkeduser = u.uniqueid
WHERE cr.ready IS NULL AND (lastload - UNIX_TIMESTAMP(cr.date)) < 10
AND u.username = ?


";

$ghost_calls = queryToValue($ghost_calls_query, $username, "num_calls", $dbc);

// echo "<br>Number of Ghost Calls: " . $ghost_calls; 

//                                      MISSED CALLS QUERY
$missed_calls_query = "


SELECT count(username) as num_calls
FROM usernames u
INNER JOIN callrequests cr ON cr.linkeduser = u.uniqueid
WHERE cr.ready IS NULL AND (lastload - UNIX_TIMESTAMP(cr.date)) > 10
AND u.username = ?

";

$missed_calls = queryToValue($missed_calls_query, $username, "num_calls", $dbc);

// echo "<br>Number of Missed Calls: " . $missed_calls; 

// SET NOT OTHER VARIABLE (gets sum of all known categories, then subtract from total_calls)

$not_other = $successful_calls + $ghost_calls + $missed_calls + $short_calls;

// echo "<br>Not Other: " . $not_other;

?>