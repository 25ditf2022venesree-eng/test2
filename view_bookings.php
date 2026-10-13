<?php

session_start();

include "config.php";
include "functions.php";

checkSession();

$sql = "
    SELECT
        b.booking_id,
        b.booking_name,
        b.booking_date,
        b.start_time,
        b.end_time,
        b.booking_created,
        u.username,
        f.facility_name,
        f.facility_type,
        f.location
    FROM bookings b

    INNER JOIN users u
        ON b.user_id = u.user_id

    INNER JOIN facilities f
        ON b.facility_id = f.facility_id

    ORDER BY b.booking_date DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>View Bookings</title>

</head>

<body>

<h1>View Bookings</h1>

<?php if ($result->num_rows > 0): ?>

<table border="1" cellpadding="10">

<tr>

    <th>Booking Name</th>

    <th>Username</th>

    <th>Facility</th>

    <th>Type</th>

    <th>Location</th>

    <th>Date</th>

    <th>Start Time</th>

    <th>End Time</th>

    <th>Created</th>

</tr>

<?php while ($row = $result->fetch_assoc()): ?>

<tr>

<td>
<?php
echo htmlspecialchars(
    $row["booking_name"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["username"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["facility_name"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["facility_type"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["location"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["booking_date"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["start_time"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["end_time"]
);
?>
</td>

<td>
<?php
echo htmlspecialchars(
    $row["booking_created"]
);
?>
</td>

</tr>

<?php endwhile; ?>

</table>

<?php else: ?>

<p>No bookings found.</p>

<?php endif; ?>

<br>

<a href="dashboard.php">
    Back to Main Page
</a>

</body>

</html>