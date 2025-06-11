<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
  header("Location: login.php");
  exit;
}
include('../Backend/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
    background: url('../images/e.jpg') no-repeat center center/cover;
    color: white;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    /* Optional overlay effect */
    position: relative;
}
body::before {
    content: '';
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: rgba(0,0,0,0.6); /* dark overlay */
    z-index: -1;
}

    .glass {
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(10px);
    }
  </style>
</head>
<body>
  <header class="bg-black border-b-4 border-red-600 py-4 px-8 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-red-600">ISRA Rental Orbit - Admin Panel</h1>
    <nav>
      <a href="../Backend/logout.php" class="text-white hover:text-red-500">Logout</a>
    </nav>
  </header>

  <main class="p-10">
      <div class="grid grid-cols-1  lg:grid-cols-3 gap-6">

        <div class="bg-black bg-opacity-60 p-6 rounded-lg border border-red-600">
          <h3 class="text-xl font-semibold mb-2">View Rentals</h3>
          <p class="text-gray-300 mb-4">Check current and past rental records.</p>
          <a href="rental.php" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">Go</a>
        </div>

        <div class="bg-black bg-opacity-60 p-6 rounded-lg border border-red-600">
          <h3 class="text-xl font-semibold mb-2">Manage Cars</h3>
          <p class="text-gray-300 mb-4">Add, edit or remove car listings.</p>
          <a href="manage_car.php" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">Go</a>
        </div>

        <div class="bg-black bg-opacity-60 p-6 rounded-lg border border-red-600">
          <h3 class="text-xl font-semibold mb-2">User Accounts</h3>
          <p class="text-gray-300 mb-4">Manage user registrations and roles.</p>
          <a href="manage_user.php" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">Go</a>
        </div>

        <div class="bg-black bg-opacity-60 p-6 rounded-lg border border-red-600">
          <h3 class="text-xl font-semibold mb-2">Rental Requests</h3>
          <p class="text-gray-300 mb-4">Approve or reject new rental bookings.</p>
          <a href="rental_request.php" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">Go</a>
        </div>

      </div>
    </section>
  </main>
</body>
</html>