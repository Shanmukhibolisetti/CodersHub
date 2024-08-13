<?php
session_start();
include 'db.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $department = $_POST['department'];
    $domain = $_POST['domain'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $username = $_SESSION['username'];

    if (!empty($department) && !empty($domain) && !empty($title) && !empty($description) && !empty($username)) {
        $sql = "INSERT INTO projects (department, domain, title, description, username) VALUES ('$department','$domain','$title','$description','$username')";

        if ($conn->query($sql) === TRUE) {
            header('Location: project_index.php');
            exit();
        } else {
            echo 'Error: ' . $conn->error;
        }
    } else {
        $error = "All fields are required.";
    }
}

if (isset($error)) {
    echo "<p style='color: red;'>$error</p>";
}
$conn->close();

?>
