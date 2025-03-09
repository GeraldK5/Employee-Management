<?php
require_once 'connection.php';

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare SQL query with placeholders
    $sql = "INSERT INTO employees (firstName, lastName, nin, email, position, salary) 
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters (s = string, d = decimal/double)
    $stmt->bind_param(
        "sssssd",
        $_POST['firstName'],
        $_POST['lastName'],
        $_POST['nin'],
        $_POST['email'],
        $_POST['position'],
        $_POST['salary']
    );

    // Execute statement and check if successful
    if ($stmt->execute()) {
        $_SESSION['toast'] = [
            'status' => 'success',
            'message' => 'Employee added successfully'
        ];
    } else {
        $_SESSION['toast'] = [
            'status' => 'error',
            'message' => 'Error: ' . $stmt->error
        ];
    }

    // Close statement
    $stmt->close();
    $conn->close();

    // Redirect back to index.php
    header('Location: ../index.php');
    exit();
} else {
    // If not POST request, return error
    header('Content-Type: application/json');
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Invalid request method'
    ));
}
