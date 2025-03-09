<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    $_SESSION['toast'] = [
        'status' => 'error',
        'message' => 'Please login to access this page'
    ];
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Table</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
    <script src="./toast.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }

        /* Modern Table Styling */
        .table-responsive {
            border-radius: 8px;
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            white-space: nowrap;
            background: white;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 1rem;
            border-top: none;
            border-bottom: 2px solid #e2e8f0;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .btn-danger {
            background-color: #ef4444;
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
        }

        .btn-success {
            background-color: #10b981;
            border: none;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .btn-success:hover {
            background-color: #059669;
            transform: translateY(-1px);
        }
    </style>
</head>

<body class="py-4">
    <?php
    if (isset($_SESSION['toast'])) {
        $toast = $_SESSION['toast'];
        echo "<script>
            window.addEventListener('DOMContentLoaded', (event) => {
                const toast = new bootstrap.Toast(createToast('{$toast['status']}', '{$toast['message']}'));
                toast.show();
            });
        </script>";
        unset($_SESSION['toast']);
    }
    ?>
    <div class="container">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-semibold m-0">Employee List</h2>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                        <i class="fas fa-plus"></i> Add Employee
                    </button>
                </div>
                <div class="table-responsive">
                    <?php
                    require_once 'actions/connection.php';
                    $sql = 'SELECT * FROM employees ORDER BY dateAdded DESC';
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Position</th>
                                    <th>Salary</th>
                                    <th>Date Added</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>';

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='employee-row' style='cursor: pointer;' 
                                     data-id='" . $row['id'] . "' 
                                     data-firstname='" . htmlspecialchars($row['firstName']) . "'
                                     data-lastname='" . htmlspecialchars($row['lastName']) . "'
                                     data-email='" . htmlspecialchars($row['email']) . "'
                                     data-nin='" . htmlspecialchars($row['nin']) . "'
                                     data-position='" . htmlspecialchars($row['position']) . "'
                                     data-salary='" . htmlspecialchars($row['salary']) . "'>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td>" . $row['firstName'] . " " . $row['lastName'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['position'] . "</td>";
                            echo "<td>UGX." . number_format($row['salary'], 1) . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['dateAdded'])) . "</td>";
                            echo "<td onclick='event.stopPropagation();'>
                                    <form action='actions/deleteEmployee.php' method='POST' style='display: inline;' onsubmit='return confirm(\"Are you sure you want to delete this employee?\");'>
                                        <input type='hidden' name='id' value='" . $row['id'] . "'>
                                        <button type='submit' class='btn btn-danger btn-sm'>
                                            <i class='fas fa-trash'></i> Delete
                                        </button>
                                    </form>
                                  </td>";
                            echo "</tr>";
                        }
                        echo '</tbody></table>';
                    } else {
                        echo '<div class="text-center py-5">
                                <dotlottie-player 
                                    src="https://lottie.host/99e22931-ba19-41b2-83f7-a62aac4ef316/AZnfO45n5k.lottie"
                                    background="transparent" 
                                    speed="1" 
                                    style="width: 300px; height: 300px; margin: 0 auto;" 
                                    loop 
                                    autoplay>
                                </dotlottie-player>
                                <h4 class="text-muted fw-medium mt-3">No employees found</h4>
                                <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                                    <i class="fas fa-plus"></i> Add Employee
                                </button>
                              </div>';
                    }
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="addEmployeeModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="employeeForm" action="actions/addEmployee.php" method="POST" onsubmit="return validateForm()">
                        <div class="mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName">
                            <small class="text-danger" id="firstNameError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName">
                            <small class="text-danger" id="lastNameError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <small class="text-danger" id="emailError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="nin" class="form-label">NIN</label>
                            <input type="text" class="form-control" id="nin" name="nin">
                            <small class="text-danger" id="ninError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" name="position">
                            <small class="text-danger" id="positionError"></small>
                        </div>
                        <div class="mb-3">
                            <label for="salary" class="form-label">Salary</label>
                            <input type="text" class="form-control" id="salary" name="salary">
                            <small class="text-danger" id="salaryError"></small>
                        </div>
                        <div class="modal-footer justify-content-between align-items-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save Employee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="editEmployeeModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editEmployeeForm" action="actions/updateEmployee.php" method="POST">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control edit-input" id="edit_firstName" name="firstName">
                        </div>
                        <div class="mb-3">
                            <label for="edit_lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control edit-input" id="edit_lastName" name="lastName">
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control edit-input" id="edit_email" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="edit_nin" class="form-label">NIN</label>
                            <input type="text" class="form-control edit-input" id="edit_nin" name="nin">
                        </div>
                        <div class="mb-3">
                            <label for="edit_position" class="form-label">Position</label>
                            <input type="text" class="form-control edit-input" id="edit_position" name="position">
                        </div>
                        <div class="mb-3">
                            <label for="edit_salary" class="form-label">Salary</label>
                            <input type="text" class="form-control edit-input" id="edit_salary" name="salary">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="updateButton" disabled>Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validateForm() {
            // Get all form elements
            const firstName = document.getElementById('firstName');
            const lastName = document.getElementById('lastName');
            const nin = document.getElementById('nin');
            const email = document.getElementById('email');
            const position = document.getElementById('position');
            const salary = document.getElementById('salary');

            // Clear previous error messages
            clearErrors();

            // Flag to track validation status
            let isValid = true;

            // Validate First Name
            if (!firstName.value.trim()) {
                showError('firstNameError', 'First Name is required');
                isValid = false;
            }

            // Validate Last Name
            if (!lastName.value.trim()) {
                showError('lastNameError', 'Last Name is required');
                isValid = false;
            }

            // Validate NIN
            if (!nin.value.trim()) {
                showError('ninError', 'NIN is required');
                isValid = false;
            }
            if (!email.value.trim()) {
                showError('emailError', 'Email is required');
                isValid = false;
            }
            if (!position.value.trim()) {
                showError('positionError', 'Position is required');
                isValid = false;
            }
            if (!salary.value.trim()) {
                showError('salaryError', 'Salary is required');
                isValid = false;
            }

            return isValid;
        }


        function showError(elementId, message) {
            const errorElement = document.getElementById(elementId);
            errorElement.textContent = message;
        }

        function clearErrors() {
            const errorElements = document.getElementsByClassName('error-message');
            Array.from(errorElements).forEach(element => {
                element.textContent = '';
            });
        }

        // Store original values for comparison
        let originalValues = {};

        // Add click event to employee rows
        document.querySelectorAll('.employee-row').forEach(row => {
            row.addEventListener('click', function() {
                // Get data from row
                const data = this.dataset;

                // Store original values
                originalValues = {
                    ...data
                };

                // Fill form fields
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_firstName').value = data.firstname;
                document.getElementById('edit_lastName').value = data.lastname;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_nin').value = data.nin;
                document.getElementById('edit_position').value = data.position;
                document.getElementById('edit_salary').value = data.salary;

                // Show modal
                new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
            });
        });

        // Add change event listeners to all edit inputs
        document.querySelectorAll('.edit-input').forEach(input => {
            input.addEventListener('input', checkForChanges);
        });

        function checkForChanges() {
            const currentValues = {
                firstname: document.getElementById('edit_firstName').value,
                lastname: document.getElementById('edit_lastName').value,
                email: document.getElementById('edit_email').value,
                nin: document.getElementById('edit_nin').value,
                position: document.getElementById('edit_position').value,
                salary: document.getElementById('edit_salary').value
            };

            // Check if any value is different from original
            const hasChanges = Object.keys(currentValues).some(key =>
                currentValues[key] !== originalValues[key]
            );

            // Enable/disable update button
            document.getElementById('updateButton').disabled = !hasChanges;
        }

        // Reset form when modal is closed
        document.getElementById('editEmployeeModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('editEmployeeForm').reset();
            document.getElementById('updateButton').disabled = true;
        });

        function createToast(status, message) {
            const toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            toastContainer.innerHTML = `
                <div class="toast align-items-center text-white bg-${status === 'success' ? 'success' : 'danger'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;
            document.body.appendChild(toastContainer);
            return toastContainer.querySelector('.toast');
        }
    </script>
</body>

</html>