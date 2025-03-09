<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts - Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="./toast.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            margin: 0 auto;
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
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
            <div class="card-header text-center bg-white border-0">
                <h4 class="fw-semibold mb-1">Create Account</h4>
                <p class="text-muted">Fill in your details to get started</p>
            </div>
            <div class="card-body p-4">
                <form action="actions/signup.php" method="POST">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control py-2" id="firstName" name="firstName" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control py-2" id="lastName" name="lastName" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control py-2" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control py-2" id="password" name="password" required>
                    </div>
                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary py-2">Sign Up</button>
                    </div>
                </form>
                <p class="text-center text-muted mt-3">
                    Already have an account? <a href="login.php" class="text-decoration-none fw-medium">Login</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>