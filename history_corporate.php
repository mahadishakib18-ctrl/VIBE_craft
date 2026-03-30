<?php
$conn = new mysqli("localhost", "root", "root", "vibecraft");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM event_history WHERE event_type = 'corporate' ORDER BY uploaded_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Corporate Event History – VibeCraft</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-indigo-100 via-white to-gray-100 text-gray-800 font-sans">

  <nav class="bg-white shadow p-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-indigo-600">Vibe<span class="text-gray-800">Craft</span></h1>
    <ul class="flex gap-4 text-sm font-medium text-gray-700">
      <li><a href="home.html" class="hover:text-indigo-600">Home</a></li>
      <li><a href="Event.html" class="hover:text-indigo-600">History</a></li>
      <li><a href="history_corporate.php" class="text-indigo-600 font-semibold">Corporate History</a></li>
    </ul>
  </nav>

  <header class="text-center py-10">
     <div class="absolute top-20 right-4">
    <a href="Event.html" class="inline-block px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg shadow hover:bg-yellow-600 transition">
      ← Back
    </a>
  </div>
    <h2 class="text-4xl font-extrabold text-indigo-600 mb-2">Corporate Experiences</h2>
    <p class="text-gray-700 text-lg">Explore past conferences, meetings, and launches</p>
  </header>

  <main class="max-w-6xl mx-auto px-4 pb-16 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php if ($result && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-white/60 backdrop-blur-md rounded-lg shadow-lg overflow-hidden">
          <?php if (!empty($row['image_path'])): ?>
            <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="Corporate Image" class="w-full h-72 object-cover">
          <?php endif; ?>
          <div class="p-4 text-gray-800">
            <p class="text-sm"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
            <p class="text-xs text-gray-500 mt-2">Uploaded: <?= date("F j, Y", strtotime($row['uploaded_at'])) ?></p>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="text-center text-gray-500 col-span-full">No corporate history added yet.</p>
    <?php endif; ?>
  </main>
  <footer class="bg-gradient-to-r from-purple-100 to-blue-100 text-center text-sm text-gray-600 py-4 shadow-inner">
    <p>© 2025 VibeCraft. All Rights Reserved.</p>
  </footer>

</body>
</html>

<?php $conn->close(); ?>
