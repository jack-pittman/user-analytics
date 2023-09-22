<?php
// Start output buffering
ob_start();

// START SESSION, STORE TABLE CONTENT AS ACCESIBLE VARIABLE vvvv
session_start();
$_SESSION['tableContent'] = $tableContent;

if (!isset($_SESSION["user"])) {
    echo "ACCESS DENIED";
    exit();
}

require "../connect.php";

// BOOL TO STR METHOD

function boolToStr($val) {
    if ($val === null || $val === 0) {
        return "no";
    } else {
        return "yes";
    }
}

// ONBOARD TO STRING METHOD

function onboardConvert($val) {
    if ($val === null || $val != 5) {
        return "no";
    } else {
        return "yes";
    }
}

// RUN SQL QUERY

$user_name = "";
$search_term = "%"; 

$days_query = "

    SELECT username, firstname, lastname, email, method, DATE(datecreated) AS date,
        org, location, orgname AS org, onboarding AS onboarded, prerecorded, phone, meeting,
        
        CASE 
            WHEN 'true' IN (hours_monday, hours_tuesday, hours_wednesday, hours_thursday, hours_friday, hours_saturday, hours_sunday) THEN 'yes'
            ELSE 'no'
        END AS customhrs,
        
        count(callid) as numcalls
    FROM usernames
    LEFT JOIN orgs ON usernames.org = orgs.uniqueorg
    LEFT JOIN callrequests on linkeduser = uniqueid
    GROUP BY username

";

$stmt = mysqli_stmt_init($dbc);

if (!mysqli_stmt_prepare($stmt, $days_query)) {
    echo "SQL statement failed";
    exit();
} else {
    // mysqli_stmt_bind_param($stmt, "s", $search_term);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}

if (mysqli_num_rows($result) > 0) {

    // FIRST/LAST NAME, ORG NAME
    echo '<table id="example" class="display compact" style="width:100%">
        <thead>
            <tr>
                <th scope="col">username</th>
                <th scope="col">firstname</th>
                <th scope="col">lastname</th>
                <th scope="col">org</th>

                <th scope="col">email</th>
                <th scope="col">method</th>
                <th scope="col">date joined</th>
                <th scope="col">location</th>

                <th scope="col">onbrd?</th>
                <th scope="col">prerec?</th>
                <th scope="col">phone?</th>
                <th scope="col">calndr?</th>

                <th scope="col">customhrs?</th>

                <th scope="col">total calls</th>
                

            </tr>
        </thead>
        <tbody>';

    while ($row = mysqli_fetch_assoc($result)) {

        echo '<tr>
                <th scope="row">
                    <form method="post" action="3_user_summary.php">
                        <input type="hidden" name="username" value="' . $row['username'] . '">
                        <button type="submit" class="btn btn-link">
                            ' . $row['username'] . '
                        </button>
                    </form>
                </th>
                <td>' . $row["firstname"] . '</td>
                <td>' . $row["lastname"] . '</td>
                <td>' . $row["org"] . '</td>

                <td>' . $row["email"] . '</td>
                <td>@' . $row["method"] . '</td>
                <td>' . $row["date"] . '</td>
                <td>' . $row["location"] . '</td>
                
                <td>' . onboardConvert($row["onboarded"]) . '</td>
                <td>' . boolToStr($row["prerecorded"]) . '</td>
                <td>' . boolToStr($row["phone"]) . '</td>
                <td>' . boolToStr($row["meeting"]) . '</td>

                <td>' . $row["customhrs"] . '</td>

                <td>' . $row["numcalls"] . '</td>
            </tr>';

        // <td>'.$row["onboarded"].'</td>
        // <td>'.boolToStr($row["onboarded"]).'</td>
        // <td>'.gettype($row["onboarded"]).'</td>

    }

    echo '</tbody></table>';
} else {
    echo 'Error: Invalid Username.';
}


// Store the output in a variable
$tableContent = ob_get_clean();

// Close the database connection
mysqli_close($dbc);
?>