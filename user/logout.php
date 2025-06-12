<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Destroy session data to log out the user
session_unset();
session_destroy();

// Redirect to the index page (which will show login/register options)
header('Location: ../index.php');
exit();
?>
