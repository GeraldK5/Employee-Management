<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // Prepare SQL query with placeholder
    $sql = "DELETE FROM employees WHERE id = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameter (i = integer)
    $stmt->bind_param("i", $_POST['id']);

    // Execute statement and check if successful
    if ($stmt->execute()) {
        $_SESSION['toast'] = [
            'status' => 'success',
            'message' => 'Employee deleted successfully'
        ];
    } else {
        $_SESSION['toast'] = [
            'status' => 'error',
            'message' => 'Error: ' . $stmt->error
        ];
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Redirect back to index.php
    header('Location: ../index.php');
    exit();
} else {
    $_SESSION['toast'] = [
        'status' => 'error',
        'message' => 'Invalid request'
    ];
    header('Location: ../index.php');
    exit();
}
