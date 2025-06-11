<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rental Requests - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
body {
    background: url('../images/x.jpg') no-repeat center center/cover;
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
    <h1 class="text-2xl font-bold text-red-600">Rental Requests - ISRA Rental Orbit</h1>
    <nav>
      <a href="admin_dashboard.php" class="text-white hover:text-red-500 mr-4">Dashboard</a>
      <a href="../Backend/logout.php" class="text-white hover:text-red-500">Logout</a>
    </nav>
  </header>

  <main class="p-8 glass rounded-lg mx-auto max-w-7xl mt-8">
    <div class="mb-4">
      <h2 class="text-3xl font-bold text-red-600">Pending Rental Requests</h2>
    </div>

    <!-- Message box -->
    <div id="messageBox" class="hidden mb-4 px-4 py-2 rounded text-white"></div>

    <table class="w-full text-left border border-gray-700 rounded">
      <thead class="bg-red-600">
        <tr>
          <th class="px-4 py-2">Request ID</th>
          <th class="px-4 py-2">Customer Name</th>
          <th class="px-4 py-2">Vehicle</th>
          <th class="px-4 py-2">Start Date</th>
          <th class="px-4 py-2">End Date</th>
          <th class="px-4 py-2">Total Hours</th>
          <th class="px-4 py-2">Total Amount</th>
          <th class="px-4 py-2">Status</th>
          <th class="px-4 py-2 text-center">Actions</th>
        </tr>
      </thead>
      <tbody id="requestTableBody" class="bg-black bg-opacity-60">
        <!-- dynamic rows -->
      </tbody>
    </table>
  </main>

  <script>
    $(document).ready(function () {
      function showMessage(message, success = true) {
        $('#messageBox')
          .removeClass('hidden bg-red-600 bg-green-600')
          .addClass(success ? 'bg-green-600' : 'bg-red-600')
          .text(message)
          .fadeIn();

        setTimeout(() => {
          $('#messageBox').fadeOut(() => {
            $('#messageBox').addClass('hidden').text('');
          });
        }, 3000);
      }

      function loadRequests() {
        $.ajax({
          url: '../Backend/request/get_all_requests.php',
          method: 'GET',
          dataType: 'json',
          success: function (data) {
            let rows = '';
            if (data.length === 0) {
              rows = `<tr><td colspan="9" class="text-center py-4">No rental requests found.</td></tr>`;
            } else {
              data.forEach(req => {
                rows += `
                  <tr class="border-b border-gray-700 hover:bg-gray-800">
                    <td class="px-4 py-2">${req.BookingID}</td>
                    <td class="px-4 py-2">${req.CustomerName}</td>
                    <td class="px-4 py-2">${req.VehicleBrand} ${req.VehicleModel}</td>
                    <td class="px-4 py-2">${req.StartDateTime}</td>
                    <td class="px-4 py-2">${req.EndDateTime}</td>
                    <td class="px-4 py-2">${req.TotalHours}</td>
                    <td class="px-4 py-2">₹${req.TotalAmount}</td>
                    <td class="px-4 py-2">${req.PaymentStatus}</td>
                    <td class="px-4 py-2 text-center">
                      ${req.PaymentStatus === 'Pending' ? `
                        <div class="flex flex-col sm:flex-row justify-center gap-2">
                          <button class="approveBtn bg-emerald-500 hover:bg-emerald-600 transition px-4 py-1 rounded-full text-sm font-semibold shadow-sm" data-id="${req.BookingID}">
                            ✅ Approve
                          </button>
                          <button class="rejectBtn bg-rose-500 hover:bg-rose-600 transition px-4 py-1 rounded-full text-sm font-semibold shadow-sm" data-id="${req.BookingID}">
                            ❌ Reject
                          </button>
                        </div>
                      ` : `<span class="text-gray-400">No actions</span>`}
                    </td>
                  </tr>
                `;
              });
            }
            $('#requestTableBody').html(rows);
          },
          error: function () {
            showMessage('Failed to load rental requests.', false);
          }
        });
      }

      loadRequests();

      $(document).on('click', '.approveBtn', function () {
        let id = $(this).data('id');
        if (!confirm('Are you sure you want to approve this request?')) return;

        $.ajax({
          url: '../Backend/request/update_request_status.php',
          method: 'POST',
          data: { id: id, status: 'Approved' },
          dataType: 'json',
          success: function (res) {
            if (res.success) {
              showMessage(res.message, true);
              loadRequests();
            } else {
              showMessage(res.message, false);
            }
          },
          error: function () {
            showMessage('Server error!', false);
          }
        });
      });

      $(document).on('click', '.rejectBtn', function () {
        let id = $(this).data('id');
        if (!confirm('Are you sure you want to reject this request?')) return;

        $.ajax({
          url: '../Backend/request/update_request_status.php',
          method: 'POST',
          data: { id: id, status: 'Rejected' },
          dataType: 'json',
          success: function (res) {
            if (res.success) {
              showMessage(res.message, true);
              loadRequests();
            } else {
              showMessage(res.message, false);
            }
          },
          error: function () {
            showMessage('Server error!', false);
          }
        });
      });
    });
  </script>
</body>
</html>
