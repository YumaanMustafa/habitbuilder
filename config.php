<?php
// config.php
// Switched to SQLite for "no-installation" database support

$db_file = __DIR__ . '/micro_habit.db';

try {
    // Create (connect to) SQLite database in file
    $pdo = new PDO("sqlite:$db_file");
    
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Enable Foreign Keys
    $pdo->exec("PRAGMA foreign_keys = ON;");

    // Initialize Database Schema automatically if not exists
    $query = "
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL UNIQUE,
        email TEXT NOT NULL UNIQUE,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    );

    CREATE TABLE IF NOT EXISTS habit_templates (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        category TEXT NOT NULL,
        goal TEXT NOT NULL,
        micro_action TEXT NOT NULL
    );

    CREATE TABLE IF NOT EXISTS habits (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        goal TEXT NOT NULL,
        micro_action TEXT NOT NULL,
        current_streak INTEGER DEFAULT 0,
        longest_streak INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS daily_completions (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        habit_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        completed_date DATE NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (habit_id) REFERENCES habits(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    );
    ";
    
    $pdo->exec($query);

    // Seed Templates if empty
    $check = $pdo->query("SELECT count(*) FROM habit_templates")->fetchColumn();
    if ($check == 0) {
        $pdo->exec("INSERT INTO habit_templates (category, goal, micro_action) VALUES 
        ('Fitness', 'Run a Marathon', 'Put on running shoes and walk out the door'),
        ('Fitness', 'Get 6-pack abs', 'Do 1 minute of planks'),
        ('Studying', 'Master Python', 'Write code for 5 minutes'),
        ('Studying', 'Read 20 books a year', 'Read 1 page'),
        ('Language Learning', 'Fluent in Spanish', 'Learn 3 new words'),
        ('Language Learning', 'Speak French', 'Listen to a French podcast for 5 mins')");
    }

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}

// Helper to check login
function require_login() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header("Location: index.php");
        exit;
    }
}
?>
