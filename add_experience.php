<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_SESSION['username'];
    $company = $conn->real_escape_string($_POST['company']);
    $role = $conn->real_escape_string($_POST['role']);
    $experience = $conn->real_escape_string($_POST['experience']);

    $sql = "INSERT INTO experiences (username, company, role, experience) VALUES ('$username', '$company', '$role', '$experience')";

    if ($conn->query($sql) === TRUE) {
        header('Location: exp_index.php');
        exit();
    } else {
        echo 'Error: ' . $conn->error;
    }
} else {
    echo 'Invalid request method';
}

$conn->close();
?>
