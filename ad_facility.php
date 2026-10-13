<?php

session_start();

include "config.php";
include "functions.php";

checkSession();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $facility_name = sanitize($_POST["facility_name"]);
    $facility_type = sanitize($_POST["facility_type"]);
    $location = sanitize($_POST["location"]);
    $capacity = trim($_POST["capacity"]);
    $status = sanitize($_POST["status"]);

    if (
        $facility_name == "" ||
        $facility_type == "" ||
        $location == "" ||
        $capacity == "" ||
        $status == ""
    ) {

        $message = "All fields are required.";

    } elseif (!is_numeric($capacity) || $capacity <= 0) {

        $message = "Capacity must be a valid number.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO facilities
            (facility_name, facility_type, location, capacity, status)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssis",
            $facility_name,
            $facility_type,
            $location,
            $capacity,
            $status
        );

        if ($stmt->execute()) {

            $message = "Facility added successfully.";

        } else {

            $message = "Failed to add facility.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Add Facility</title>
</head>

<body>

<h1>Add Facility</h1>

<?php if ($message != ""): ?>

<p>
    <?php echo htmlspecialchars($message); ?>
</p>

<?php endif; ?>

<form method="POST">

    <label>Facility Name:</label><br>

    <input
        type="text"
        name="facility_name"
        required
    >

    <br><br>

    <label>Facility Type:</label><br>

    <input
        type="text"
        name="facility_type"
        required
    >

    <br><br>

    <label>Location:</label><br>

    <input
        type="text"
        name="location"
        required
    >

    <br><br>

    <label>Capacity:</label><br>

    <input
        type="number"
        name="capacity"
        min="1"
        required
    >

    <br><br>

    <label>Status:</label><br>

    <select name="status" required>

        <option value="">-- Select Status --</option>

        <option value="Available">
            Available
        </option>

        <option value="Unavailable">
            Unavailable
        </option>

    </select>

    <br><br>

    <button type="submit">
        Add Facility
    </button>

</form>

<hr>

<a href="dashboard.php">
    Back to Main Page
</a>

</body>
</html>