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
  <title>Manage Users - Isra Rental Orbit</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
body {
    background: url('../images/c.jpg') no-repeat center center/cover;
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
    <h1 class="text-2xl font-bold text-red-600">Manage Users - ISRA Rental Orbit</h1>
    <nav>
      <a href="admin_dashboard.php" class="text-white hover:text-red-500 mr-4">Dashboard</a>
      <a href="../Backend/logout.php" class="text-white hover:text-red-500">Logout</a>
    </nav>
  </header>

  <main class="p-8 glass rounded-lg mx-auto max-w-6xl mt-8">
    <div class="mb-4">
      <h2 class="text-3xl font-bold text-red-600">User Listings</h2>
    </div>

    <!-- Message box for success or error -->
    <div id="messageBox" class="hidden mb-4 px-4 py-2 rounded text-white"></div>

    <table class="w-full text-left border border-gray-700 rounded">
      <thead class="bg-red-600">
        <tr>
          <th class="px-4 py-2">ID</th>
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Email</th>
          <th class="px-4 py-2">Phone Number</th>
          <th class="px-4 py-2">Address</th>
          <th class="px-4 py-2">Driving License</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody id="userTableBody" class="bg-black bg-opacity-60">
        <!-- Dynamic rows here -->
      </tbody>
    </table>
  </main>

  <!-- Edit User Modal -->
  <div id="userModal" class="fixed inset-0 hidden items-center justify-center modal-bg z-50">
    <div class="bg-black p-6 rounded-lg max-w-md w-full relative">
      <h3 id="modalTitle" class="text-xl font-bold mb-4 text-red-600">Edit User</h3>
      <form id="userForm" class="space-y-4">
        <input type="hidden" id="userId" name="userId" />
        <div>
          <label class="block mb-1">Name</label>
          <input type="text" id="name" name="name" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Email</label>
          <input type="email" id="email" name="email" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Phone Number</label>
          <input type="text" id="phoneNumber" name="phoneNumber" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div>
          <label class="block mb-1">Address</label>
          <textarea id="address" name="address" rows="2" class="w-full p-2 rounded bg-gray-900 text-white" required></textarea>
        </div>
        <div>
          <label class="block mb-1">Driving License Number</label>
          <input type="text" id="drivingLicenseNumber" name="drivingLicenseNumber" class="w-full p-2 rounded bg-gray-900 text-white" required />
        </div>
        <div class="flex justify-end space-x-3 mt-6">
          <button type="button" id="modalCancel" class="px-4 py-2 rounded bg-gray-600 hover:bg-gray-700">Cancel</button>
          <button type="submit" id="modalSubmit" class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white">Save</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      function loadUsers() {
        $.ajax({
          url: '../Backend/customers/get_all_customers.php',
          method: 'GET',
          dataType: 'json',
          success: function(data) {
            let rows = '';
            data.forEach(user => {
              rows += `
                <tr class="border-b border-gray-700 hover:bg-gray-800">
                  <td class="px-4 py-2">${user.CustomerID}</td>
                  <td class="px-4 py-2">${user.Name}</td>
                  <td class="px-4 py-2">${user.Email}</td>
                  <td class="px-4 py-2">${user.PhoneNumber}</td>
                  <td class="px-4 py-2">${user.Address}</td>
                  <td class="px-4 py-2">${user.DrivingLicenseNumber}</td>
                  <td class="px-4 py-2 space-x-2">
                    <button class="editBtn bg-yellow-500 hover:bg-yellow-600 px-3 py-1 rounded text-black" data-id="${user.CustomerID}">Edit</button>
                    <button class="deleteBtn bg-red-600 hover:bg-red-700 px-3 py-1 rounded text-white" data-id="${user.CustomerID}">Delete</button>
                  </td>
                </tr>`;
            });
            $('#userTableBody').html(rows);
          },
          error: function() {
            showMessage('Failed to fetch users.', false);
          }
        });
      }

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

      loadUsers();

      // Cancel modal
      $('#modalCancel').click(function() {
        $('#userModal').fadeOut();
      });

      // Edit user button click
      $(document).on('click', '.editBtn', function() {
        let id = $(this).data('id');
        $.ajax({
          url: '../Backend/customers/get_all_customers.php',
          method: 'GET',
          data: { id: id },
          dataType: 'json',
          success: function(user) {
            $('#modalTitle').text('Edit User');
            $('#userId').val(user.CustomerID);
            $('#name').val(user.Name);
            $('#email').val(user.Email);
            $('#phoneNumber').val(user.PhoneNumber);
            $('#address').val(user.Address);
            $('#drivingLicenseNumber').val(user.DrivingLicenseNumber);
            $('#userModal').fadeIn().css('display', 'flex');
          },
          error: function() {
            showMessage('Failed to fetch user details.', false);
          }
        });
      });

      // Submit edit form
      $('#userForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
          url: '../Backend/customers/edit_customer.php',
          method: 'POST',
          data: $(this).serialize(),
          dataType: 'json',
          success: function(res) {
            if (res.success) {
              showMessage(res.message, true);
              $('#userModal').fadeOut();
              loadUsers();
            } else {
              showMessage(res.message, false);
            }
          },
          error: function() {
            showMessage('Server error!', false);
          }
        });
      });

      // Delete user button click
      $(document).on('click', '.deleteBtn', function() {
        if (!confirm('Are you sure you want to delete this user?')) return;
        let id = $(this).data('id');
        $.ajax({
          url: '../Backend/customers/delete_customer.php',
          method: 'POST',
          data: { id: id },
          dataType: 'json',
          success: function(res) {
            if (res.success) {
              showMessage(res.message, true);
              loadUsers();
            } else {
              showMessage(res.message, false);
            }
          },
          error: function() {
            showMessage('Server error!', false);
          }
        });
      });
    });
  </script>
</body>
</html>
