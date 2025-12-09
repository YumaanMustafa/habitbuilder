<?php
require_once 'config.php';
session_start();

$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Micro-Habit Builder</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .split-bg {
            background: url('https://images.unsplash.com/photo-1506784983877-45594efa4cbe?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') no-repeat center center/cover;
            position: relative;
        }
        .split-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.4));
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-0 m-0" style="min-height: 100vh; overflow: hidden;">

<div class="row w-100 h-100 g-0">
    <!-- Left Side Content -->
    <div class="col-lg-5 d-flex flex-column justify-content-center px-5 position-relative z-2" style="background: var(--bg-dark);">
        <div class="mx-auto w-100" style="max-width: 400px;">
            <div class="d-flex align-items-center mb-5">
                <span class="d-flex align-items-center justify-content-center bg-primary bg-gradient rounded-3 me-2" style="width: 40px; height: 40px;">
                    <i class="bi bi-activity text-white"></i>
                </span>
                <h4 class="m-0 fw-bold">MicroHabit</h4>
            </div>

            <h1 class="display-5 fw-bold mb-2">Welcome back</h1>
            <p class="text-secondary mb-5">Please enter your details to sign in.</p>

            <?php if($error): ?>
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger mb-4"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if(isset($_GET['registered'])): ?>
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success mb-4">Registration successful! Please login.</div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control form-control-lg" required placeholder="you@company.com">
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 mb-4">Sign in</button>
            </form>
            
            <p class="text-center text-secondary">Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-bold">Sign up</a></p>
        </div>
        
        <div class="mt-auto py-4 text-center">
            <small class="text-muted">&copy; <?php echo date('Y'); ?> Micro-Habit Builder</small>
        </div>
    </div>

    <!-- Right Side Image -->
    <div class="col-lg-7 d-none d-lg-block split-bg position-relative">
        <div class="position-absolute bottom-0 start-0 p-5 z-2 text-white" style="max-width: 600px;">
            <h2 class="display-6 fw-bold mb-3">"Small daily improvements are the key to staggering long-term results."</h2>
            <div class="d-flex align-items-center gap-3">
                <div class="d-flex">
                    <div class="rounded-circle bg-white border border-2 border-primary" style="width: 40px; height: 40px; margin-right: -10px;"></div>
                    <div class="rounded-circle bg-light border border-2 border-primary" style="width: 40px; height: 40px; margin-right: -10px;"></div>
                    <div class="rounded-circle bg-secondary border border-2 border-primary" style="width: 40px; height: 40px;"></div>
                </div>
                <div class="text-white-50">Join 1,000+ habit builders</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
