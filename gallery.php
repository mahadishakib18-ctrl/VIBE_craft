<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VibeCraft - Gallery</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-green-100 via-blue-100 to-purple-100 ">

 <!-- ✨ Navbar -->
  <nav class="bg-gradient-to-r from-blue-200 via-purple-100 to-green-200 p-4 shadow-md flex justify-between items-center ">
    <h1 class="text-3xl font-bold text-purple-700 tracking-wide">Vibe<span class="text-gray-900">Craft</span></h1>
    <ul class="flex gap-6 text-base font-medium text-gray-700">
      <li><a href="home.html" class="hover:text-purple-700 transition">Home</a></li>
      <li><a href="Event.html" class="hover:text-purple-700 transition">History</a></li>
      <li><a href="register.html" class="hover:text-purple-700 transition">Register</a></li>
      <li><a href="login.html" class="hover:text-purple-700 transition">Login</a></li>
      <li><a href="about.html" class="hover:text-purple-700 transition">About</a></li>
      <li><a href="contact.html" class="hover:text-purple-700 transition">Contact</a></li>
      <li><a href="feedback.html" class="hover:text-purple-700 transition">Feedback</a></li>
      <li><a href="admin_login.html" class="hover:text-purple-700 transition">Admin</a></li>
      <li><a href="gallery.php" class="hover:text-purple-700 transition">Reviews</a></li>
    </ul>
  </nav>

  <h1 class="text-3xl text-center font-bold text-purple-700 mb-6 mt-4">VibeCraft Feedback Gallery</h1>

  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-6xl mx-auto">
    <?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Connect to the database
    $conn = new mysqli("localhost", "root", "root", "vibecraft");
    if ($conn->connect_error) {
      die("<p class='col-span-3 text-center text-red-600'>Database connection failed: " . $conn->connect_error . "</p>");
    }

    // Fetch only approved feedback entries
    $sql = "SELECT name, event_type, feedback, rating, image_path, submitted_at 
            FROM feedbacks 
            WHERE is_approved = 1 
            ORDER BY submitted_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row['name']);
        $event = htmlspecialchars($row['event_type']);
        $feedback = htmlspecialchars($row['feedback']);
        $rating = (int)$row['rating'];
        $image_path = $row['image_path'];
        $date = date('M d, Y', strtotime($row['submitted_at']));

        echo '<div class="bg-white p-4 rounded-xl shadow-md hover:shadow-xl transition text-gray-800">';

        // Image (with fallback if file doesn't exist)
        if (!empty($image_path) && file_exists($image_path)) {
          echo '<img src="' . htmlspecialchars($image_path) . '" alt="Event Image" class="rounded h-40 w-full object-cover mb-3">';
        } else {
          echo '<div class="h-40 w-full bg-gray-200 flex items-center justify-center mb-3 text-gray-500">No Image</div>';
        }

        // User Info
        echo "<h2 class='font-bold text-purple-700 text-lg mb-1'>{$name}</h2>";
        echo "<p class='text-sm italic text-gray-600 mb-1'>{$event} • {$date}</p>";
        echo "<p class='text-sm mb-2'>{$feedback}</p>";

        // Star Rating
        echo '<div class="text-yellow-500 text-lg">';
        for ($i = 1; $i <= 5; $i++) {
          echo $i <= $rating ? '★' : '☆';
        }
        echo '</div>';

        echo '</div>';
      }
    } else {
      echo '<p class="col-span-3 text-center text-gray-600">No feedback approved yet.</p>';
    }

    $conn->close();
    ?>
  </div>
  <footer class="bg-gradient-to-r from-purple-100 to-blue-100 text-center text-sm text-gray-600 py-4 shadow-inner">
    <p>© 2025 VibeCraft. All Rights Reserved.</p>
  </footer>
</body>
</html>
