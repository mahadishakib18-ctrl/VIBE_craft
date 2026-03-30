<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.html");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: contact_admin.php?message=Invalid+ID");
    exit;
}

$conn = new mysqli("localhost", "root", "root", "vibecraft");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("DELETE FROM contact_form WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: admin_dash.php?message=Message+deleted+successfully");
} else {
    header("Location: admin_dash.php?message=Failed+to+delete+message");
}

$stmt->close();
$conn->close();
exit;
?>
