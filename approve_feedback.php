<?php
$conn = new mysqli("localhost", "root", "root", "vibecraft");

if (isset($_GET['id']) && isset($_GET['action'])) {
  $id = intval($_GET['id']);
  $action = $_GET['action'];

  if ($action === 'approve') {
    $conn->query("UPDATE feedbacks SET is_approved = 1 WHERE id = $id");
    $message = "Feedback approved successfully.";
  } elseif ($action === 'reject') {
    // Permanently delete feedback
    $conn->query("DELETE FROM feedbacks WHERE id = $id");
    $message = "Feedback deleted successfully.";
  }

  $conn->close();
  header("Location: admin_dash.php?message=" . urlencode($message));
  exit;
}
?>
