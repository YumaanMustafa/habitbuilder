<?php
require_once 'config.php';
require_login();

// Handle Delete
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM habits WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $success = "Habit deleted.";
}

// Fetch All Habits
$stmt = $pdo->prepare("SELECT * FROM habits WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$habits = $stmt->fetchAll();
?>
<?php include 'includes/header.php'; ?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold">My Habits</h2>
        <p class="text-secondary">Manage and track your goals.</p>
    </div>
</div>

<div class="row fade-in-up">
    <div class="col-12">
        <div class="glass-card">
            <?php if(empty($habits)): ?>
                <div class="p-5 text-center">
                    <p class="text-secondary">No active habits.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-borderless text-white mb-0">
                        <thead style="background: rgba(255,255,255,0.05); border-bottom: 1px solid var(--card-border);">
                            <tr>
                                <th class="p-3 ps-4">Goal</th>
                                <th class="p-3">Micro-Action</th>
                                <th class="p-3 text-center">Streak</th>
                                <th class="p-3 text-center">Best</th>
                                <th class="p-3 text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($habits as $h): ?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <td class="p-3 ps-4 align-middle fw-bold"><?php echo htmlspecialchars($h['goal']); ?></td>
                                <td class="p-3 align-middle text-secondary"><?php echo htmlspecialchars($h['micro_action']); ?></td>
                                <td class="p-3 align-middle text-center">
                                    <span class="badge rounded-pill bg-primary bg-opacity-25 text-primary border border-primary border-opacity-25">
                                        <?php echo $h['current_streak']; ?> 🔥
                                    </span>
                                </td>
                                <td class="p-3 align-middle text-center text-secondary"><?php echo $h['longest_streak']; ?></td>
                                <td class="p-3 align-middle text-end pe-4">
                                    <a href="habit_detail.php?id=<?php echo $h['id']; ?>" class="btn btn-sm btn-outline-light me-2">Details</a>
                                    <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this habit?');">
                                        <input type="hidden" name="delete_id" value="<?php echo $h['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
