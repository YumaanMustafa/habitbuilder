<?php
require_once 'config.php';
require_login();

if (!isset($_GET['id'])) {
    header("Location: habit_list.php");
    exit;
}

$habit_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch Habit Info
$stmt = $pdo->prepare("SELECT * FROM habits WHERE id = ? AND user_id = ?");
$stmt->execute([$habit_id, $user_id]);
$habit = $stmt->fetch();

if (!$habit) {
    header("Location: habit_list.php");
    exit;
}

// Fetch History (Last 30 entries)
$stmt = $pdo->prepare("SELECT * FROM daily_completions WHERE habit_id = ? ORDER BY completed_date DESC LIMIT 30");
$stmt->execute([$habit_id]);
$history = $stmt->fetchAll();

// Prepare Data for Chart (Last 14 days)
$labels = [];
$data = [];
for($i = 13; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('M d', strtotime($date));
    
    // Check if completed
    $found = false;
    foreach($history as $h) {
        if($h['completed_date'] == $date) {
            $found = true;
            break;
        }
    }
    $data[] = $found ? 1 : 0;
}

// Calculate Completion Rate
$total_last_30 = count($history);
$rate = round(($total_last_30 / 30) * 100);
?>
<?php include 'includes/header.php'; ?>

<div class="row mb-5 align-items-center fade-in-up">
    <div class="col-md-8">
        <a href="habit_list.php" class="text-secondary text-decoration-none mb-3 d-inline-block hover-white transition">
            <i class="bi bi-arrow-left me-1"></i> Back to Habits
        </a>
        <h1 class="fw-bold mb-1"><?php echo htmlspecialchars($habit['goal']); ?></h1>
        <p class="text-primary fs-4"><?php echo htmlspecialchars($habit['micro_action']); ?></p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="d-inline-block text-center glass-card px-4 py-3">
            <div class="text-secondary small text-uppercase fw-bold mb-1">Current Streak</div>
            <div class="display-6 fw-bold text-warning streak-flame">
                <i class="bi bi-fire"></i> <?php echo $habit['current_streak']; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Chart Section -->
    <div class="col-12 mb-4 fade-in-up delay-1">
        <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="m-0">Last 14 Days Activity</h5>
                <span class="badge bg-primary bg-opacity-10 text-primary">visualization</span>
            </div>
            <div style="height: 250px;">
                <canvas id="habitChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-4 mb-4 fade-in-up delay-2">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-center text-center position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 w-100 h-1 bg-gradient-to-r from-transparent via-white to-transparent opacity-25"></div>
            <h5 class="text-secondary text-uppercase fs-7 ls-1">Longest Streak</h5>
            <div class="display-4 fw-bold text-white my-2"><?php echo $habit['longest_streak']; ?></div>
            <div class="text-secondary small">Days in a row</div>
        </div>
    </div>
    <div class="col-md-4 mb-4 fade-in-up delay-2">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-center text-center">
            <h5 class="text-secondary text-uppercase fs-7 ls-1">Total Logs</h5>
            <div class="display-4 fw-bold text-white my-2"><?php echo $total_last_30; ?></div>
            <div class="text-secondary small">In last 30 days</div>
        </div>
    </div>
    <div class="col-md-4 mb-4 fade-in-up delay-2">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-center text-center">
            <h5 class="text-secondary text-uppercase fs-7 ls-1">Consistency</h5>
            <div class="display-4 fw-bold <?php echo $rate >= 70 ? 'text-success' : 'text-warning'; ?> my-2"><?php echo $rate; ?>%</div>
            <div class="text-secondary small">Success Rate</div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('habitChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Completed',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: '#6366f1',
            borderRadius: 4,
            hoverBackgroundColor: '#8b5cf6'
        }]
    },
    options: {
        responsive: true,
        preserveAspectRatio: false,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                max: 1,
                ticks: { display: false },
                grid: { display: false, drawBorder: false }
            },
            x: {
                grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                ticks: { color: '#94a3b8' }
            }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
</script>

<?php include 'includes/footer.php'; ?>
