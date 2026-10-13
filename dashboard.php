<?php

session_start();

include "config.php";
include "functions.php";

checkSession();

$username = $_SESSION["username"];

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>

<h1>Campus Facility Booking System</h1>

<h2>Welcome <?php echo htmlspecialchars($username); ?></h2>

<hr>

<p>
    <a href="add_facility.php">Add Facility</a>
</p>

<p>
    <a href="manage_facilities.php">Manage Facilities</a>
</p>

<p>
    <a href="booking.php">Make Booking</a>
</p>

<p>
    <a href="view_bookings.php">View Bookings</a>
</p>

<p>
    <a href="logout.php">Logout</a>
</p>

</body>
</html>