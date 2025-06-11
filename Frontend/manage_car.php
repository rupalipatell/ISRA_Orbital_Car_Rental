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
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Cars - ISRA Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
body {
    background: url('../images/y.jpg') no-repeat center center/cover;
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
    .modal-bg { background: rgba(0,0,0,0.7); }
  </style>
</head>
<body>
  <header class="bg-black border-b-4 border-red-600 py-4 px-8 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-red-600">Manage Cars - ISRA Rental Orbit</h1>
    <nav>
      <a href="admin_dashboard.php" class="text-white hover:text-red-500 mr-4">Dashboard</a>
      <a href="../Backend/logout.php" class="text-white hover:text-red-500">Logout</a>
    </nav>
  </header>

  <main class="p-8 glass rounded-lg mx-auto max-w-6xl mt-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-3xl font-bold text-red-600">Car Listings</h2>
      <button id="btnAddCar" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded text-white">Add New Car</button>
    </div>

    <div id="messageBox" class="hidden mb-4 px-4 py-2 rounded text-white"></div>

    <table class="w-full text-left border border-gray-700 rounded">
      <thead class="bg-red-600">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Brand</th>
          <th class="px-4 py-2">Model</th>
          <th class="px-4 py-2">Capacity</th>
          <th class="px-4 py-2">Reg. Number</th>
          <th class="px-4 py-2">Rent/hr</th>
          <th class="px-4 py-2">Available</th>
          <th class="px-4 py-2">Image</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody id="carTableBody" class="bg-black bg-opacity-60"></tbody>
    </table>
  </main>

  <div id="carModal" class="fixed inset-0 hidden items-center justify-center modal-bg z-50">
    <div class="bg-black p-6 rounded-lg max-w-md w-full relative">
      <h3 id="modalTitle" class="text-xl font-bold mb-4 text-red-600">Add New Car</h3>
      <form id="carForm" class="space-y-4" enctype="multipart/form-data">
        <input type="hidden" id="carId" name="carId" />
        <div>
          <label class="block mb-1">Brand</label>
          <input type="text" name="brand" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Model</label>
          <input type="text" name="model" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Capacity</label>
          <input type="number" name="capacity" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Registration Number</label>
          <input type="text" name="registrationNumber" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Rent Per Hour</label>
          <input type="number" name="rentPerHour" step="0.01" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Available</label>
          <select name="availableStatus" class="w-full p-2 rounded bg-gray-900 text-white">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
        <div>
          <label class="block mb-1">Image</label>
          <input type="file" name="carImage" accept="image/*" class="w-full p-2 rounded bg-gray-900 text-white" />
        </div>
        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" id="modalCancel" class="px-4 py-2 bg-gray-600 hover:bg-gray-700">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      function loadCars() {
        $.getJSON('../Backend/cars/view_car.php', function(data) {
          let rows = '';
          data.forEach(car => {
            rows += `
              <tr class="border-b border-gray-700 hover:bg-gray-800">
                <td class="px-4 py-2">${car.VehicleID}</td>
                <td class="px-4 py-2">${car.Brand}</td>
                <td class="px-4 py-2">${car.Model}</td>
                <td class="px-4 py-2">${car.Capacity}</td>
                <td class="px-4 py-2">${car.RegistrationNumber}</td>
                <td class="px-4 py-2">${car.RentPerHour}</td>
                <td class="px-4 py-2">${car.AvailableStatus == 1 ? 'Yes' : 'No'}</td>
                <td class="px-4 py-2">
                  ${car.Image ? `<img src="../../uploads/${car.Image}" class="h-16 rounded">` : 'No image'}
                </td>
                <td class="px-4 py-2 space-x-2">
                  <button class="deleteBtn bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-white" data-id="${car.VehicleID}">Delete</button>
                </td>
              </tr>`;
          });
          $('#carTableBody').html(rows);
        });
      }

      function showMessage(message, success = true) {
        $('#messageBox')
          .removeClass('hidden bg-red-600 bg-green-600')
          .addClass(success ? 'bg-green-600' : 'bg-red-600')
          .text(message)
          .fadeIn();
        setTimeout(() => $('#messageBox').fadeOut().addClass('hidden').text(''), 3000);
      }

      $('#btnAddCar').click(() => {
        $('#carForm')[0].reset();
        $('#carModal').fadeIn().css('display', 'flex');
      });

      $('#modalCancel').click(() => $('#carModal').fadeOut());

      $('#carForm').submit(function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
          url: '../Backend/cars/add_car.php',
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(res) {
            let response = JSON.parse(res);
            showMessage(response.message, response.success);
            if (response.success) {
              $('#carModal').fadeOut();
              loadCars();
            }
          },
          error: () => showMessage('Server error', false)
        });
      });

      // Enhanced delete functionality
      $(document).on('click', '.deleteBtn', function() {
        const carId = $(this).data('id');
        const carRow = $(this).closest('tr');
        
        if (!confirm('Are you sure you want to delete this car? This action cannot be undone.')) {
          return;
        }

        // Show loading state
        $(this).prop('disabled', true).text('Deleting...');
        
        $.ajax({
          url: '../Backend/cars/delete_car.php',
          method: 'POST',
          dataType: 'json',
          data: { id: carId },
          success: function(response) {
            if (response.success) {
              // Visual feedback before removing
              carRow.addClass('bg-red-900');
              setTimeout(() => {
                carRow.fadeOut(400, function() {
                  $(this).remove();
                  showMessage(response.message, true);
                });
              }, 300);
            } else {
              showMessage(response.message || 'Failed to delete car', false);
              $('.deleteBtn[data-id="'+carId+'"]').prop('disabled', false).text('Delete');
            }
          },
          error: function(xhr, status, error) {
            showMessage('Error: ' + (xhr.responseJSON?.message || 'Server error'), false);
            $('.deleteBtn[data-id="'+carId+'"]').prop('disabled', false).text('Delete');
            console.error('Delete error:', error);
          }
        });
      });

      loadCars();
    });
  </script>
</body>
</html>