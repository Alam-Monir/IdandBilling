<?php
session_start();
include('../../config/dbcon.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['hashedPassword'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['firstName'] = $user['firstName'];
        $_SESSION['isAdmin'] = $user['isAdmin'];

        // Show success alert and redirect
        echo "<script>
            alert('Successfully logged in!');
            window.location.href = '" . ($user['isAdmin'] ? "/idandbilling/" : "/idandbilling/") . "';
        </script>";
        exit;
    } else {
        // Show error alert and stay on login page
        echo "<script>
            alert('Invalid email or password.');
            window.location.href = '/idandbilling/auth/login/';
        </script>";
        exit;
    }
}
