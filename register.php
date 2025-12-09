<?php
require_once 'config.php';
session_start();

$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Check if exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) { // SQLite specific check improvement
            $error = "Username or Email already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hash])) {
                header("Location: index.php?registered=1");
                exit;
            } else {
                $error = "Registration failed.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Micro-Habit Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .split-bg {
            background: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center/cover;
            position: relative;
        }
        .split-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to left, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.4));
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-0 m-0" style="min-height: 100vh; overflow: hidden;">

<div class="row w-100 h-100 g-0">
    
    <!-- Left Side Image -->
    <div class="col-lg-7 d-none d-lg-block split-bg position-relative order-2 order-lg-1">
        <div class="position-absolute bottom-0 end-0 p-5 z-2 text-white text-end" style="max-width: 600px;">
            <h2 class="display-6 fw-bold mb-3">"We are what we repeatedly do. Excellence, then, is not an act, but a habit."</h2>
            <div class="text-white-50">— Aristotle</div>
        </div>
    </div>

    <!-- Right Side Content -->
    <div class="col-lg-5 d-flex flex-column justify-content-center px-5 position-relative z-2 order-1 order-lg-2" style="background: var(--bg-dark);">
        <div class="mx-auto w-100" style="max-width: 400px;">
            <div class="d-flex align-items-center mb-5">
                <span class="d-flex align-items-center justify-content-center bg-primary bg-gradient rounded-3 me-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-activity text-white"></i>
                </span>
                <h4 class="m-0 fw-bold">MicroHabit</h4>
            </div>

            <h1 class="display-5 fw-bold mb-2">Create Account</h1>
            <p class="text-secondary mb-5">Start your journey to a better you.</p>

            <?php if($error): ?>
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" required placeholder="johndoe">
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg" required placeholder="you@company.com">
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 mb-4">Get Started</button>
            </form>
            
            <p class="text-center text-secondary">Already have an account? <a href="index.php" class="text-primary text-decoration-none fw-bold">Login</a></p>
        </div>
    </div>

</div>

</body>
</html>
