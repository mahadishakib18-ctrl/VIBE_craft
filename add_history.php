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

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_type = $_POST['event_type'];
    $description = $_POST['description'];

    $upload_dir = "uploads/";
    $image_path = "";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    if (!empty($_FILES['image']['name'])) {
        $target_file = $upload_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_path = $target_file;
            } else {
                $message = "Error uploading image.";
            }
        } else {
            $message = "Invalid file type.";
        }
    }

    $stmt = $conn->prepare("INSERT INTO event_history (event_type, image_path, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $event_type, $image_path, $description);
    if ($stmt->execute()) {
        header("Location: admin_dash.php?message=History added successfully!");
        exit;
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Event History</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8 font-sans text-gray-800">

  <div class="max-w-2xl mx-auto bg-white p-8 rounded shadow">
    <h1 class="text-3xl font-bold mb-6 text-purple-700">Add Event History</h1>

    <?php if (!empty($message)): ?>
      <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <form action="" method="POST" enctype="multipart/form-data" class="space-y-6">
      <div>
        <label class="block font-semibold mb-1">Select Event Type:</label>
        <select name="event_type" required class="w-full border px-3 py-2 rounded">
          <option value="wedding">Wedding</option>
          <option value="birthday">Birthday</option>
          <option value="concert">Concert</option>
          <option value="corporate">Corporate</option>
          <option value="fashion">Fashion</option>
          <option value="cultural">Cultural</option>
        </select>
      </div>

      <div>
        <label class="block font-semibold mb-1">Upload Image:</label>
        <input type="file" name="image" accept="image/*" required class="w-full border px-3 py-2 rounded" />
      </div>

      <div>
        <label class="block font-semibold mb-1">Description:</label>
        <textarea name="description" rows="5" required class="w-full border px-3 py-2 rounded"></textarea>
      </div>

      <div class="flex justify-between">
        <a href="admin_dash.php" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">← Back</a>
        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded">Submit</button>
      </div>
    </form>
  </div>

</body>
</html>
