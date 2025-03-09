<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Prepare SQL query with placeholders
    $sql = "UPDATE employees 
            SET firstName = ?, 
                lastName = ?, 
                email = ?, 
                nin = ?, 
                position = ?, 
                salary = ? 
            WHERE id = ?";

    // Prepare statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters (s = string, d = decimal/double, i = integer)
        $stmt->bind_param(
            "sssssdi",
            $_POST['firstName'],
            $_POST['lastName'],
            $_POST['email'],
            $_POST['nin'],
            $_POST['position'],
            $_POST['salary'],
            $_POST['id']
        );

        // Execute statement and check if successful
        if ($stmt->execute()) {
            // Check if any rows were affected
            if ($stmt->affected_rows > 0) {
                $_SESSION['toast'] = [
                    'status' => 'success',
                    'message' => 'Employee updated successfully'
                ];
            } else {
                $_SESSION['toast'] = [
                    'status' => 'info',
                    'message' => 'No changes were made'
                ];
            }
        } else {
            $_SESSION['toast'] = [
                'status' => 'error',
                'message' => 'Error updating record: ' . $stmt->error
            ];
        }

        // Close statement
        $stmt->close();
    } else {
        $_SESSION['toast'] = [
            'status' => 'error',
            'message' => 'Error preparing statement: ' . $conn->error
        ];
    }

    // Close connection
    $conn->close();

    // Redirect back to index.php
    header('Location: ../index.php');
    exit();
} else {
    $_SESSION['toast'] = [
        'status' => 'error',
        'message' => 'Invalid request method'
    ];
    header('Location: ../index.php');
    exit();
}
