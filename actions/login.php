<?php
session_start();
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare SQL query with placeholder for email
    $sql = "SELECT id, firstName, lastName, email, password FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind email parameter
        $stmt->bind_param("s", $email);

        // Execute the query
        if ($stmt->execute()) {
            // Store the result
            $stmt->store_result();

            // Check if email exists
            if ($stmt->num_rows == 1) {
                // Bind the result variables
                $stmt->bind_result($id, $firstName, $lastName, $dbEmail, $hashedPassword);
                $stmt->fetch();

                // Verify password
                if (password_verify($password, $hashedPassword)) {
                    // Password is correct, start a new session
                    session_regenerate_id();

                    // Store data in session variables
                    $_SESSION["loggedin"] = true;
                    $_SESSION["id"] = $id;
                    $_SESSION["email"] = $dbEmail;
                    $_SESSION["firstName"] = $firstName;
                    $_SESSION["lastName"] = $lastName;

                    $_SESSION['toast'] = [
                        'status' => 'success',
                        'message' => 'Welcome back, ' . $firstName . '!'
                    ];

                    // Redirect to index page
                    header("Location: ../index.php");
                    exit();
                } else {
                    $_SESSION['toast'] = [
                        'status' => 'error',
                        'message' => 'Invalid password'
                    ];
                    header("Location: ../login.php");
                    exit();
                }
            } else {
                $_SESSION['toast'] = [
                    'status' => 'error',
                    'message' => 'No account found with that email'
                ];
                header("Location: ../login.php");
                exit();
            }
        } else {
            $_SESSION['toast'] = [
                'status' => 'error',
                'message' => 'Oops! Something went wrong. Please try again later.'
            ];
            header("Location: ../login.php");
            exit();
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
} else {
    $_SESSION['toast'] = [
        'status' => 'error',
        'message' => 'Invalid request method'
    ];
    header("Location: ../login.php");
    exit();
}
