<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input values
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // First check if email already exists
    $checkEmail = "SELECT id FROM users WHERE email = ?";
    if ($stmt = $conn->prepare($checkEmail)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $_SESSION['toast'] = [
                'status' => 'error',
                'message' => 'This email is already registered'
            ];
            header("Location: ../signup.php");
            exit();
        }
        $stmt->close();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare insert statement
    $sql = "INSERT INTO users (firstName, lastName, email, password) VALUES (?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);

        // Attempt to execute
        if ($stmt->execute()) {
            $_SESSION['toast'] = [
                'status' => 'success',
                'message' => 'Registration successful! Please login.'
            ];
            header("Location: ../login.php");
            exit();
        } else {
            $_SESSION['toast'] = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again.'
            ];
            header("Location: ../signup.php");
            exit();
        }

        // Close statement
        $stmt->close();
    } else {
        $_SESSION['toast'] = [
            'status' => 'error',
            'message' => 'Database error: ' . $conn->error
        ];
        header("Location: ../signup.php");
        exit();
    }

    // Close connection
    $conn->close();
} else {
    $_SESSION['toast'] = [
        'status' => 'error',
        'message' => 'Invalid request method'
    ];
    header("Location: ../signup.php");
    exit();
}
