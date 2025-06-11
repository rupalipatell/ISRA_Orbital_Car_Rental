<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('../Backend/db.php');

if (!isset($_SESSION['user_id'])) {
  header("Location: ../Frontend/login.php");
  exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT CustomerID, Name, Email,DrivingLicenseNumber, PhoneNumber FROM customers WHERE CustomerID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  die("User not found.");
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Profile - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: url('../images/a.jpg') no-repeat center center/cover;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: white;
      min-height: 100vh;
      margin: 0;
      padding: 0;
      position: relative;
    }

    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(0, 0, 0, 0.65);
      z-index: -1;
    }

    .glass-card {
      background: rgba(0, 0, 0, 0.55);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      box-shadow: 0 0 30px rgba(193, 20, 20, 0.6);
      border-radius: 1.5rem;
      transition: all 0.3s ease;
    }

    .glass-card:hover {
      background: rgba(0, 0, 0, 0.45);
      box-shadow: 0 0 40px rgba(225, 5, 5, 0.8);
    }
  </style>
</head>
<body class="flex flex-col items-center justify-center px-4 py-10">

  <header class="w-full max-w-4xl text-center mb-10">
    <h1 class="text-5xl font-extrabold text-red-700 tracking-tight drop-shadow-lg">My Profile</h1>
    <p class="text-gray-300 mt-2">Welcome back, <?= htmlspecialchars($user['Name']) ?> !👋</p>
  </header>

  <main class="glass-card w-full max-w-2xl p-10 space-y-6 text-white">

    <div>
      <h2 class="text-lg font-semibold text-red-600 mb-1">Name:</h2>
      <p class="text-white text-xl break-words"><?= htmlspecialchars($user['Name']) ?></p>
    </div>

    <div>
      <h2 class="text-lg font-semibold text-red-600 mb-1">Email:</h2>
      <p class="text-white text-xl break-words"><?= htmlspecialchars($user['Email']) ?></p>
    </div>

    <div>
      <h2 class="text-lg font-semibold text-red-600 mb-1">Driving License Number:</h2>
      <p class="text-white text-xl break-words"><?= htmlspecialchars($user['DrivingLicenseNumber']) ?></p>
    </div>

    <div>
      <h2 class="text-lg font-semibold text-red-600 mb-1">Phone Number:</h2>
      <p class="text-white text-xl break-words"><?= htmlspecialchars($user['PhoneNumber']) ?></p>
    </div>

    <div class="pt-6 border-t border-gray-600 text-center">
      <a href="../Frontend/user_dashboard.php" class="inline-block mt-4 px-6 py-3 rounded-full bg-red-600 hover:bg-red-700 text-white font-semibold tracking-wide transition-all duration-300 shadow-md">
        ← Back to Dashboard
      </a>
    </div>

  </main>

</body>
</html>
