# Micro-Habit Builder

A premium Micro-Habit Builder web application built with PHP, MySQL, and Bootstrap.

## Features
- **User Authentication**: Secure Login & Registration.
- **Habit Tracking**: Turn big goals into micro-actions.
- **Daily Dashboard**: Track streaks and mark tasks as done.
- **Streak Logic**: Tracks current and longest streaks automatically.
- **Habit Templates**: Built-in inspiration for Fitness, Studying, and Languages.
- **Premium UI**: Glassmorphism design, dark mode, and smooth animations.

## Setup Instructions

### 1. Database Setup
1.  Make sure you have a MySQL server running (e.g., via XAMPP, WAMP, or standalone MySQL).
2.  Open your MySQL client (like phpMyAdmin or Workbench).
3.  Create a new database named `micro_habit_builder`, or simply run the provided SQL file.
4.  Import the `database.sql` file included in this folder.
    - This will create the necessary tables (`users`, `habits`, `habit_templates`, `daily_completions`) and seed some template data.

### 2. Configuration
1.  Open `config.php`.
2.  Update the database credentials if yours differ from the defaults:
    ```php
    $host = 'localhost';
    $dbname = 'micro_habit_builder';
    $username = 'root'; // Update this
    $password = '';     // Update this
    ```

### 3. Running the App
**Option A: Using XAMPP/WAMP**
1. Move the `habitbuilder` folder into your `htdocs` (XAMPP) or `www` (WAMP) directory.
2. Open your browser and go to `http://localhost/habitbuilder`.

**Option B: Using Built-in PHP Server**
1. Open a terminal in this directory.
2. Run the following command:
   ```powershell
   php -S localhost:8000
   ```
3. Open your browser and go to `http://localhost:8000`.

## Enjoy building better habits!
