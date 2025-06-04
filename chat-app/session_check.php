<?php
session_start();

// Timeout duration (in seconds)
$timeout = 600; // 10 minutes

if (isset($_SESSION['last_activity'])) {
    $inactive = time() - $_SESSION['last_activity'];
    if ($inactive > $timeout) {
        // Session expired due to inactivity
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit;
    }
}
// Update last activity time stamp
$_SESSION['last_activity'] = time();
?>
