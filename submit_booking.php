<?php
session_start();

$conn = new mysqli("localhost", "root", "root", "vibecraft");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['customer_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$event_type = $_POST['event_type'];
$date = $_POST['date'];
$time = $_POST['time'];
$decoration = $_POST['decoration'];
$duration = $_POST['duration'];
$cuisine = $_POST['cuisine'];
$food_price = $_POST['food_price'];
$food_name = $_POST['food_name'];
$guests = $_POST['guests'];
$location = trim($_POST['location']);
$theme_text = $_POST['theme_text'];
$total_cost = $_POST['total_cost'];

// Fallback for empty location
if ($location === '') {
    $location = 'Not specified';
}

// Upload image
$target_dir = "uploads/";
$image_path = "";
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $filename = time() . '_' . basename($_FILES["image"]["name"]);
    $target_file = $target_dir . $filename;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $target_file;
    }
}

// Check for conflict with approved bookings
$check = $conn->prepare("SELECT id FROM bookings WHERE date = ? AND time = ? AND status = 'approved'");
$check->bind_param("ss", $date, $time);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    header("Location: dashboard.php?error=This date and time is already booked.");
    exit;
}
$check->close();

// Insert into bookings
$stmt = $conn->prepare("INSERT INTO bookings (
    name, phone, email, event_type, date, time, decoration, duration,
    category, food_price, food_name, guests, location, theme_text,
    total_cost, theme_images, status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind with corrected types
$stmt->bind_param("sssssssisssisdss",
    $name, $phone, $email, $event_type, $date, $time,
    $decoration, $duration, $cuisine, $food_price, $food_name,
    $guests, $location, $theme_text, $total_cost, $image_path
);

if ($stmt->execute()) {
    header("Location: dashboard.php?message=Booking submitted successfully!");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
