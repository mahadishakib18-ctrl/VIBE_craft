<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.html");
    exit;
}

$conn = new mysqli("localhost", "root", "root", "vibecraft");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_SESSION['user_email'];

// Fetch username from `username` column
$conn->set_charset("utf8");
$nameResult = $conn->query("SELECT username FROM users WHERE email = '$email'");
$username = 'User';
if ($nameResult && $nameResult->num_rows > 0) {
    $row = $nameResult->fetch_assoc();
    $username = htmlspecialchars($row['username']);
}

// Get latest booking status
$statusResult = $conn->query("SELECT status FROM bookings WHERE email = '$email' ORDER BY id DESC LIMIT 1");

$status = 'No Booking Found';
if ($statusResult && $statusResult->num_rows > 0) {
    $row = $statusResult->fetch_assoc();
    $status = ucfirst($row['status']);
}

// Get approved booking details
$approvedResult = $conn->query("SELECT * FROM bookings WHERE email = '$email' AND status = 'approved' ORDER BY id DESC LIMIT 1");
$approvedBooking = null;
if ($approvedResult && $approvedResult->num_rows > 0) {
    $approvedBooking = $approvedResult->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>VibeCraft - Event Booking</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function updateFoodOptions() {
      const cuisine = document.getElementById('cuisine').value;
      const foodSelect = document.getElementById('food');
      const foodNameInput = document.getElementById('food_name');
      foodSelect.innerHTML = '<option value="">Select Food Menu</option>';
      let menus = [];

      if (cuisine === 'Chinese') {
        menus = [
          { name: 'Egg Fried Rice + Grill Chicken + Veg + Water', price: 250 },
          { name: 'Mixed Rice + Peri Peri Chicken + Beef + Soft Drink', price: 300 }
        ];
      } else if (cuisine === 'Indian') {
        menus = [
          { name: 'Butter Chicken + Naan + Veg Pulao + Lassi', price: 280 },
          { name: 'Chicken Biryani + Kebab + Raita + Gulab Jamun', price: 320 }
        ];
      } else if (cuisine === 'Italian') {
        menus = [
          { name: 'Pasta + Garlic Bread + Salad + Juice', price: 270 },
          { name: 'Pizza + Chicken Wings + Dessert + Soft Drink', price: 310 }
        ];
      }

      menus.forEach(m => {
        const option = document.createElement('option');
        option.value = m.price;
        option.setAttribute('data-name', m.name);
        option.textContent = `${m.name} ($${m.price})`;
        foodSelect.appendChild(option);
      });
    }

    function calculateTotal() {
      const foodSelect = document.getElementById('food');
      const selectedOption = foodSelect.options[foodSelect.selectedIndex];
      const foodPrice = parseInt(foodSelect.value || 0);
      const foodName = selectedOption.getAttribute('data-name') || '';
      const guests = parseInt(document.getElementById('guests').value || 0);
      const deco = document.getElementById('decoration').value;
      const days = parseInt(document.getElementById('duration').value || 1);
      let decorationCost = 0;
      if (deco === 'Excellent') decorationCost = 5000;
      else if (deco === 'Good') decorationCost = 3000;
      else if (deco === 'Average') decorationCost = 2000;

      const total = ((foodPrice * guests) + decorationCost) * days;
      document.getElementById('total_cost_display').value = '$' + total.toFixed(2);
      document.getElementById('total_cost').value = total;
      document.getElementById('food_name').value = foodName;
    }

    document.addEventListener("DOMContentLoaded", () => {
      document.getElementById('image-upload').addEventListener('change', function () {
        const preview = document.getElementById('preview');
        const file = this.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
          };
          reader.readAsDataURL(file);
        }
      });
    });

    function scrollToSection(id) {
      document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
    }
  </script>
</head>
<body class="bg-gradient-to-b from-green-100 via-blue-100 to-purple-100 min-h-screen">

  <!-- Logout Button -->
  <a href="home.html" class="absolute top-4 right-4 bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700 z-50 shadow-md">Logout</a>

  <!-- Username Display -->
  <div class="absolute top-4 left-4 bg-white/90 px-4 py-1 rounded text-purple-800 font-semibold shadow z-50">
    👋 Welcome, <?php echo $username; ?>
  </div>

  <!-- Error Message -->
  <?php if (isset($_GET['error'])): ?>
    <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 max-w-xl mx-auto text-center shadow">
      <?= htmlspecialchars($_GET['error']) ?>
    </div>
  <?php endif; ?>

  <!-- Booking Status -->
  <div class="text-center text-2xl font-bold p-6 
    <?php echo $status === 'Approved' ? 'text-green-700' : ($status === 'Pending' ? 'text-yellow-600' : 'text-red-600'); ?>">
    Booking Status: <?php echo $status; ?>
  </div>

  <!-- Approved Booking Display -->
  <?php if ($approvedBooking): ?>
    <section class="max-w-3xl mx-auto bg-green-100 border border-green-300 p-6 mt-4 mb-6 rounded-xl shadow">
      <h2 class="text-xl font-bold text-green-700 mb-4 text-center">🎉 Your Approved Booking Details</h2>
      <div class="space-y-2 text-lg">
        <p><strong>Event Type:</strong> <?= htmlspecialchars($approvedBooking['event_type']) ?></p>
        <p><strong>Date & Time:</strong> <?= htmlspecialchars($approvedBooking['date']) ?> at <?= htmlspecialchars($approvedBooking['time']) ?></p>
        <p><strong>Guests:</strong> <?= htmlspecialchars($approvedBooking['guests']) ?></p>
        <p><strong>Food Menu:</strong> <?= htmlspecialchars($approvedBooking['food_name']) ?></p>
        <p><strong>Decoration:</strong> <?= htmlspecialchars($approvedBooking['decoration']) ?></p>
        <p><strong>Duration:</strong> <?= htmlspecialchars($approvedBooking['duration']) ?> day(s)</p>
        <p><strong>Location:</strong> <?= htmlspecialchars($approvedBooking['location']) ?></p>
        <p><strong>Total Cost:</strong> $<?= number_format($approvedBooking['total_cost'], 2) ?></p>

        <?php if (!empty($approvedBooking['theme_images'])): ?>
          <div>
            <p><strong>Theme Image:</strong></p>
            <img src="<?= htmlspecialchars($approvedBooking['theme_images']) ?>" alt="Theme Image" class="w-60 h-40 object-cover rounded shadow border">
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endif; ?>

  <!-- Booking Form -->
  <section id="booking" class="max-w-3xl mx-auto bg-white/90 p-10 rounded-2xl shadow-xl">
    <h1 class="text-3xl font-bold text-purple-700 mb-6 text-center">Book Your Event</h1>
    <form oninput="calculateTotal()" action="submit_booking.php" method="POST" enctype="multipart/form-data" class="space-y-5">
      <input type="text" name="customer_name" placeholder="Your Name" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
      <input type="text" name="phone" placeholder="Phone Number" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
      <input type="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" readonly class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">

      <select name="event_type" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
        <option value="">Select Event Type</option>
        <option value="Wedding">Wedding</option>
        <option value="Birthday">Birthday</option>
        <option value="Corporate">Corporate</option>
        <option value="Fashion show">Fashion show</option>
        <option value="Cultural Fest">Cultural Fest</option>
      </select>

      <input type="date" name="date" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
      <select name="time" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
        <option value="">Select Time</option>
        <option value="Morning">Morning</option>
        <option value="Afternoon">Afternoon</option>
        <option value="Evening">Evening</option>
        <option value="Night">Night</option>
      </select>

      <select name="decoration" id="decoration" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
        <option value="">Decoration Quality</option>
        <option value="Excellent">Excellent (Cost: $5000)</option>
        <option value="Good">Good (Cost: $3000)</option>
        <option value="Average">Average (Cost: $2000)</option>
      </select>

      <input type="number" name="duration" id="duration" placeholder="Duration (in days)" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">

      <select name="cuisine" id="cuisine" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm" onchange="updateFoodOptions()">
        <option value="">Select Cuisine</option>
        <option value="Chinese">Chinese</option>
        <option value="Indian">Indian</option>
        <option value="Italian">Italian</option>
      </select>

      <select name="food_price" id="food" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
        <option value="">Select Food Menu</option>
      </select>
      <input type="hidden" name="food_name" id="food_name">

      <input type="number" name="guests" id="guests" placeholder="Number of Guests" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
      
      <input type="text" name="location" placeholder="Event Location" required class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">

      <label class="block font-semibold">Upload an Event Photo</label>
      <input type="file" accept="image/*" name="image" id="image-upload" class="w-full px-4 py-2 rounded border border-gray-300 bg-white" />
      <div class="mt-3">
        <img id="preview" class="rounded w-40 h-40 object-cover hidden" />
      </div>

      <input type="text" name="theme_text" placeholder="Describe your theme" class="w-full p-3 border border-purple-300 rounded-xl shadow-sm">
      <input type="hidden" name="total_cost" id="total_cost">
      <input type="text" id="total_cost_display" readonly placeholder="Total Cost" class="w-full p-3 border border-purple-200 rounded-xl bg-gray-100 text-purple-700 font-bold">

      <button type="submit" class="bg-purple-700 text-white w-full py-3 rounded-xl font-semibold shadow hover:bg-purple-800 transition">Submit Booking</button>
    </form>
  </section>

  <footer class="bg-gradient-to-r from-purple-100 to-blue-100 text-center text-sm text-gray-600 py-4 shadow-inner">
    <p>© 2025 VibeCraft. All Rights Reserved.</p>
  </footer>
</body>
</html>
