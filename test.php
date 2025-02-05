<?php
$password = "User@123"; // Change this if needed
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo $hashedPassword;
?>
