<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


if (!isset($_SESSION['user_id'])) {
    die("Access denied. Please log in.");
}

$user_id = $_SESSION['user_id'];
$api_url = 'http://localhost/ISRA_Orbital_Car_Rental/Backend/rentals/get_user_rentals.php?user_id=' . urlencode($user_id);
$json_data = @file_get_contents($api_url);
if ($json_data === false) {
    $error = error_get_last();
    die("Failed to fetch rentals. Error: " . $error['message']);
}


$rentals_data = json_decode($json_data, true);
if ($rentals_data === null) {
    die("Invalid JSON response.");
}

$current_rentals = [];
$past_rentals = [];

if ($rentals_data['success']) {
    foreach ($rentals_data['data'] as $rental) {
        $status = strtolower($rental['status']);
        if ($status === 'active') {
            $current_rentals[] = $rental;
        } elseif ($status === 'completed') {
            $past_rentals[] = $rental;
        }

    }
} else {
    die("Failed to load rentals: " . ($rentals_data['error'] ?? 'Unknown error'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Rentals - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      background: url('../images/home.jpg') no-repeat center center/cover;
      color: white;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      position: relative;
    }
    body::before {
      content: '';
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(0,0,0,0.6);
      z-index: -1;
    }
    .glass-effect {
      background: rgba(0, 0, 0, 0.65);
      backdrop-filter: blur(12px);
      border-radius: 1rem;
      max-width: 1000px;
      margin: 3rem auto;
      padding: 2.5rem 3rem;
      box-shadow: 0 8px 32px rgba(0,0,0,0.5);
      border: 1px solid rgba(220, 38, 38, 0.4);
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    th, td {
      padding: 0.75rem 1rem;
      border-bottom: 1px solid rgba(255,255,255,0.15);
    }
    th {
      background-color: rgba(220, 38, 38, 0.8);
      text-align: left;
      font-weight: 600;
    }
    tbody tr:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
    .status-ongoing {
      color: #34D399; /* Tailwind green-400 */
      font-weight: 600;
    }
    .status-completed {
      color: #9CA3AF; /* Tailwind gray-400 */
      font-weight: 600;
    }
    .no-data {
      text-align: center;
      color: #9CA3AF;
      padding: 1rem 0;
      font-style: italic;
    }
    a.back-link {
      display: inline-block;
      margin-top: 2rem;
      color: #EF4444;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    a.back-link:hover {
      text-decoration: underline;
      color: #B91C1C;
    }
    h1, h2 {
      user-select: none;
    }
  </style>
</head>
<body>
  <div class="glass-effect">
    <h1 class="text-4xl text-center font-bold mb-8" style="color:#DC2626;">My Rentals</h1>

    <!-- Current Rentals Section -->
    <section>
      <h2 class="text-2xl font-semibold mb-4" style="color:#EF4444;">Current Rentals</h2>
      <table>
        <thead>
          <tr>
            <th>Car</th>
            <th>Rental Start</th>
            <th>Rental End</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($current_rentals) === 0): ?>
            <tr><td colspan="4" class="no-data">No current rentals found.</td></tr>
          <?php else: ?>
            <?php foreach ($current_rentals as $rental): ?>
              <tr>
                <td><?= htmlspecialchars($rental['Brand'] . ' ' . $rental['Model']) ?></td>
                <td><?= htmlspecialchars((new DateTime($rental['start_date']))->format('Y-m-d')) ?></td>
                <td><?= htmlspecialchars((new DateTime($rental['end_date']))->format('Y-m-d')) ?></td>
                <td class="status-ongoing">Ongoing</td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- Past Rentals Section -->
    <section class="mt-10">
      <h2 class="text-2xl font-semibold mb-4" style="color:#EF4444;">Past Rentals</h2>
      <table>
        <thead>
          <tr>
            <th>Car</th>
            <th>Rental Start</th>
            <th>Rental End</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($past_rentals) === 0): ?>
            <tr><td colspan="4" class="no-data">No past rentals found.</td></tr>
          <?php else: ?>
            <?php foreach ($past_rentals as $rental): ?>
              <tr>
                <td><?= htmlspecialchars($rental['Brand'] . ' ' . $rental['Model']) ?></td>
                <td><?= htmlspecialchars((new DateTime($rental['start_date']))->format('Y-m-d')) ?></td>
                <td><?= htmlspecialchars((new DateTime($rental['end_date']))->format('Y-m-d')) ?></td>
                <td class="status-completed">Completed</td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- Back to Dashboard -->
    <a href="user_dashboard.php" class="back-link">← Back to dashboard</a>
  </div>
</body>
</html>
