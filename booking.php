<?php

session_start();

include "config.php";
include "functions.php";

checkSession();

$message = "";

/*
    Get facilities
*/
$facilities = $conn->query(
    "SELECT facility_id, facility_name
     FROM facilities
     WHERE status = 'Available'
     ORDER BY facility_name"
);


/*
    Previous selected facility from cookie
*/
$selected_facility = "";

if (isset($_COOKIE["last_facility"])) {
    $selected_facility = $_COOKIE["last_facility"];
}


/*
    Process booking
*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $booking_name = sanitize($_POST["booking_name"]);
    $facility_id = trim($_POST["facility_id"]);
    $booking_date = trim($_POST["booking_date"]);
    $start_time = trim($_POST["start_time"]);
    $end_time = trim($_POST["end_time"]);

    if (
        $booking_name == "" ||
        $facility_id == "" ||
        $booking_date == "" ||
        $start_time == "" ||
        $end_time == ""
    ) {

        $message = "All fields are required.";

    } elseif (!is_numeric($facility_id)) {

        $message = "Invalid facility.";

    } elseif ($start_time >= $end_time) {

        $message = "End time must be later than start time.";

    } else {

        /*
            Validate facility exists
        */
        $stmt = $conn->prepare(
            "SELECT facility_id
             FROM facilities
             WHERE facility_id = ?
             AND status = 'Available'"
        );

        $stmt->bind_param("i", $facility_id);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 0) {

            $message = "Invalid facility selected.";

        } else {

            /*
                Get logged-in user
            */
            $username = $_SESSION["username"];

            $user_stmt = $conn->prepare(
                "SELECT user_id
                 FROM users
                 WHERE username = ?"
            );

            $user_stmt->bind_param("s", $username);

            $user_stmt->execute();

            $user_result = $user_stmt->get_result();

            $user = $user_result->fetch_assoc();

            $user_stmt->close();

            if (!$user) {

                $message = "User not found.";

            } else {

                $user_id = $user["user_id"];

                /*
                    Insert booking
                */
                $insert = $conn->prepare(
                    "INSERT INTO bookings
                    (
                        user_id,
                        facility_id,
                        booking_name,
                        booking_date,
                        start_time,
                        end_time
                    )
                    VALUES (?, ?, ?, ?, ?, ?)"
                );

                $insert->bind_param(
                    "iissss",
                    $user_id,
                    $facility_id,
                    $booking_name,
                    $booking_date,
                    $start_time,
                    $end_time
                );

                if ($insert->execute()) {

                    /*
                        Cookie expires after 1 day
                    */
                    setcookie(
                        "last_facility",
                        $facility_id,
                        time() + (24 * 60 * 60),
                        "/"
                    );

                    $message = "Booking successfully created.";

                    $selected_facility = $facility_id;

                } else {

                    $message = "Booking failed.";
                }

                $insert->close();
            }
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Facility Booking</title>

</head>

<body>

<h1>Facility Booking</h1>

<?php if ($message != ""): ?>

<p>
    <?php echo htmlspecialchars($message); ?>
</p>

<?php endif; ?>

<form method="POST">

    <label>Booking Name:</label><br>

    <input
        type="text"
        name="booking_name"
        required
    >

    <br><br>


    <label>Facility:</label><br>

    <select name="facility_id" required>

        <option value="">
            -- Select Facility --
        </option>

        <?php while ($facility = $facilities->fetch_assoc()): ?>

            <option
                value="<?php echo $facility["facility_id"]; ?>"
                <?php
                if (
                    $selected_facility ==
                    $facility["facility_id"]
                ) {
                    echo "selected";
                }
                ?>
            >

                <?php
                echo htmlspecialchars(
                    $facility["facility_name"]
                );
                ?>

            </option>

        <?php endwhile; ?>

    </select>

    <br><br>


    <label>Booking Date:</label><br>

    <input
        type="date"
        name="booking_date"
        required
    >

    <br><br>


    <label>Start Time:</label><br>

    <input
        type="time"
        name="start_time"
        required
    >

    <br><br>


    <label>End Time:</label><br>

    <input
        type="time"
        name="end_time"
        required
    >

    <br><br>


    <button type="submit">
        Book Facility
    </button>

</form>

<hr>

<a href="dashboard.php">
    Back to Main Page
</a>

</body>

</html>