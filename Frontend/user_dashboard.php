<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
include('../Backend/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>User Dashboard - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { background-color: #0a0a0a; color: white; }
  </style>
</head>
<body class="p-6">
  <header class="bg-black border-b-4 border-red-600 p-4 mb-6 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-red-700">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <nav>
      <a href="my_rentals.php" class="text-white hover:text-red-500 mr-4">My Rentals</a>
      <a href="profile.php" class="text-white hover:text-red-500 mr-4">Profile</a>
      <a href="../Backend/logout.php" class="text-white hover:text-red-500">Logout</a>
    </nav>
  </header>

  <section class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold text-red-500 mb-4">Available Cars</h2>
    <div class="grid md:grid-cols-3 gap-6">
      <?php
      $result = $conn->query("SELECT * FROM vehicles WHERE AvailableStatus = 1");
      while ($row = $result->fetch_assoc()) {
        ?>
        <div class="bg-gray-900 p-4 rounded-lg shadow-lg relative">
          <img src="../uploads/<?php echo htmlspecialchars($row['Image']); ?>" class="w-full h-40 object-cover rounded mb-4" alt="Car Image">
          <h3 class="text-xl font-semibold text-white mb-1"><?php echo htmlspecialchars($row['Brand'] . ' ' . $row['Model']); ?></h3>
          <p class="text-gray-300">Capacity: <?php echo htmlspecialchars($row['Capacity']); ?></p>
          <p class="text-gray-300">Reg No: <?php echo htmlspecialchars($row['RegistrationNumber']); ?></p>
          <p class="text-red-400 font-bold mt-2">₹<?php echo htmlspecialchars($row['RentPerHour']); ?>/hour</p>

          <!-- Book Now button -->
          <button onclick="openBookingForm(<?php echo $row['VehicleID']; ?>, '<?php echo addslashes($row['Brand'] . ' ' . $row['Model']); ?>')" 
                  class="mt-4 inline-block bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white">
            Book Now
          </button>
        </div>
        <?php
      }
      ?>
    </div>
  </section>

  <!-- Booking Form Popup -->
<div id="bookingPopup" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden z-50">
    <div class="bg-gray-900 p-6 rounded-lg w-96 relative">
        <button onclick="closeBookingForm()" class="absolute top-2 right-2 text-red-600 font-bold text-xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-red-500">Book Car: <span id="carName"></span></h2>

        <form id="bookingForm" action="../Backend/rentals/book_rental.php" method="POST" class="space-y-4">
            <input type="hidden" name="vehicle_id" id="vehicleIdInput" value="">
            <input type="hidden" id="hourlyRate" value="">

            <label class="block">
                Start Date & Time:
                <input type="datetime-local" name="start_date" id="startDate" required 
                       class="w-full p-2 rounded bg-gray-800 border border-gray-700 text-white"
                       onchange="calculateTotal()">
            </label>

            <label class="block">
                End Date & Time:
                <input type="datetime-local" name="end_date" id="endDate" required 
                       class="w-full p-2 rounded bg-gray-800 border border-gray-700 text-white"
                       onchange="calculateTotal()">
            </label>

            <!-- Total Amount Display -->
            <div id="totalAmountContainer" class="hidden bg-gray-800 p-3 rounded-lg">
                <div class="flex justify-between items-center">
                    <span class="text-gray-300">Total Rental Amount:</span>
                    <span id="totalAmount" class="text-xl font-bold text-green-500">₹0</span>
                </div>
                <div class="text-xs text-gray-400 mt-1">(Inclusive of all taxes)</div>
            </div>
          <!-- Payment Screenshot Upload Section -->
<div id="paymentUploadSection" style="display:none;" class="bg-gray-800 p-4 rounded-lg mt-4">
  <label class="block mb-2 text-gray-300 font-semibold" for="paymentScreenshotInput">
    Upload Payment Screenshot:
  </label>
  <input
    type="file"
    name="payment_screenshot"
    id="paymentScreenshotInput"
    accept="image/*"
    class="mb-3 w-full text-gray-200 bg-gray-700 rounded p-1"
    onchange="previewPaymentScreenshot(event)"
  />
  <div id="paymentScreenshotPreview" class="mb-3">
    <!-- Image preview will appear here -->
  </div>
  <button type="button" onclick="clearPaymentScreenshot()" class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded text-sm">
    Clear
  </button>
</div>
            <!-- QR Code Section -->
            <div class="text-center border-t border-gray-700 pt-4">
                <p class="text-sm text-gray-300 mb-2">Scan this QR code for payment:</p>
                <img src="../images/qr.jpg" alt="Admin QR Code" class="mx-auto w-40 h-40 border border-gray-700 rounded mb-2">
            </div>

            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 py-2 rounded text-white font-bold">
                Confirm Booking
            </button>
        </form>
    </div>
</div>

<script>
    function openBookingForm(vehicleId, carName, rentPerHour) {
        document.getElementById('bookingPopup').classList.remove('hidden');
        document.getElementById('vehicleIdInput').value = vehicleId;
        document.getElementById('carName').textContent = carName;
        document.getElementById('hourlyRate').value = rentPerHour;
    }

    function closeBookingForm() {
        document.getElementById('bookingPopup').classList.add('hidden');
    }

    function calculateTotal() {
        const startDate = new Date(document.getElementById('startDate').value);
        const endDate = new Date(document.getElementById('endDate').value);
        const hourlyRate = parseFloat(document.getElementById('hourlyRate').value);
        
        console.log("Start Date:", startDate);
        console.log("End Date:", endDate);
        console.log("Hourly Rate:", hourlyRate);

        if (startDate && endDate && !isNaN(hourlyRate) && endDate > startDate) {
            const diffHours = (endDate - startDate) / (1000 * 60 * 60);
            const totalAmount = diffHours * hourlyRate;
            const totalWithTax = totalAmount * 1.18; // Adding 18% tax
            
            document.getElementById('totalAmount').textContent = '₹' + totalWithTax.toFixed(2);
            document.getElementById('totalAmountContainer').classList.remove('hidden');
        } else {
            document.getElementById('totalAmountContainer').classList.add('hidden');
            document.getElementById('paymentUploadSection').classList.add('hidden');
        }
    }

    // Update the button click in your car listing to pass the rentPerHour
    document.querySelectorAll('[onclick^="openBookingForm"]').forEach(button => {
        const originalOnClick = button.getAttribute('onclick');
        const match = originalOnClick.match(/openBookingForm\((\d+), '(.*?)'/);
        if (match) {
            const rentPerHour = button.closest('.bg-gray-900').querySelector('.text-red-400').textContent.replace('₹', '').replace('/hour', '');
            button.setAttribute('onclick', `openBookingForm(${match[1]}, '${match[2]}', ${rentPerHour})`);
        }
    });
    // Preview uploaded payment screenshot image
  function previewPaymentScreenshot(event) {
    const previewDiv = document.getElementById('paymentScreenshotPreview');
    previewDiv.innerHTML = ''; // Clear previous preview
    const file = event.target.files[0];
    if (file) {
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.alt = 'Payment Screenshot Preview';
      img.className = 'mx-auto max-w-full max-h-48 rounded border border-gray-600';
      previewDiv.appendChild(img);
    }
  }
  // Clear uploaded file input and preview
  function clearPaymentScreenshot() {
    const input = document.getElementById('paymentScreenshotInput');
    input.value = '';
    document.getElementById('paymentScreenshotPreview').innerHTML = '';
  }
</script>