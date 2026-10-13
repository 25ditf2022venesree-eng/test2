<?php

session_start();

include "config.php";
include "functions.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = sanitize($_POST["username"]);

    if ($username === "admin") {

        $_SESSION["username"] = $username;

        redirect("dashboard.php");

    } else {

        $error = "Invalid username.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Campus Facility Booking System</title>
</head>

<body>

<h1>Campus Facility Booking System</h1>

<h2>Login</h2>

<?php if ($error != ""): ?>

    <p style="color:red;">
        <?php echo htmlspecialchars($error); ?>
    </p>

<?php endif; ?>

<form method="POST">

    <label>Username:</label>

    <input
        type="text"
        name="username"
        required
    >

    <br><br>

    <button type="submit">Login</button>

</form>

</body>
</html>