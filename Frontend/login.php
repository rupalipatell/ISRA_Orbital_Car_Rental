<?php
session_start();
if (isset($_SESSION['user_id'])) {
    echo "You are already logged in as user. <a href='user_dashboard.php'>Go to Dashboard</a>";
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
  <title>Login - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: url('../images/c.jpg') no-repeat center center/cover;
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .glass-effect {
  background: rgba(0, 0, 0, 0.65);                /* slightly darker background */
  backdrop-filter: blur(20px);                    /* strong glass blur */
  -webkit-backdrop-filter: blur(20px);            /* Safari support */
  box-shadow: 0 8px 40px 0 rgba(139, 0, 0, 0.6);   /* deeper/darker red glow */
  border-radius: 1rem;                            /* rounded edges */
  transition: all 0.3s ease;
}

.glass-effect:hover {
  background: rgba(0, 0, 0, 0.55);
  box-shadow: 0 10px 50px 0 rgba(139, 0, 0, 0.8); /* dark red glow on hover */
}

  </style>
</head>
<body class="h-screen overflow-hidden">
  <div class="h-full flex items-center justify-center bg-black bg-opacity-80">
    <div class="glass-effect p-10 rounded-3xl shadow-2xl flex w-full max-w-5xl">
      <div class="w-1/2 hidden md:block">
        <img src="../images/road.jpg" alt="Login Visual" class="rounded-3xl w-full h-auto object-cover" />
      </div>
      <div class="w-full md:w-1/2 pl-0 md:pl-12 flex flex-col justify-center">
        <h2 class="text-4xl text-red-500 font-extrabold mb-8 text-center tracking-wide">Login to ISRA Rental Orbit</h2>

        <div class="flex justify-center gap-6 mb-6">
          <button onclick="showLogin('user')" class="bg-red-600 hover:bg-red-700 transition transform hover:scale-105 text-white py-2 px-6 rounded-full">User</button>
          <button onclick="showLogin('admin')" class="bg-red-600 hover:bg-red-700 transition transform hover:scale-105 text-white py-2 px-6 rounded-full">Admin</button>
        </div>

        <!-- User Login -->
        <form id="user-login" class="hidden flex flex-col gap-5" action="../Backend/auth_user.php" method="POST">
          <input type="email" name="email" placeholder="User Email" class="p-3 rounded-lg bg-black bg-opacity-50 border border-red-500 text-white w-full focus:ring-2 focus:ring-red-500" />
          <input type="password" name="password" placeholder="Password" class="p-3 rounded-lg bg-black bg-opacity-50 border border-red-500 text-white w-full focus:ring-2 focus:ring-red-500" />
          <button class="bg-red-600 hover:bg-red-700 transition transform hover:scale-105 text-white font-semibold py-3 rounded-lg w-full" name ='login' type="submit">Login as User</button>
        </form>

        <!-- Admin Login -->
        <form id="admin-login" class="hidden flex flex-col gap-5" action="../Backend/auth_admin.php" method="POST">
          <input type="email" name="admin_email" placeholder="Admin Email" class="p-3 rounded-lg bg-black bg-opacity-50 border border-red-500 text-white w-full focus:ring-2 focus:ring-red-500" />
          <input type="password" name="admin_password" placeholder="Password" class="p-3 rounded-lg bg-black bg-opacity-50 border border-red-500 text-white w-full focus:ring-2 focus:ring-red-500" />
          <button class="bg-red-600 hover:bg-red-700 transition transform hover:scale-105 text-white font-semibold py-3 rounded-lg w-full" name="login" type="submit">Login as Admin</button>
        </form>

        <p class="mt-6 text-gray-300 text-center text-sm">
          Don't have an account? <a href="register.php" class="text-red-400 hover:text-red-500 font-semibold hover:underline">Register</a>
          | <a href="index.html" class="text-red-400 hover:text-red-500 font-semibold hover:underline">Home</a>
        </p>
      </div>
    </div>
  </div>

  <script>
    function showLogin(role) {
      document.getElementById('user-login').style.display = role === 'user' ? 'flex' : 'none';
      document.getElementById('admin-login').style.display = role === 'admin' ? 'flex' : 'none';
    }
    // Optional: show user login by default
    showLogin('user');
  </script>
</body>
</html>

