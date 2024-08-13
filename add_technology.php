<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $techName = $_POST['techName'];
    $description = $_POST['description'];
    $difficultyLevel = $_POST['difficultyLevel'];
    $useCases = $_POST['useCases'];
    $username = $_SESSION['username'];
    $paidLinks = isset($_POST['paidLinks']) ? $_POST['paidLinks'] : [];
    $unpaidLinks = isset($_POST['unpaidLinks']) ? $_POST['unpaidLinks'] : [];

    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("INSERT INTO technologies (techName, description, difficultyLevel, useCases, username) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $techName, $description, $difficultyLevel, $useCases, $username);
        $stmt->execute();
        $technologyId = $stmt->insert_id;

        
        $stmt = $conn->prepare("INSERT INTO links (technology_id, type, link) VALUES (?, 'paid', ?)");
        foreach ($paidLinks as $link) {
            if (!empty($link)) {
                $stmt->bind_param("is", $technologyId, $link);
                $stmt->execute();
            }
        }

        $stmt = $conn->prepare("INSERT INTO links (technology_id, type, link) VALUES (?, 'unpaid', ?)");
        foreach ($unpaidLinks as $link) {
            if (!empty($link)) {
                $stmt->bind_param("is", $technologyId, $link);
                $stmt->execute();
            }
        }

        $conn->commit();
        echo "Data inserted successfully!";
        header("Location: learn_index.php");
    } catch (Exception $e) {
        $conn->rollback();
        echo "Failed to insert data: " . $e->getMessage();
    }
}
?>
