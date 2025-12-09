<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Micro-Habit Builder</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand text-white d-flex align-items-center" href="dashboard.php">
            <span class="d-flex align-items-center justify-content-center bg-primary bg-gradient rounded-3 me-2" style="width: 40px; height: 40px;">
                <i class="bi bi-activity text-white"></i>
            </span>
            <span>Micro<span class="text-primary">Habit</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if(isset($_SESSION['user_id'])): ?>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3" href="dashboard.php">
                        <i class="bi bi-grid me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-3" href="habit_list.php">
                        <i class="bi bi-list-check me-1"></i> Habits
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-primary btn-sm px-4 rounded-pill" href="create_habit.php">
                        <i class="bi bi-plus-lg me-1"></i> New Habit
                    </a>
                </li>
                <li class="nav-item dropdown ms-lg-3">
                     <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 32px; height: 32px; font-size: 0.8rem;">
                            <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 1)); ?>
                        </div>
                     </a>
                     <ul class="dropdown-menu dropdown-menu-end bg-dark border-secondary shadow-lg">
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                     </ul>
                </li>
            </ul>
            <?php else: ?>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Login</a>
                </li>
            </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 110px; padding-bottom: 60px; min-height: 80vh;">
