<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "root", "vibecraft");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';

if (!$id || !$action) {
    header("Location: admin_dash.php?message=Invalid request");
    exit;
}

if ($action === 'approve') {
    $stmt = $conn->prepare("UPDATE bookings SET status='approved' WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dash.php?message=Booking approved successfully!");
} elseif ($action === 'reject') {
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin_dash.php?message=Booking rejected and deleted!");
} else {
    header("Location: admin_dash.php?message=Invalid action");
}

$conn->close();
?>
