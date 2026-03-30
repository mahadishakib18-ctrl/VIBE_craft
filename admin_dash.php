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

$pending = $conn->query("SELECT * FROM feedbacks WHERE is_approved = 0 ORDER BY submitted_at DESC");
$approved = $conn->query("SELECT * FROM feedbacks WHERE is_approved = 1 ORDER BY submitted_at DESC");
$contacts = $conn->query("SELECT * FROM contact_form ORDER BY id DESC");
$bookings = $conn->query("SELECT * FROM bookings WHERE status = 'pending' ORDER BY date ASC, time ASC");
$approvedBookings = $conn->query("SELECT * FROM bookings WHERE status = 'approved' ORDER BY date ASC, time ASC");
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard – VibeCraft</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-100 via-blue-100 to-purple-100 min-h-screen text-gray-800 font-sans p-6">

  <!-- Header -->
  <div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-extrabold text-purple-900 drop-shadow">🎛️ Admin Dashboard – VibeCraft</h1>
     <a href="add_history.php" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded shadow">+ Add Event History</a>
    <a href="home.html" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">Logout</a>
  </div>

  <!-- Alert Message -->
  <?php if (isset($_GET['message'])): ?>
    <div id="alert-msg" class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-center shadow-md max-w-xl mx-auto">
      <?= htmlspecialchars($_GET['message']) ?>
    </div>
    <script>
      setTimeout(() => {
        const msg = document.getElementById("alert-msg");
        if (msg) msg.style.display = "none";
      }, 3000);
    </script>
  <?php endif; ?>

  <!-- Helper function for delete link -->
  <?php function deleteButton($url) {
    return "<a href='$url' onclick=\"return confirm('Are you sure you want to delete this?');\" class='bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded'>Delete</a>";
  } ?>

  <!-- Section Template -->
  <?php function cardWrapperStart($title, $color) {
    echo "<section class='mb-12'><h2 class='text-2xl font-bold mb-4 text-$color-700'>$title</h2><div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6'>";
  } ?>

  <?php function cardWrapperEnd() { echo "</div></section>"; } ?>

  <!-- Pending Feedback -->
  <?php cardWrapperStart("🕒 Pending Feedback", "yellow"); ?>
    <?php if ($pending->num_rows > 0): while ($row = $pending->fetch_assoc()): ?>
      <div class="bg-white p-6 rounded-2xl shadow border border-yellow-300 hover:shadow-lg transition-all">
        <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
        <p><strong>Event:</strong> <?= htmlspecialchars($row['event_type']) ?></p>
        <p><strong>Rating:</strong> <?= htmlspecialchars($row['rating']) ?> ★</p>
        <p><strong>Feedback:</strong> <?= htmlspecialchars($row['feedback']) ?></p>
        <?php if (!empty($row['image_path'])): ?>
          <img src="<?= htmlspecialchars($row['image_path']) ?>" class="w-full h-40 object-cover mt-2 rounded-xl border" />
        <?php endif; ?>
        <div class="mt-4 flex gap-3">
          <a href="approve_feedback.php?id=<?= $row['id'] ?>&action=approve" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Approve</a>
          <?= deleteButton("delete_feedback.php?id={$row['id']}") ?>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p class="text-gray-500">No pending feedbacks.</p>
    <?php endif; ?>
  <?php cardWrapperEnd(); ?>

  <!-- Approved Feedback -->
  <?php cardWrapperStart("✅ Approved Feedback", "green"); ?>
    <?php if ($approved->num_rows > 0): while ($row = $approved->fetch_assoc()): ?>
      <div class="bg-white p-6 rounded-2xl shadow border border-green-300 hover:shadow-lg transition-all">
        <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
        <p><strong>Event:</strong> <?= htmlspecialchars($row['event_type']) ?></p>
        <p><strong>Rating:</strong> <?= htmlspecialchars($row['rating']) ?> ★</p>
        <p><strong>Feedback:</strong> <?= htmlspecialchars($row['feedback']) ?></p>
        <?php if (!empty($row['image_path'])): ?>
          <img src="<?= htmlspecialchars($row['image_path']) ?>" class="w-full h-40 object-cover mt-2 rounded-xl border" />
        <?php endif; ?>
        <div class="mt-4">
          <?= deleteButton("delete_feedback.php?id={$row['id']}") ?>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p class="text-gray-500">No approved feedbacks yet.</p>
    <?php endif; ?>
  <?php cardWrapperEnd(); ?>

  <!-- Contact Messages -->
  <?php cardWrapperStart("📩 Contact Messages", "blue"); ?>
    <?php if ($contacts && $contacts->num_rows > 0): while ($row = $contacts->fetch_assoc()): ?>
      <div class="bg-white p-6 rounded-2xl shadow border border-blue-300 hover:shadow-lg transition-all">
        <p><strong>Name:</strong> <?= htmlspecialchars($row['name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($row['email']) ?></p>
        <p><strong>Message:</strong> <?= nl2br(htmlspecialchars($row['message'])) ?></p>
        <p class="text-sm text-gray-500 mt-2">Submitted at: <?= htmlspecialchars($row['submitted_at']) ?></p>
        <div class="mt-4">
          <?= deleteButton("admin_delete_contact.php?id={$row['id']}") ?>
        </div>
      </div>
    <?php endwhile; else: ?>
      <p class="text-gray-500">No contact messages yet.</p>
    <?php endif; ?>
  <?php cardWrapperEnd(); ?>

 <!-- Pending Bookings -->
<?php cardWrapperStart("📝 Pending Booking Requests", "purple"); ?>
  <?php if ($bookings && $bookings->num_rows > 0): while ($booking = $bookings->fetch_assoc()): ?>
    <div class="bg-white p-6 rounded-2xl shadow border border-purple-300 hover:shadow-lg transition-all space-y-2 text-sm">
      <p><strong>Name:</strong> <?= htmlspecialchars($booking['name']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($booking['date']) ?> at <?= htmlspecialchars($booking['time']) ?></p>
      <p><strong>Event Type:</strong> <?= htmlspecialchars($booking['event_type']) ?></p>
      <p><strong>Decoration:</strong> <?= htmlspecialchars($booking['decoration']) ?></p>
      <p><strong>Duration:</strong> <?= htmlspecialchars($booking['duration']) ?> day(s)</p>
      <p><strong>Cuisine:</strong> <?= htmlspecialchars($booking['cuisine']) ?></p>
      <p><strong>Food Menu:</strong> <?= htmlspecialchars($booking['food_name']) ?> ($<?= htmlspecialchars($booking['food_price']) ?>)</p>
      <p><strong>Guests:</strong> <?= htmlspecialchars($booking['guests']) ?></p>
      <p><strong>Location:</strong> <?= htmlspecialchars($booking['location']) ?></p>
      <p><strong>Theme:</strong> <?= htmlspecialchars($booking['theme_text']) ?></p>
      <p><strong>Total Cost:</strong> $<?= number_format($booking['total_cost'], 2) ?></p>

      <?php if (!empty($booking['theme_images'])): ?>
        <img src="<?= htmlspecialchars($booking['theme_images']) ?>" class="w-full h-40 object-cover mt-2 rounded-xl border" />
      <?php endif; ?>

      <div class="mt-4 flex gap-3">
        <a href="booking_approve.php?id=<?= $booking['id'] ?>&action=approve" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">Approve</a>
        <?= deleteButton("booking_approve.php?id={$booking['id']}&action=reject") ?>
      </div>
    </div>
  <?php endwhile; else: ?>
    <p class="text-gray-500">No pending bookings.</p>
  <?php endif; ?>
<?php cardWrapperEnd(); ?>



<!-- Approved Bookings -->
<?php cardWrapperStart("🎉 Upcoming Approved Events", "emerald"); ?>
  <?php if ($approvedBookings && $approvedBookings->num_rows > 0): while ($booking = $approvedBookings->fetch_assoc()): ?>
    <div class="bg-emerald-50 p-6 rounded-2xl shadow border border-emerald-300 hover:shadow-lg transition-all space-y-2 text-sm">
      <p><strong>Name:</strong> <?= htmlspecialchars($booking['name']) ?></p>
      <p><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
      <p><strong>Date:</strong> <?= htmlspecialchars($booking['date']) ?> at <?= htmlspecialchars($booking['time']) ?></p>
      <p><strong>Event Type:</strong> <?= htmlspecialchars($booking['event_type']) ?></p>
      <p><strong>Decoration:</strong> <?= htmlspecialchars($booking['decoration']) ?></p>
      <p><strong>Duration:</strong> <?= htmlspecialchars($booking['duration']) ?> day(s)</p>
      <p><strong>Cuisine:</strong> <?= htmlspecialchars($booking['cuisine']) ?></p>
      <p><strong>Food Menu:</strong> <?= htmlspecialchars($booking['food_name']) ?> ($<?= htmlspecialchars($booking['food_price']) ?>)</p>
      <p><strong>Guests:</strong> <?= htmlspecialchars($booking['guests']) ?></p>
      <p><strong>Location:</strong> <?= htmlspecialchars($booking['location']) ?></p>
      <p><strong>Theme:</strong> <?= htmlspecialchars($booking['theme_text']) ?></p>
      <p><strong>Total Cost:</strong> $<?= number_format($booking['total_cost'], 2) ?></p>

      <?php if (!empty($booking['theme_images'])): ?>
        <img src="<?= htmlspecialchars($booking['theme_images']) ?>" class="w-full h-40 object-cover mt-2 rounded-xl border" />
      <?php endif; ?>

      <div class="mt-4">
        <?= deleteButton("delete_booking.php?id={$booking['id']}") ?>
      </div>
    </div>
  <?php endwhile; else: ?>
    <p class="text-gray-500">No approved bookings.</p>
  <?php endif; ?>
<?php cardWrapperEnd(); ?>


</body>
</html>

<?php $conn->close(); ?>


