<?php
require_once 'config.php';
require_login();

// Mark Done Handling
if (isset($_GET['mark_done'])) {
    $habit_id = intval($_GET['mark_done']);
    $user_id = $_SESSION['user_id'];
    $today = date('Y-m-d');

    // Check if already completed today
    $stmt = $pdo->prepare("SELECT id FROM daily_completions WHERE habit_id = ? AND user_id = ? AND completed_date = ?");
    $stmt->execute([$habit_id, $user_id, $today]);
    
    if (!$stmt->fetch()) {
        // Insert Log
        $pdo->prepare("INSERT INTO daily_completions (habit_id, user_id, completed_date) VALUES (?, ?, ?)")
            ->execute([$habit_id, $user_id, $today]);

        // Streak Logic: Check yesterday for continuation
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $checkStreak = $pdo->prepare("SELECT id FROM daily_completions WHERE habit_id = ? AND user_id = ? AND completed_date = ?");
        $checkStreak->execute([$habit_id, $user_id, $yesterday]);

        if ($checkStreak->fetch()) {
             // Continue Streak
            $pdo->prepare("UPDATE habits SET current_streak = current_streak + 1 WHERE id = ?")->execute([$habit_id]);
        } else {
             // Reset/Start Streak at 1
             // Note: If I didn't do it yesterday, it's 1. 
             // Logic: If streak was 0, it becomes 1. If streak was 10 but I missed yesterday, it should have been 0 already by auto-reset. 
             // But if auto-reset hasn't run yet? The auto-reset block runs below on page load. 
             // So here we just conceptually say: IF I have a yesterday record, +1. ELSE 1.
             $pdo->prepare("UPDATE habits SET current_streak = 1 WHERE id = ?")->execute([$habit_id]);
        }

        // Update Longest
        $pdo->prepare("UPDATE habits SET longest_streak = MAX(current_streak, longest_streak) WHERE id = ?")->execute([$habit_id]); // SQLite uses MAX
    }
    header("Location: dashboard.php");
    exit;
}

// Auto-Reset Streaks Logic
$yesterday_date = date('Y-m-d', strtotime('-1 day'));
// In SQLite, we can't use complex join updates easily or nested select in update in same way strictly everywhere, 
// but standard SQL: UPDATE habits SET current_streak = 0 WHERE id NOT IN (SELECT habit_id FROM daily_completions WHERE date >= yesterday) works.
$pdo->exec("UPDATE habits SET current_streak = 0 
            WHERE id NOT IN (
                SELECT DISTINCT habit_id FROM daily_completions 
                WHERE completed_date >= '$yesterday_date'
            )");

// Fetch Habits & Status
$userId = $_SESSION['user_id'];
$today = date('Y-m-d');

$sql = "SELECT h.*, 
        CASE WHEN dc.id IS NOT NULL THEN 1 ELSE 0 END as is_done_today 
        FROM habits h 
        LEFT JOIN daily_completions dc ON h.id = dc.habit_id AND dc.completed_date = :today 
        WHERE h.user_id = :uid 
        ORDER BY h.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['uid' => $userId, 'today' => $today]);
$habits = $stmt->fetchAll();

// Calculate Progress
$totalHabits = count($habits);
$completedHabits = 0;
foreach($habits as $h) {
    if($h['is_done_today']) $completedHabits++;
}
$progressPercent = $totalHabits > 0 ? round(($completedHabits / $totalHabits) * 100) : 0;
?>
<?php include 'includes/header.php'; ?>

<div class="row align-items-center mb-5 fade-in-up">
    <div class="col-md-8">
        <h1 class="fw-bold mb-0">Good Morning, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p class="text-secondary fs-5 mt-2">You have completed <strong><?php echo $completedHabits; ?>/<?php echo $totalHabits; ?></strong> habits today.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="d-inline-flex align-items-center gap-3 glass-card px-3 py-2 rounded-pill">
            <span class="text-secondary fw-bold small text-uppercase">Daily Progress</span>
            <div class="d-flex align-items-center">
                <span class="fs-4 fw-bold <?php echo $progressPercent == 100 ? 'text-success' : 'text-primary'; ?>"><?php echo $progressPercent; ?>%</span>
            </div>
            <div style="width: 40px; height: 40px;">
                <canvas id="dailyProgressChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php if(empty($habits)): ?>
        <div class="col-12 fade-in-up delay-1">
            <div class="glass-card p-5 text-center">
                <div class="display-1 mb-4">🌱</div>
                <h3>Start Small, Dream Big</h3>
                <p class="text-secondary mb-4" style="max-width: 400px; margin: 0 auto;">You usually have no active habits. Create your first micro-habit to get started on your journey.</p>
                <a href="create_habit.php" class="btn btn-primary btn-lg">Create First Habit</a>
            </div>
        </div>
    <?php else: ?>
        <?php foreach($habits as $index => $habit): ?>
            <div class="col-md-6 col-lg-4 mb-4 fade-in-up" style="animation-delay: <?php echo ($index * 0.1) . 's'; ?>">
                <div class="glass-card h-100 d-flex flex-column justify-content-between p-4 <?php echo $habit['is_done_today'] ? 'border-success' : ''; ?>" style="<?php echo $habit['is_done_today'] ? 'border-color: rgba(16, 185, 129, 0.3);' : ''; ?>">
                    
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10 fw-normal rounded-pill px-3 py-2">
                            <?php echo htmlspecialchars($habit['goal']); ?>
                        </span>
                        <?php if($habit['current_streak'] > 0): ?>
                        <div class="streak-flame text-warning fw-bold d-flex align-items-center gap-1">
                            <i class="bi bi-fire"></i> <?php echo $habit['current_streak']; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <h4 class="mb-2"><?php echo htmlspecialchars($habit['micro_action']); ?></h4>
                        <?php if($habit['is_done_today']): ?>
                            <p class="text-success small"><i class="bi bi-check-all me-1"></i> Completed today</p>
                        <?php else: ?>
                            <p class="text-secondary small">Not done yet</p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <?php if($habit['is_done_today']): ?>
                            <button class="btn btn-success w-100 d-flex align-items-center justify-content-center gap-2 disabled text-white" style="opacity: 1;">
                                <i class="bi bi-check-circle-fill"></i> Done
                            </button>
                        <?php else: ?>
                            <a href="dashboard.php?mark_done=<?php echo $habit['id']; ?>" class="btn btn-primary w-100">
                                Mark Complete
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
// Mini Progress Chart
const ctx = document.getElementById('dailyProgressChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Done', 'Left'],
        datasets: [{
            data: [<?php echo $completedHabits; ?>, <?php echo $totalHabits - $completedHabits; ?>],
            backgroundColor: ['#10b981', '#334155'], // slate-700
            borderWidth: 0,
            cutout: '75%'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { enabled: false } },
        maintainAspectRatio: false
    }
});
</script>

<?php include 'includes/footer.php'; ?>
