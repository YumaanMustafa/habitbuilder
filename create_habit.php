<?php
require_once 'config.php';
require_login();

$error = '';
$success = '';

// Handle Create Habit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $goal = trim($_POST['goal']);
    $micro_action = trim($_POST['micro_action']);

    if (empty($goal) || empty($micro_action)) {
        $error = "Please define both a goal and a micro-action.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO habits (user_id, goal, micro_action) VALUES (?, ?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $goal, $micro_action])) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Failed to create habit.";
        }
    }
}

// Fetch Templates
$templates = $pdo->query("SELECT * FROM habit_templates ORDER BY category")->fetchAll(PDO::FETCH_GROUP);
?>
<?php include 'includes/header.php'; ?>

<div class="row justify-content-center fade-in-up">
    <div class="col-md-8">
        <h2 class="mb-4">Create a New Habit</h2>
        
        <div class="row">
            <!-- Custom Form -->
            <div class="col-md-7 mb-4">
                <div class="glass-card p-4 h-100">
                    <h4 class="mb-3">Define Your Habit</h4>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                    <form method="POST" id="habitForm">
                        <div class="mb-3">
                            <label class="form-label text-secondary">Big Goal</label>
                            <input type="text" name="goal" id="inputGoal" class="form-control" placeholder="e.g. Run a Marathon">
                            <div class="form-text text-secondary">What is your ultimate aspiration?</div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-secondary">Micro-Action</label>
                            <input type="text" name="micro_action" id="inputAction" class="form-control" placeholder="e.g. Put on running shoes">
                            <div class="form-text text-secondary">A ridiculously small step you can do in < 2 minutes.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i>Create Habit
                        </button>
                    </form>
                </div>
            </div>

            <!-- Templates -->
            <div class="col-md-5 mb-4">
                <div class="glass-card p-4 h-100">
                    <h5 class="mb-3 text-secondary text-uppercase tracking-wider fs-6">Inspiration</h5>
                    
                    <?php foreach($templates as $category => $items): ?>
                        <h6 class="text-white mt-3 mb-2 border-bottom border-light pb-1" style="border-color: rgba(255,255,255,0.1)!important;"><?php echo htmlspecialchars($category); ?></h6>
                        <div class="list-group list-group-flush">
                            <?php foreach($items as $t): ?>
                                <button class="list-group-item list-group-item-action bg-transparent text-secondary border-0 px-0 py-2 d-flex justify-content-between align-items-center"
                                   onclick="fillForm('<?php echo addslashes($t['goal']); ?>', '<?php echo addslashes($t['micro_action']); ?>')">
                                   <div>
                                       <div class="text-white small"><?php echo htmlspecialchars($t['goal']); ?></div>
                                       <div class="small" style="font-size: 0.75rem"><?php echo htmlspecialchars($t['micro_action']); ?></div>
                                   </div>
                                   <i class="bi bi-arrow-left-circle text-primary"></i>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillForm(goal, action) {
    document.getElementById('inputGoal').value = goal;
    document.getElementById('inputAction').value = action;
}
</script>

<?php include 'includes/footer.php'; ?>
