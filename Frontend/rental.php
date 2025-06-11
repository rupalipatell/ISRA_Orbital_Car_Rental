<?php
// rentals.php

$api_url = 'http://localhost/ISRA_Orbital_Car_Rental/Backend/rentals/get_all_rentals.php';

$json_data = file_get_contents($api_url);
$rentals_data = json_decode($json_data, true);

$current_rentals = [];
$past_rentals = [];

if ($rentals_data && $rentals_data['success']) {
    $current_rentals = $rentals_data['current_rentals'] ?? [];
    $past_rentals = $rentals_data['past_rentals'] ?? [];
} else {
    die("Failed to load rentals: " . ($rentals_data['error'] ?? 'Unknown error'));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rental Information - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
body {
    background: url('../images/home.jpg') no-repeat center center/cover;
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
    .glass { background: rgba(0,0,0,0.6); backdrop-filter: blur(10px); }
  </style>
</head>
<body>
  <header class="bg-black border-b-4 border-red-600 py-4 px-8 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-red-600">Rental Information - Isra Rental Orbit</h1>
    <nav>
      <a href="admin_dashboard.php" class="text-white hover:text-red-500 mr-4">Dashboard</a>
      <a href="../Backend/logout.php" class="text-white hover:text-red-500">Logout</a>
    </nav>
  </header>

  <main class="p-8 glass rounded-lg mx-auto max-w-7xl mt-8">
    <section class="mb-12">
      <h2 class="text-3xl font-bold text-red-500 mb-4">Current Rentals</h2>
      <table class="w-full text-left border border-gray-700 rounded">
        <thead class="bg-red-600">
          <tr>
            <th class="px-4 py-2">Car</th>
            <th class="px-4 py-2">Renter Name</th>
            <th class="px-4 py-2">Rental Start</th>
            <th class="px-4 py-2">Rental End</th>
            <th class="px-4 py-2">Status</th>
          </tr>
        </thead>
        <tbody class="bg-black bg-opacity-60">
          <?php if (count($current_rentals) === 0): ?>
            <tr><td colspan="5" class="text-center py-4 text-gray-400 italic">No current rentals found.</td></tr>
          <?php else: ?>
            <?php foreach ($current_rentals as $rental): ?>
              <tr class="border-b border-gray-700 hover:bg-gray-800">
                <td class="px-4 py-2"><?= htmlspecialchars($rental['Brand'] . ' ' . $rental['Model']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($rental['RenterName']) ?></td>
                <td class="px-4 py-2"><?= (new DateTime($rental['start_date']))->format('Y-m-d') ?></td>
                <td class="px-4 py-2"><?= (new DateTime($rental['end_date']))->format('Y-m-d') ?></td>
                <td class="px-4 py-2 text-green-400 font-semibold">Ongoing</td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>

    <section>
      <h2 class="text-3xl font-bold text-red-500 mb-4">Past Rentals</h2>
      <table class="w-full text-left border border-gray-700 rounded">
        <thead class="bg-red-600">
          <tr>
            <th class="px-4 py-2">Car</th>
            <th class="px-4 py-2">Renter Name</th>
            <th class="px-4 py-2">Rental Start</th>
            <th class="px-4 py-2">Rental End</th>
            <th class="px-4 py-2">Status</th>
          </tr>
        </thead>
        <tbody class="bg-black bg-opacity-60">
          <?php if (count($past_rentals) === 0): ?>
            <tr><td colspan="5" class="text-center py-4 text-gray-400 italic">No past rentals found.</td></tr>
          <?php else: ?>
            <?php foreach ($past_rentals as $rental): ?>
              <tr class="border-b border-gray-700 hover:bg-gray-800">
                <td class="px-4 py-2"><?= htmlspecialchars($rental['Brand'] . ' ' . $rental['Model']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($rental['RenterName']) ?></td>
                <td class="px-4 py-2"><?= (new DateTime($rental['start_date']))->format('Y-m-d') ?></td>
                <td class="px-4 py-2"><?= (new DateTime($rental['end_date']))->format('Y-m-d') ?></td>
                <td class="px-4 py-2 text-gray-400 font-semibold">Completed</td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>
</body>
</html>
