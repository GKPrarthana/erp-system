<?php
include("../config/db.php");

$id = $_GET['id'] ?? 0;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM customer WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Redirect back to the list
header("Location: view.php");
exit;
