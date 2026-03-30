<?php
$host = "localhost";
$dbname = "vibecraft";
$username = "root";
$password = "root";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$event_type = $_POST['event-type'];
$feedback = $_POST['feedback'];
$rating = $_POST['rating'];

// Set default user_id to NULL since login is not required
$user_id = NULL ;

// Image upload
$image_path = "";
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
  $target_dir = "uploads/";
  if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true);
  }

  $image_info = pathinfo($_FILES["image"]["name"]);
  $ext = strtolower($image_info['extension']);
  $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $image_info['filename']);
  $new_filename = time() . "_" . $filename . "." . $ext;
  $target_file = $target_dir . $new_filename;

  if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
    $image_path = $target_file;
  }
}

// Insert into feedbacks table without requiring login
$stmt = $conn->prepare("INSERT INTO feedbacks (user_id, name, email, event_type, feedback, rating, image_path, is_approved) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, 0)");
$stmt->bind_param("issssis", $user_id, $name, $email, $event_type, $feedback, $rating, $image_path);

if ($stmt->execute()) {
  echo "<script>alert('Feedback submitted!'); window.location.href='feedback.html';</script>";
} else {
  echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
