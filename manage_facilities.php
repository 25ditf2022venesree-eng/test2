<?php

session_start();

include "config.php";
include "functions.php";

checkSession();

$sql = "
    SELECT
        f.facility_id,
        f.facility_name,
        f.facility_type,
        f.location,
        f.capacity,
        f.status,
        COUNT(b.booking_id) AS total_bookings
    FROM facilities f
    LEFT JOIN bookings b
        ON f.facility_id = b.facility_id
    GROUP BY
        f.facility_id,
        f.facility_name,
        f.facility_type,
        f.location,
        f.capacity,
        f.status
    ORDER BY f.facility_id DESC
";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Facilities</title>
</head>

<body>

<h1>Manage Facilities</h1>

<?php while ($row = $result->fetch_assoc()): ?>

<hr>

<p>
    <strong>Facility:</strong>
    <?php echo htmlspecialchars($row["facility_name"]); ?>
</p>

<p>
    <strong>Type:</strong>
    <?php echo htmlspecialchars($row["facility_type"]); ?>
</p>

<p>
    <strong>Location:</strong>
    <?php echo htmlspecialchars($row["location"]); ?>
</p>

<p>
    <strong>Capacity:</strong>
    <?php echo htmlspecialchars($row["capacity"]); ?>
</p>

<p>
    <strong>Status:</strong>
    <?php echo htmlspecialchars($row["status"]); ?>
</p>

<p>
    <strong>Total Bookings:</strong>
    <?php echo htmlspecialchars($row["total_bookings"]); ?>
</p>

<a href="edit_facility.php?id=<?php echo $row["facility_id"]; ?>">
    Edit
</a>

<form method="POST"
      action="delete_facility.php"
      style="display:inline;">

    <input
        type="hidden"
        name="facility_id"
        value="<?php echo $row["facility_id"]; ?>"
    >

    <button
        type="submit"
        onclick="return confirm('Delete this facility?');"
    >
        Delete
    </button>

</form>

<?php endwhile; ?>

<hr>

<a href="dashboard.php">
    Back to Main Page
</a>

</body>
</html>