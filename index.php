<?php
session_start();
// Redirect user depending on login status
if (isset($_SESSION['user_id'])) {
    header("Location: events.php");
} else {
    header("Location: login.php");
}
exit();
?>
