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

    // Delete theme image if exists
    $result = $conn->query("SELECT theme_images FROM bookings WHERE id = $id");
    if ($result && $row = $result->fetch_assoc()) {
        $imagePath = $row['theme_images'];
        if (!empty($imagePath) && file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Delete the booking
    $conn->query("DELETE FROM bookings WHERE id = $id");
    $conn->close();

    header("Location: admin_dash.php?message=" . urlencode("Booking deleted successfully."));
    exit;
}
?>
