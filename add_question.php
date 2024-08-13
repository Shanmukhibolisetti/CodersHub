<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username']; 
    $question = $_POST['question'];

    $sql = "INSERT INTO questions (username, question) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $question);

    if ($stmt->execute()) {
        header("Location: code_index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

