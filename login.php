<?php
session_start();
$conn = new mysqli("localhost", "root", "root", "vibecraft");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$email = trim($_POST['email']);
$password = $_POST['password'];

if (empty($email) || empty($password)) {
  echo "<script>alert('Please fill in all fields.'); window.location.href='login.html';</script>";
  exit;
}

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
  if (password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $email; // ✅ Store email for dashboard.php
    header("Location: dashboard.php"); // ✅ Redirects to the PHP dashboard
    exit;
  }
}

echo "<script>alert('Invalid email or password!'); window.location.href='login.html';</script>";

$stmt->close();
$conn->close();
?>
