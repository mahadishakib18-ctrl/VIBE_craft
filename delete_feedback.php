<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $conn = new mysqli("localhost", "root", "root", "vibecraft");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // First, delete image from folder if exists
    $result = $conn->query("SELECT image_path FROM feedbacks WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        $imagePath = $row['image_path'];
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Now delete feedback
    $conn->query("DELETE FROM feedbacks WHERE id = $id");
    $conn->close();

    header("Location: admin_dash.php?message=" . urlencode("Feedback deleted successfully."));
    exit;
}
?>
