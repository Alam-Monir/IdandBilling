<?php
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['isAdmin']) {
    echo "<script>
        alert('Access Denied. You do not have permission to access this page.');
        window.history.back();
    </script>";
    exit;
}
