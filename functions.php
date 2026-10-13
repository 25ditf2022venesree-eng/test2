<?php

function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}


function redirect($page)
{
    header("Location: " . $page);
    exit();
}


function checkSession()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["username"])) {
        redirect("login.php");
    }
}

?>