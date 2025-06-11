<?php
session_start();
if (isset($_SESSION['user_id'])) {
    echo "You are already logged in as user. <a href='dashboard.php'>Go to Dashboard</a>";
    exit;
}
if (isset($_SESSION['admin_id'])) {
    echo "You are already logged in as admin. <a href='admin_dashboard.php'>Go to Admin Dashboard</a>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background-image: url('../images/c.jpg');
      background-size: cover;
      background-position: center;
    }
    .glass-effect {
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(8px);
    }
  </style>
</head>
<body class="h-screen">
  <div class="h-full flex items-center justify-center bg-black bg-opacity-50">
    <div class="glass-effect p-8 rounded-2xl shadow-2xl flex w-full max-w-4xl">
      <div class="w-1/2 hidden md:block">
        <img src="../images/road.jpg" alt="Register Visual" class="rounded-xl w-100 h-auto" />
      </div>
      <div class="w-full md:w-1/2 pl-0 md:pl-10 flex flex-col justify-center">
        <h2 class="text-4xl text-red-600 font-bold mb-6 text-center">Register to Isra Rental Orbit</h2>
        
        <div class="flex justify-center gap-4 mb-4">
          <button onclick="showForm('user')" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">User</button>
          <button onclick="showForm('admin')" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded">Admin</button>
        </div>

        <!-- User Form -->
        <form id="user-form" style="display: none;" action="../Backend/auth_user.php" method="POST">
          <input type="text" name="name" placeholder="Full Name" required class="mb-3 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <input type="email" name="email" placeholder="Email" required class="mb-3 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <input type="text" name="phone" placeholder="Phone Number" required class="mb-3 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <input type="text" name="address" placeholder="Address" required class="mb-3 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <input type="text" name="dl_number" placeholder="Driving License Number" required class="mb-3 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <input type="password" name="password" placeholder="Password" required class="mb-3 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <button type="submit" name="register" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded w-full">Register as User</button>
        </form>

        <!-- Admin Form -->
        <form id="admin-form" style="display: none;" action="../Backend/auth_admin.php" method="POST">
          <input type="text" name="admin_name" placeholder="Admin Name" required class="mb-4 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <input type="email" name="admin_email" placeholder="Admin Email" required class="mb-4 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <input type="password" name="admin_password" placeholder="Admin Password" required class="mb-4 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />
          <!--<input type="text" name="admin_code" placeholder="Admin Code" required class="mb-4 p-3 rounded bg-black bg-opacity-60 border border-red-600 text-white w-full" />-->
          <button type="submit" name="register" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded w-full">Register as Admin</button>
        </form>

        <p class="mt-4 text-gray-300 text-center">
          Already have an account?
          <a href="login.php" class="text-red-500 hover:underline">Login</a>
          | <a href="index.html" class="text-red-500 hover:underline">Home</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    function showForm(role) {
      document.getElementById('user-form').style.display = role === 'user' ? 'block' : 'none';
      document.getElementById('admin-form').style.display = role === 'admin' ? 'block' : 'none';
    }
  </script>
</body>
</html>
