<?php
// Connect to database
$conn = new mysqli("localhost", "root", "root", "vibecraft");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get and sanitize POST data
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$username = trim($_POST['username']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$city = trim($_POST['city']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($password !== $confirm_password) {
    echo "<script>alert('❌ Passwords do not match.'); window.history.back();</script>";
    exit();
}

// Validate password strength
if (
    strlen($password) < 6 ||
    !preg_match('/[A-Z]/', $password) ||
    !preg_match('/[0-9]/', $password) ||
    !preg_match('/[^a-zA-Z0-9]/', $password) // any special character
) {
    echo "<script>alert('❌ Password must be at least 6 characters and include an uppercase letter, a number, and a special character.'); window.history.back();</script>";
    exit();
}

// Check for duplicate username or email
$checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$checkStmt->bind_param("ss", $email, $username);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo "<script>alert('❌ Username or Email already exists. Please choose another.'); window.history.back();</script>";
    $checkStmt->close();
    $conn->close();
    exit();
}
$checkStmt->close();

// Hash the password securely
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, phone, email, city, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $first_name, $last_name, $username, $phone, $email, $city, $hashed_password);

if ($stmt->execute()) {
    echo "<script>alert('✅ Registration successful! Please login.'); window.location.href='login.html';</script>";
} else {
    echo "<script>alert('❌ Registration failed: " . $conn->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>
